const fs = require("fs");
const path = require('path');
const mysql = require("mysql2/promise");
const nodemailer = require("nodemailer");
const { Command } = require('commander');
const program = new Command();

const defaultPath = path.resolve(__dirname, "..", "..", "config.json");

program
  .option('-c, --config <path>', 'Config file path.', defaultPath)
  .parse(process.argv);

const options = program.opts();
const config = JSON.parse(fs.readFileSync(options.config));

const mysqlConfig = {
	host: config["mysql_host"],
	user: config["mysql_user"],
	password: config["mysql_password"],
	database: config["mysql_database"]
}
const regions = ["j", "int"];

const mailConfig = {
	host: config["smtp_host"],
	port: config["smtp_port"],
	secure: config["smtp_secure"] == "smtps",
	ignoreTLS: config["smtp_secure"] != "starttls"
}
if (config["stmp_auth"]) {
	mailConfig.auth = {
		type: "login",
		user: config["smtp_user"],
		pass: config["smtp_pass"],
	}
}

const mailTransport = nodemailer.createTransport(mailConfig);

async function doExchange() {
	const connection = await mysql.createConnection(mysqlConfig);
	await connection.beginTransaction();
	for (const region of regions) {
		console.log("Begin exchange for region " + region);
		let numTrades = await doExchangeForRegion(region, connection);
		console.log(`Finished exchange for region ${region}; performed ${numTrades} trade(s)`);
	}
	await connection.commit();
	//await connection.rollback();
	await connection.end();
}

async function doExchangeForRegion(region, connection) {
	let table = (region == "j" ? "bxtj_exchange" : "bxt_exchange");
	// TODO: send an email for this
	await connection.execute("delete from " + table + " where entry_time < now() - interval 7 day");
	let [trades] = await connection.execute("select * from " + table + " order by entry_time asc");
	let performedTrades = new Set();
	
	for (let i = 0; i < trades.length; i++) {
		if (performedTrades.has(i)) continue;
		for (let j = i + 1; j < trades.length; j++) {
			if (performedTrades.has(j)) continue;
			if (
				trades[i]["offer_species"] == trades[j]["request_species"] &&
				trades[i]["request_species"] == trades[j]["offer_species"] &&
				(trades[i]["offer_gender"] == trades[j]["request_gender"] || trades[j]["request_gender"] == 3) &&
				(trades[i]["request_gender"] == trades[j]["offer_gender"] || trades[i]["request_gender"] == 3)
			) {
				performedTrades.add(i);
				performedTrades.add(j);
				try {
					await sendExchangeSuccessEmail(region == "j" ? "j" : trades[i]["game_region"], trades[i]["email"], trades[i]["trainer_id"], trades[i]["secret_id"], trades[i]["offer_species"], trades[i]["request_species"], trades[i]["offer_gender"], trades[i]["request_gender"], trades[j]["trainer_name"], trades[j]["pokemon"], trades[j]["mail"]);
					await sendExchangeSuccessEmail(region == "j" ? "j" : trades[j]["game_region"], trades[j]["email"], trades[j]["trainer_id"], trades[j]["secret_id"], trades[j]["offer_species"], trades[j]["request_species"], trades[j]["offer_gender"], trades[j]["request_gender"], trades[i]["trainer_name"], trades[i]["pokemon"], trades[i]["mail"]);
					await connection.execute("delete from " + table + " where (account_id = ? and trainer_id = ? and secret_id = ?) or (account_id = ? and trainer_id = ? and secret_id = ?)", [trades[i]["account_id"], trades[i]["trainer_id"], trades[i]["secret_id"], trades[j]["account_id"], trades[j]["trainer_id"], trades[j]["secret_id"]]);
				} catch (error) {
					console.error(error);
				}
				break;
			}
		}
	}
	return performedTrades.size / 2;
}

async function sendExchangeSuccessEmail(region, emailAddress, trainerId, secretId, offerSpecies, requestSpecies, offerGender, requestGender, trainerName, pokemon, mail) {
	let emailContent =
		"MIME-Version: 1.0\r\n" +
		"From: MISSINGNO.\r\n" +
		"Subject: Trade\r\n" +
		"X-Game-title: POCKET MONSTERS\r\n" + // this should be 16 characters at most
		`X-Game-code: CGB-BXT${region.toUpperCase()}-00\r\n` +
		`X-Game-result: 1 ${toHexString(trainerId, 2)}${toHexString(secretId, 2)} ${toHexString(offerGender, 1)}${toHexString(offerSpecies, 1)} ${toHexString(requestGender, 1)}${toHexString(requestSpecies, 1)} 1\r\n` +
		"X-GBmail-type: exclusive\r\n" +
		"Content-Type: application/octet-stream\r\n" +
		"Content-Transfer-Encoding: base64\r\n" +
		"\r\n" +
		`${Buffer.concat([trainerName, pokemon, mail]).toString("base64")}\r\n\r\n`
	;
	await mailTransport.sendMail({
		envelope: {
			from: "system@" + config["email_domain"],
			to: emailAddress
		},
		raw: emailContent
	});
}

function toHexString(integer, size) {
    var str = Number(integer).toString(16);
	for (let i = str.length; i < size * 2; i++) {
		str = "0" + str;
	}
	return str;
};

doExchange();