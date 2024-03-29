const EventEmitter = require("events");

class SMTPConnection extends EventEmitter {
	constructor(server, sock) {
		super();
		
		this._server = server;
		this._socket = sock;
		
		this._inputBuffer = "";
		this._isProcessingInput = false;
		this._isBusy = true;
		
		this._initSession();
		
		this._socket.on('data', data => this._onData(data));
		this._socket.on('close', () => this._onClose());
		this._socket.on('error', error => this._onError(error));
		
		this._send(220, "service ready");
		this._isBusy = false;
	}
	
	_initSession() {
		this._hostname = null;
		this._reversePath = null;
		this._forwardPath = [];
		this._dataInputMode = false;
		this._mailData = "";
	}
	
	_isMailAddressedToUs(address) {
		return address.endsWith("@" + this._server.dionDomain) || address.endsWith("@" + this._server.domain);
	}
	
	_sliceDomain(address) {
		return address.substring(0, address.indexOf("@"));
	}
	
	_send(code, data) {
        if (this._socket && this._socket.writable) {
            this._socket.write(code + " " + data + "\r\n");
        }
		this._isBusy = false;
    }
	
	close() {
        if (!this._socket.destroyed && this._socket.writable) {
            this._socket.end();
        }
		this.emit("disconnect", this, this._socket.remoteAddress, this._socket.remotePort);
    }
	
	_onClose() {
		this.close();
	}
	
	async _onData(data) {
		this._inputBuffer += data;
		// Start processing the input buffer if not already
		if (!this._isProcessingInput) {
			this._isProcessingInput = true;
			// While there are commands
			while (this._inputBuffer.indexOf("\r\n") != -1) {
				// Make sure commands run one after another and not in parallel
				if (!this._isBusy) {
					this._isBusy = true;
					try {
						if (this._dataInputMode) {
							await this._handleData(this._inputBuffer.substring(0, this._inputBuffer.indexOf("\r\n") + 2));
						} else {
							this._onCommand(this._inputBuffer.substring(0, this._inputBuffer.indexOf("\r\n") + 2));
						}
					} catch (error) {
						this._onError(error);
					} finally {
						// Remove the processed command from buffer
						this._inputBuffer = this._inputBuffer.substring(this._inputBuffer.indexOf("\r\n") + 2);
						this._isBusy = false;
					}
				}
			}
			this._isProcessingInput = false;
		}
	}
	
	_onError(error) {
		this.emit("error", error);
		this._send(451, "server error");
	}
	
	_onCommand(command) {
		let commandName = command.indexOf(" ") == -1 ? command.substring(0, command.indexOf("\r\n") == -1 ? 0 : command.indexOf("\r\n")) : command.substring(0, command.indexOf(" "));
		this.emit("command", commandName, this._hostname, this._socket.remoteAddress, this._socket.remotePort);
		if (this._isCommandSupported(commandName)) {
			this["_commandHandler_" + commandName].call(this, command.indexOf(" ") == -1 ? null : command.substring(command.indexOf(" ") + 1, command.indexOf("\r\n")));
		} else {
			this._send(500, "command not recognized");
		}
	}
	
    _isCommandSupported(command) {
        return typeof this["_commandHandler_" + command] === "function";
    }
	
	_commandHandler_HELO(param) {
		if (param != null && param != "") {
			this._initSession();
			this._hostname = param;
			this._send(250, "OK");
		} else {
			this._send(501, "invalid parameter");
		}
    }
	
	_commandHandler_QUIT(param) {
        this._send(221, "see ya");
        this.close();
    }
	
	_commandHandler_MAIL(param) {
		if (this._hostname != null) {
			if (param != null && param != "" && param.indexOf("FROM:<") != -1 && param.indexOf(">") > param.indexOf("FROM:<")) {
				this._reversePath = param.substring(param.indexOf("FROM:<") + 6, param.indexOf(">"));
				this._send(250, "OK");
			} else {
				this._send(501, "invalid parameter");
			}
		} else {
			this._send(503, "bad sequence of commands");
		}
    }
	
	_commandHandler_RCPT(param) {
		if (this._hostname != null && this._reversePath != null) {
			if (param != null && param != "" && param.indexOf("TO:<") != -1 && param.indexOf(">") > param.indexOf("TO:<")) {
				let forwardPath = param.substring(param.indexOf("TO:<") + 4, param.indexOf(">"));
				if (this._isMailAddressedToUs(forwardPath)) {
					// Mail addressed to us
					this._forwardPath.push(forwardPath);
					this._send(250, "OK");
				} else {
					// Mail addressed to other server
					this._send(550, "unknown recipient");
				}
			} else {
				this._send(501, "invalid parameter");
			}
		} else {
			this._send(503, "bad sequence of commands");
		}
    }
	
	_commandHandler_DATA(param) {
		if (this._hostname != null && this._reversePath != null && this._forwardPath.length > 0) {
			// Enable data input mode
			this._dataInputMode = true;
			this._send(354, "start mail input");
		} else {
			this._send(503, "bad sequence of commands");
		}
    }
	
	async _handleData(data) {
		this._mailData += data;
		if (data.endsWith(".\r\n")) {
			this._dataInputMode = false;
			let mailToInsert = [];
			for (let i = 0; i < this._forwardPath.length; i++) {
				if (this._isMailAddressedToUs(this._forwardPath[i])) {
					let [result] = await this._server.mysql.execute("select id from sys_users where dion_email_local = ? limit 1", [this._sliceDomain(this._forwardPath[i])]);
					if (result.length > 0) {
						let mail = [];
						mail[0] = this._reversePath;
						mail[1] = result[0]["id"];
						mail[2] = this._mailData.substring(0, this._mailData.length - 3);
						mailToInsert.push(mail);
					}
				}
			}
			
			if (mailToInsert.length > 0) {
				await this._server.mysql.query("insert into sys_inbox (sender, recipient, message) values ?", [mailToInsert]);
			}
			this._send(250, "OK");
		}
	}
}
module.exports.SMTPConnection = SMTPConnection;
