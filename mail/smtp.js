const net = require("net");
const SMTPConnection = require("./smtpConnection").SMTPConnection;
const mysql = require("mysql2/promise");

class SMTPServer {
	constructor(mysqlConfig, domain, dionDomain) {
		this.connections = new Set();
		this.mysql = mysql.createPool(mysqlConfig);
		this.domain = domain;
		this.dionDomain = dionDomain;
		net.createServer(sock => this._onClientConnect(sock)).listen(25, "::");
		console.log("SMTP server listening");
	}
	
	_onClientConnect(socket) {
		console.log("(SMTP) CONNECTED: " + socket.remoteAddress + ":" + socket.remotePort);
		let conn = new SMTPConnection(this, socket);
		conn.on("disconnect", (connection, ip, port) => this._onClientDisconnect(connection, ip, port));
		conn.on("command", (command, user, ip, port) => this._onClientCommand(command, user, ip, port));
		conn.on("error", error => this._onClientError(error));
		this.connections.add(conn);
	}
	
	_onClientDisconnect(connection, ip, port) {
		console.log("(SMTP) DISCONNECTED: " + ip + ":" + port);
		this.connections.delete(connection);
	}
	
	_onClientCommand(command, user, ip, port) {
		console.log("(SMTP) " + ip + ":" + port + (user == null ? "" : " (" + user + ")") + ": " + command);
	}
	
	_onClientError(error) {
		console.log("(SMTP) " + error);
	}
}
module.exports.SMTPServer = SMTPServer;
