const fs = require("fs");
const path = require("path");
const mysql = require("mysql2/promise");
const { Command } = require("commander");
const { loadBxtConfig } = require("../bxt_config_loader");

const program = new Command();
const defaultPath = path.resolve(__dirname, "..", "..", "config.json");

program
  .option("-c, --config <path>", "Config file path.", defaultPath)
  .parse(process.argv);

const options = program.opts();
const config = JSON.parse(fs.readFileSync(options.config, "utf8"));

const mysqlConfig = {
    host: config["mysql_host"],
    user: config["mysql_user"],
    password: config["mysql_password"],
    database: config["mysql_database"],
};

// Load PHP BXT config so we can derive the active regions from game_region_map.
const phpConfigPath = path.resolve(__dirname, "..", "..", "web", "cgb", "pokemon", "bxt_config.php");
const bxtConfig = loadBxtConfig(phpConfigPath) || {};

// Derive region codes from game_region_map (e,f,d,s,i,j,p,u,...).
let regions = [];
if (bxtConfig.game_region_map && typeof bxtConfig.game_region_map === "object") {
    const vals = Object.values(bxtConfig.game_region_map)
        .map(r => String(r).toLowerCase())
        .filter(Boolean);
    regions = Array.from(new Set(vals));
}
// Fallback if config is missing or empty.
if (regions.length === 0) {
    regions = ["e", "f", "d", "s", "i", "j", "p", "u"];
}

const numRoomsPerLevel = 20;

async function updateContent() {
    const connection = await mysql.createConnection(mysqlConfig);
    await connection.beginTransaction();
    try {
        // Globally expire old records once per run.
        await connection.execute(
            "DELETE FROM bxt_battle_tower_records WHERE timestamp < NOW() - INTERVAL 7 DAY"
        );

        for (const region of regions) {
            console.log("Begin battle content update for region " + region);
            await updateContentForRegion(region, connection);
            console.log("Finished battle content update for region " + region);
        }

        await connection.commit();
    } catch (e) {
        console.error("Battle content update failed, rolling back:", e);
        await connection.rollback();
    } finally {
        await connection.end();
    }
}

async function updateContentForRegion(region, connection) {
    // Rebuild trainer pool for this region from scratch each run.
    await connection.execute(
        "DELETE FROM bxt_battle_tower_trainers WHERE game_region = ?",
        [region]
    );

    // Track which trainers we've already inserted for this region to avoid PK duplicates.
    const seenTrainerKeys = new Set();

    for (let level = 0; level < 10; level++) {
        for (let room = 0; room < numRoomsPerLevel; room++) {
            // 1) Select best 6 trainers for this region/level/room.
            const [selectedTrainersBase] = await connection.execute(
                "SELECT " +
                " id, trainer_id, secret_id, account_id, " +
                " player_name, player_name_decode, " +
                " `class`, `class_decode`, " +
                " pokemon1, pokemon1_decode, " +
                " pokemon2, pokemon2_decode, " +
                " pokemon3, pokemon3_decode, " +
                " message_start, message_start_decode, " +
                " message_win, message_win_decode, " +
                " message_lose, message_lose_decode, " +
                " level, level_decode " +
                "FROM bxt_battle_tower_records " +
                "WHERE game_region = ? AND level = ? AND room = ? " +
                "ORDER BY num_trainers_defeated DESC, " +
                "         num_turns_required DESC, " +
                "         damage_taken DESC, " +
                "         num_fainted_pokemon DESC " +
                "LIMIT 6",
                [region, level, room]
            );

            let selectedTrainers = selectedTrainersBase;

            // 2) Select a random 7th trainer (first encountered) not in the top 6.
            if (selectedTrainers.length === 6) {
                const ids = selectedTrainers.map(trainer => trainer.id);
                const placeholders = ids.map(() => "?").join(",");
                const params = [region, level, room, ...ids];

                const [seventhTrainer] = await connection.query(
                    "SELECT " +
                    " id, trainer_id, secret_id, account_id, " +
                    " player_name, player_name_decode, " +
                    " `class`, `class_decode`, " +
                    " pokemon1, pokemon1_decode, " +
                    " pokemon2, pokemon2_decode, " +
                    " pokemon3, pokemon3_decode, " +
                    " message_start, message_start_decode, " +
                    " message_win, message_win_decode, " +
                    " message_lose, message_lose_decode, " +
                    " level, level_decode " +
                    "FROM bxt_battle_tower_records " +
                    "WHERE game_region = ? AND level = ? AND room = ? " +
                    "  AND id NOT IN (" + placeholders + ") " +
                    "ORDER BY RAND() " +
                    "LIMIT 1",
                    params
                );

                if (seventhTrainer && seventhTrainer.length !== 0) {
                    selectedTrainers.push(seventhTrainer[0]);
                }
            }

            // 3) Maintain honor roll (leaders table) for this region/level/room.
            //    We do NOT delete records or trainers here; records are trimmed globally.
            // Honor roll is append-only: do not delete existing rows.

            if (selectedTrainers.length > 0) {
                const leader = selectedTrainers[0];

                // Insert leader with decoded name and level.
                await connection.execute(
                    "INSERT INTO bxt_battle_tower_honor_roll " +
                    "(game_region, player_name, player_name_decode, `class`, `class_decode`, " +
                    "pokemon1, pokemon1_decode, pokemon2, pokemon2_decode, pokemon3, pokemon3_decode, " +
                    "message_start, message_start_decode, room, level, level_decode) " +
                    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        region,
                        leader.player_name,
                        leader.player_name_decode || null,
                        leader.class || null,
                        leader.class_decode || null,
                        (leader.pokemon1 ?? null),
                        leader.pokemon1_decode || null,
                        (leader.pokemon2 ?? null),
                        leader.pokemon2_decode || null,
                        (leader.pokemon3 ?? null),
                        leader.pokemon3_decode || null,
                        (leader.message_start ?? null),
                        leader.message_start_decode || null,
                        room,
                        level,
                        leader.level_decode || null,
                    ]
                );

                // Keep honor roll capped at 30 entries per (game_region, room, level),
                // deleting the oldest by timestamp first (ties broken by id).
                const [hrCountRows] = await connection.execute(
                    "SELECT COUNT(*) AS cnt " +
                    "FROM bxt_battle_tower_honor_roll " +
                    "WHERE game_region = ? AND room = ? AND level = ?",
                    [region, room, level]
                );
                const hrCount = Number(hrCountRows?.[0]?.cnt ?? 0);
                if (hrCount > 30) {
                    const excess = hrCount - 30;
                    await connection.execute(
                        "DELETE FROM bxt_battle_tower_honor_roll " +
                        "WHERE id IN (" +
                        "  SELECT id FROM (" +
                        "    SELECT id " +
                        "    FROM bxt_battle_tower_honor_roll " +
                        "    WHERE game_region = ? AND room = ? AND level = ? " +
                        "    ORDER BY `timestamp` ASC, id ASC " +
                        "    LIMIT ?" +
                        "  ) AS t" +
                        ")",
                        [region, room, level, excess]
                    );
                }

            }

            // 4) Populate trainer pool table (bxt_battle_tower_trainers) for this region.
            //    Deduplicate on (region, trainer_id, secret_id, account_id) to match composite PK.
            if (selectedTrainers.length > 0) {
                const insertTrainerSql =
                    "INSERT INTO bxt_battle_tower_trainers " +
                    "(game_region, room, level, level_decode, no, trainer_id, secret_id, player_name, player_name_decode, " +
                    " `class`, `class_decode`, " +
                    " pokemon1, pokemon1_decode, " +
                    " pokemon2, pokemon2_decode, " +
                    " pokemon3, pokemon3_decode, " +
                    " message_start, message_start_decode, " +
                    " message_win, message_win_decode, " +
                    " message_lose, message_lose_decode, " +
                    " account_id) " +
                    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                let slot = 0;
                for (const t of selectedTrainers) {
                    const key = region + "-" + t.trainer_id + "-" + t.secret_id + "-" + t.account_id;
                    if (seenTrainerKeys.has(key)) {
                        continue;
                    }
                    seenTrainerKeys.add(key);

                    await connection.execute(insertTrainerSql, [
                        region,
                        room,
                        level,
                        t.level_decode || null,
                        slot,
                        t.trainer_id,
                        t.secret_id,
                        t.player_name,
                        t.player_name_decode || null,
                        t.class,
                        t.class_decode || null,
                        t.pokemon1,
                        t.pokemon1_decode || null,
                        t.pokemon2,
                        t.pokemon2_decode || null,
                        t.pokemon3,
                        t.pokemon3_decode || null,
                        t.message_start,
                        t.message_start_decode || null,
                        t.message_win,
                        t.message_win_decode || null,
                        t.message_lose,
                        t.message_lose_decode || null,
                        t.account_id,
                    ]);
                    slot++;
                }
            }
        }
    }
}

updateContent();
