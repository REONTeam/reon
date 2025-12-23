const fs = require("fs");
const path = require("path");
const mysql = require("mysql2/promise");
const nodemailer = require("nodemailer");

const { Command } = require("commander");

// ------------------------------
// Config
// ------------------------------

const defaultPath = path.resolve(__dirname, "..", "..", "config.json");

const program = new Command();
program
  .option("-c, --config <path>", "Config file path.", defaultPath)
  .parse(process.argv);

const options = program.opts();
const config = JSON.parse(fs.readFileSync(options.config, "utf8"));

const dbConfig = {
  host: config["mysql_host"],
  port: config["mysql_port"] || 3306,
  user: config["mysql_user"],
  password: config["mysql_password"],
  database: config["mysql_database"],
};

// ------------------------------
// SMTP transport – mirror PHP UserUtil.php config
// ------------------------------

let mailTransport;

const smtpHost = config["smtp_host"];
const smtpPort = config["smtp_port"];
const smtpAuth = config["smtp_auth"];
const smtpSecure = config["smtp_secure"];

if (!smtpHost || smtpHost === "") {
  // Sendmail mode (PHP isSendmail())
  mailTransport = nodemailer.createTransport({
    sendmail: true,
    newline: "unix",
    path: "/usr/sbin/sendmail", // adjust if different on your system
  });
} else {
  // SMTP mode (PHP isSMTP())
  const transportOptions = {
    host: smtpHost,
    port: smtpPort || 587,
    secure: smtpSecure === "smtps", // implicit TLS
    requireTLS: smtpSecure === "starttls", // STARTTLS
    auth: smtpAuth
      ? {
          user: config["smtp_user"],
          pass: config["smtp_pass"],
        }
      : undefined,
    // allow self-signed like your PHP setup
    tls: {
      rejectUnauthorized: false,
    },
  };

  mailTransport = nodemailer.createTransport(transportOptions);
}

// ------------------------------
// Email + main exchange logic
// ------------------------------

async function doExchange() {
  const connection = await mysql.createConnection(dbConfig);

  try {
    await connection.beginTransaction();

    const table = "amc_trades";

    const [trades] = await connection.execute(
      "SELECT * FROM " + table + " ORDER BY game_region ASC, timestamp ASC"
    );

    // group trades by region and pair sequentially within each region
    const byRegion = new Map();
    for (const trade of trades) {
      const region = trade["game_region"] ?? "";
      if (!byRegion.has(region)) byRegion.set(region, []);
      byRegion.get(region).push(trade);
    }

    for (const [region, list] of byRegion.entries()) {
      for (let i = 1; i < list.length; i += 2) {
        const a = list[i - 1];
        const b = list[i];

        await mailTransport.sendMail({
          envelope: {
            from: a["email"],
            to: b["email"],
          },
          raw: "To: " + b["email"] + "\r\n" + a["message"],
        });
        await mailTransport.sendMail({
          envelope: {
            from: b["email"],
            to: a["email"],
          },
          raw: "To: " + a["email"] + "\r\n" + b["message"],
        });

        // Clean up processed rows
        await connection.execute("DELETE FROM " + table + " WHERE id = ?", [a["id"]]);
        await connection.execute("DELETE FROM " + table + " WHERE id = ?", [b["id"]]);
      }
    },
        raw: "To: " + trades[i]["email"] + "\r\n" + trades[i-1]["message"],
      });
      await mailTransport.sendMail({
        envelope: {
          from: trades[i]["email"],
          to: trades[i-1]["email"],
        },
        raw: "To: " + trades[i-1]["email"] + "\r\n" + trades[i]["message"],
      });

      await connection.execute(
        "DELETE FROM " + table + " WHERE id = ?", [trades[i-1]["id"]]
      );
      await connection.execute(
        "DELETE FROM " + table + " WHERE id = ?", [trades[i]["id"]]
      );
    }

    await connection.commit();
    console.log("Finished exchange");
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

doExchange();
