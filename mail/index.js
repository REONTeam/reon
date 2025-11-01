const fs = require("fs");
const SMTPServer = require("./smtp").SMTPServer;
const POP3Server = require("./pop3").POP3Server;
const { Command } = require('commander');
const program = new Command();

program
  .option('-c, --config <path>', 'Config file path.', "../config.json")
  .parse(process.argv);

const options = program.opts();
const config = JSON.parse(fs.readFileSync(options.config));

const mysqlConfig = {
	host: config["mysql_host"],
	user: config["mysql_user"],
	password: config["mysql_password"],
	database: config["mysql_database"]
}

let smtp = new SMTPServer(mysqlConfig, config["email_domain"], config["email_domain_dion"]);
let pop3 = new POP3Server(mysqlConfig);