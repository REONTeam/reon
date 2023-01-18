const net = require("net");
const mysql = require("mysql");
const POP3Connection = require("./pop3Connection").POP3Connection;

class POP3Server {
	constructor(mysqlConfig) {
		this.connections = new Set();
		this.mysql = mysql.createPool(mysqlConfig);
		net.createServer(sock => this._onClientConnect(sock)).listen(110, "0.0.0.0");
		console.log("POP3 server listening");
	}
	
	_onClientConnect(socket) {
		console.log("(POP3) CONNECTED: " + socket.remoteAddress + ":" + socket.remotePort);
		let conn = new POP3Connection(this, socket);
		conn.on("disconnect", (connection, ip, port) => this._onClientDisconnect(connection, ip, port));
		conn.on("command", (command, user, ip, port) => this._onClientCommand(command, user, ip, port));
		conn.on("error", error => this._onClientError(error));
		this.connections.add(conn);
	}
	
	_onClientDisconnect(connection, ip, port) {
		console.log("(POP3) DISCONNECTED: " + ip + ":" + port);
		this.connections.delete(connection);
	}
	
	_onClientCommand(command, user, ip, port) {
		console.log("(POP3) " + ip + ":" + port + (user == null ? "" : " (" + user + ")") + ": " + command);
	}
	
	_onClientError(error) {
		console.log("(POP3) " + error);
	}
}
module.exports.POP3Server = POP3Server;
