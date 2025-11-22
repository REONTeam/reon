const fs = require("fs");
const path = require("path");
const mysql = require("mysql");
const nodemailer = require("nodemailer");

function createMySqlConnection(config) {
    return new Promise((resolve, reject) => {
        const conn = mysql.createConnection(config);
        conn.connect(err => {
            if (err) return reject(err);
            resolve(conn);
        });
    });
}

function execute(conn, sql, params) {
    return new Promise((resolve, reject) => {
        conn.query(sql, params || [], (err, results, fields) => {
            if (err) return reject(err);
            resolve([results, fields]);
        });
    });
}

const { Command } = require("commander");
const { loadBxtConfig } = require("../bxt_config_loader");
const program = new Command();

const defaultPath = path.resolve(__dirname, "..", "..", "config.json");

program
  .option("-c, --config <path>", "Config file path.", defaultPath)
  .parse(process.argv);

const options = program.opts();
const config = JSON.parse(fs.readFileSync(options.config, "utf8"));

const phpConfigPath = path.resolve(__dirname, "..", "..", "web", "cgb", "pokemon", "bxt_config.php");
const bxtConfig = loadBxtConfig(phpConfigPath);

// Database connection
const dbConfig = {
    host: config["mysql_host"],
    port: config["mysql_port"],
    user: config["mysql_user"],
    password: config["mysql_password"],
    database: config["mysql_database"],
};

// Mail transport
const mailConfig = {
    host: config["smtp_host"],
    port: config["smtp_port"],
    secure: config["smtp_secure"] === "smtps",
    ignoreTLS: config["smtp_secure"] !== "starttls",
};
if (config["stmp_auth"]) {
    mailConfig.auth = {
        type: "login",
        user: config["smtp_user"],
        pass: config["smtp_pass"],
    };
}
const mailTransport = nodemailer.createTransport(mailConfig);

// -------- Trade Corner config wiring --------

// Global Trade Corner feature toggle from PHP config.
const TRADE_CORNER_ENABLED = !!bxtConfig.trade_corner_enabled;

/**
 * Parse region_groups['trade_corner'] directly from the PHP bxt_config.php so that
 * JavaScript sees exactly the same grouping the PHP layer uses.
 *
 * This parser does NOT try to JSON-parse the PHP; it just scans for
 *   'trade' => [
 *       ['e','f',...],
 *       ['j'],
 *   ],
 * within the region_groups block and extracts each ['...'] array.
 */
function loadTradeRegionGroupsFromPhpConfig(phpPath) {
    try {
        const php = fs.readFileSync(phpPath, "utf8");

        let tradeKey = "'trade_corner' => [";
        let start = php.indexOf(tradeKey);
        if (start === -1) {
            // Backwards-compatibility: older configs may still use 'trade'.
            tradeKey = "'trade' => [";
            start = php.indexOf(tradeKey);
        }
        if (start === -1) {
            return [];
        }

        // Heuristic end: up to the start of the next feature in region_groups.
        // In your config this is 'battle_tower', immediately after the trade block.
        let end = php.indexOf("'battle_tower'", start);
        if (end === -1) {
            // Fallback: just take a small slice after 'trade' block.
            end = start + 512;
            if (end > php.length) end = php.length;
        }

        const block = php.slice(start, end);

        const groups = [];
        const arrayRegex = /\[([^\]]+)\]/g;
        let m;
        while ((m = arrayRegex.exec(block)) !== null) {
            const inner = m[1];
            const codes = [];
            const codeRegex = /'([^']+)'/g;
            let m2;
            while ((m2 = codeRegex.exec(inner)) !== null) {
                codes.push(String(m2[1]));
            }
            if (codes.length) {
                groups.push(codes);
            }
        }

        return groups;
    } catch (e) {
        console.error("Failed to load trade region groups from PHP config:", e);
        return [];
    }
}

// Region groups now come ONLY from the PHP file.
const TRADE_REGION_GROUPS = loadTradeRegionGroupsFromPhpConfig(phpConfigPath);
// Uncomment for debugging if needed:
// console.log("TRADE_REGION_GROUPS:", JSON.stringify(TRADE_REGION_GROUPS));

/**
 * Determine if two regions are allowed to trade.
 * True if:
 *   - regions match, OR
 *   - both appear in at least one entry of TRADE_REGION_GROUPS.
 */
function regionCanTrade(a, b) {
    if (!a || !b) return false;
    a = String(a).toLowerCase();
    b = String(b).toLowerCase();

    if (a === b) return true;

    for (const group of TRADE_REGION_GROUPS) {
        if (!Array.isArray(group)) continue;
        const g = group.map(x => String(x).toLowerCase());
        if (g.includes(a) && g.includes(b)) return true;
    }
    return false;
}

async function doExchange() {
    const connection = await createMySqlConnection(dbConfig);
    try {
        // Global feature toggle check: if Trade Corner is disabled, do nothing.
        if (!TRADE_CORNER_ENABLED) {
            console.log("Trade Corner is disabled (TRADE_CORNER_ENABLED = false); skipping exchange run.");
            await new Promise((resolve, reject) =>
                connection.end(err => (err ? reject(err) : resolve()))
            );
            return;
        }

        await new Promise((resolve, reject) =>
            connection.beginTransaction(err => (err ? reject(err) : resolve()))
        );

        const table = "bxt_exchange";

        // Expire old trades
        await execute(
            connection,
            "DELETE FROM " + table + " WHERE timestamp < NOW() - INTERVAL 7 DAY"
        );

        const [allTrades] = await execute(
            connection,
            "SELECT * FROM " + table + " ORDER BY timestamp ASC"
        );

        const trades = allTrades;

        let performedTrades = new Set();
        let numTrades = 0;

        for (let i = 0; i < trades.length; i++) {
            if (performedTrades.has(i)) continue;
            for (let j = i + 1; j < trades.length; j++) {
                if (performedTrades.has(j)) continue;

                const a = trades[i];
                const b = trades[j];

                if (
                    regionCanTrade(a["game_region"], b["game_region"]) &&
                    a["offer_species"] == b["request_species"] &&
                    a["request_species"] == b["offer_species"] &&
                    (a["offer_gender"] == b["request_gender"] || b["request_gender"] == 3) &&
                    (a["request_gender"] == b["offer_gender"] || a["request_gender"] == 3)
                ) {
                    performedTrades.add(i);
                    performedTrades.add(j);
                    numTrades++;

                    try {
                        await sendExchangeSuccessEmail(
                            a["game_region"],
                            b["email"],
                            b["trainer_id"],
                            b["secret_id"],
                            b["offer_species"],
                            a["request_species"],
                            b["offer_gender"],
                            a["request_gender"],
                            a["player_name"],
                            a["pokemon"],
                            a["mail"]
                        );
                        await sendExchangeSuccessEmail(
                            b["game_region"],
                            a["email"],
                            a["trainer_id"],
                            a["secret_id"],
                            a["offer_species"],
                            b["request_species"],
                            a["offer_gender"],
                            b["request_gender"],
                            b["player_name"],
                            b["pokemon"],
                            b["mail"]
                        );

                        await execute(
                            connection,
                            "DELETE FROM " + table + " WHERE email = ? AND account_id = ? AND trainer_id = ? AND secret_id = ? LIMIT 1",
                            [b["email"], b["account_id"], b["trainer_id"], b["secret_id"]]
                        );
                        await execute(
                            connection,
                            "DELETE FROM " + table + " WHERE email = ? AND account_id = ? AND trainer_id = ? AND secret_id = ? LIMIT 1",
                            [a["email"], a["account_id"], a["trainer_id"], a["secret_id"]]
                        );
                    } catch (e) {
                        console.error("Error performing trade:", e);
                    }

                    // Move on to next base offer
                    break;
                }
            }
        }

        await new Promise((resolve, reject) =>
            connection.commit(err => (err ? reject(err) : resolve()))
        );
        console.log(`Finished exchange; performed ${numTrades} trade(s)`);
    } catch (e) {
        console.error("Exchange failed, rolling back:", e);
        try {
            await new Promise((resolve, reject) =>
                connection.rollback(err => (err ? reject(err) : resolve()))
            );
        } catch (_) {}
    } finally {
        try {
            await new Promise((resolve, reject) =>
                connection.end(err => (err ? reject(err) : resolve()))
            );
        } catch (_) {}
    }
}

async function sendExchangeSuccessEmail(
    region,
    emailAddress,
    trainerId,
    secretId,
    offerSpecies,
    requestSpecies,
    offerGender,
    requestGender,
    trainerName,
    pokemon,
    mail
) {
    const r = String(region || "e").toUpperCase();

    let emailContent =
        "MIME-Version: 1.0\r\n" +
        "From: MISSINGNO.\r\n" +
        "Subject: Trade\r\n" +
        "X-Game-title: POCKET MONSTERS\r\n" +
        `X-Game-code: CGB-BXT${r}-00\r\n` +
        `X-Game-result: 1 ${toHexString(trainerId, 2)}${toHexString(secretId, 2)}${toHexString(offerGender, 1)}${toHexString(offerSpecies, 1)}${toHexString(requestGender, 1)}${toHexString(requestSpecies, 1)} 1\r\n` +
        "X-GBmail-type: exclusive\r\n" +
        "Content-Type: application/octet-stream\r\n" +
        "Content-Transfer-Encoding: base64\r\n" +
        "\r\n" +
        `${Buffer.concat([trainerName, pokemon, mail]).toString("base64")}\r\n\r\n`;

    await mailTransport.sendMail({
        envelope: {
            from: "system@" + config["email_domain"],
            to: emailAddress,
        },
        raw: emailContent,
    });
}

function toHexString(integer, size) {
    let str = Number(integer).toString(16);
    while (str.length < size * 2) {
        str = "0" + str;
    }
    return str;
}

doExchange();
