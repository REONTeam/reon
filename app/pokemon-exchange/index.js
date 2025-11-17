const fs = require("fs");
const path = require("path");
const mysql = require("mysql2/promise");
const nodemailer = require("nodemailer");
const { Command } = require("commander");
const program = new Command();

const defaultPath = path.resolve(__dirname, "..", "..", "config.json");

program
  .option("-c, --config <path>", "Config file path.", defaultPath)
  .parse(process.argv);

const options = program.opts();
const config = JSON.parse(fs.readFileSync(options.config));

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

// Trade Corner international region groups.
//
// Two offers are allowed to trade if either:
//   - their game_region is identical, OR
//   - they appear together in at least one entry in TRADE_REGION_GROUPS.
//
// By default, all non-Japanese regions can trade with each other,
// while Japanese ('j') is isolated unless explicitly added.
const TRADE_REGION_GROUPS = [
    ["e","f","d","s","i","p","u"],
];

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
    const connection = await mysql.createConnection(dbConfig);
    try {
        await connection.beginTransaction();

        const table = "bxt_exchange";

        // Expire old trades
        await connection.execute(
            "DELETE FROM " + table + " WHERE timestamp < NOW() - INTERVAL 7 DAY"
        );

        const [trades] = await connection.execute(
            "SELECT * FROM " + table + " ORDER BY timestamp ASC"
        );

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
                            b["player_name"],
                            b["pokemon"],
                            b["mail"]
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
                            a["player_name"],
                            a["pokemon"],
                            a["mail"]
                        );

                        await connection.execute(
                            "DELETE FROM " + table + " WHERE email = ? AND account_id = ? AND trainer_id = ? AND secret_id = ? LIMIT 1",
                            [b["email"], b["account_id"], b["trainer_id"], b["secret_id"]]
                        );
                        await connection.execute(
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

        await connection.commit();
        console.log(`Finished exchange; performed ${numTrades} trade(s)`);
    } catch (e) {
        console.error("Exchange failed, rolling back:", e);
        try {
            await connection.rollback();
        } catch (_) {}
    } finally {
        try {
            await connection.end();
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
