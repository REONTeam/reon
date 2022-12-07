const EventEmitter = require("events");
const POP3State = Object.freeze({"AUTHORIZATION":1, "TRANSACTION":2, "UPDATE":3});

class POP3Connection extends EventEmitter {
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
		
		this._send(true, "service ready");
		this._isBusy = false;
	}
	
	_initSession() {
		this._user = null;
		this._state = POP3State.AUTHORIZATION;
		this._maildrop = [];
	}
	
	_send(success, data) {
        if (this._socket && this._socket.writable) {
            this._socket.write((success ? "+OK " : "-ERR ") + data + "\r\n");
        }
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
	
	_onData(data) {
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
						this._onCommand(this._inputBuffer.substring(0, this._inputBuffer.indexOf("\r\n") + 2));
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
		this._send(false, "server error");
	}
	
	_onCommand(command) {
		let commandName = command.indexOf(" ") == -1 ? command.substring(0, command.indexOf("\r\n")) : command.substring(0, command.indexOf(" "));
		this.emit("command", commandName, this._user, this._socket.remoteAddress, this._socket.remotePort);
		if (this._isCommandSupported(commandName)) {
			this["_commandHandler_" + commandName].call(this, command.indexOf(" ") == -1 ? null : command.substring(command.indexOf(" ") + 1, command.indexOf("\r\n")));
		} else {
			this._send(false, "command not recognized");
		}
	}
	
    _isCommandSupported(command) {
        return typeof this["_commandHandler_" + command] === "function";
    }
	
	_commandHandler_USER(param) {
		if (this._state == POP3State.AUTHORIZATION) {
			if (param != null && param != "") {
				this._user = param;
				this._send(true, "user accepted");
			} else {
				this._send(false, "invalid parameter");
			}
		} else {
			this._send(false, "command not allowed");
		}
    }
	
	_commandHandler_PASS(param) {
        if (this._state == POP3State.AUTHORIZATION) {
			if (param != null && param != "") {
				if (this._user != null) {
					this._server.mysql.query("select password from users where email_id = ? limit 1", [this._user], function (error, results, fields) {
						if (error) {
							this._onError(error);
						} else {
							if (results.length > 0) {
								// Check password
								if (param === results[0]["password"]) {
									// Get a list of mail for the client
									this._server.mysql.query("select id, char_length(content) as size from mail where recipient = ?", [this._user], function (error, results, fields) {
										if (error) {
											this._onError(error);
										} else {
											for (let i = 0; i < results.length; i++) {
												this._maildrop[i] = [];
												this._maildrop[i]["id"] = results[i]["id"];
												this._maildrop[i]["size"] = results[i]["size"];
												this._maildrop[i]["deleted"] = false;
											}
										}
									}.bind(this));
									this._state = POP3State.TRANSACTION;
									this._send(true, "pass accepted");
								} else {
									this._send(false, "invalid user or pass");
								}
							} else {
								this._send(false, "invalid user or pass");
							}
						}
					}.bind(this));
				} else {
					this._send(false, "no user set");
				}
			} else {
				this._send(false, "invalid parameter");
			}
		} else {
			this._send(false, "command not allowed");
		}
    }
	
	_commandHandler_QUIT(param) {
		switch (this._state) {
			case POP3State.AUTHORIZATION:
			this._send(true, "bye");
			this.close();
			break;
			
			case POP3State.TRANSACTION:
			this._state = POP3State.UPDATE;
			
			let deleteList = [];
			for (let i = 0; i < this._maildrop.length; i++) {
				if (this._maildrop[i]["deleted"]) deleteList.push(this._maildrop[i]["id"]);
			}
			if (deleteList.length > 0) {
				this._server.mysql.query("delete from mail where id in (?)", [deleteList], function (error, results, fields) {
					if (error) {
						this._onError(error);
					} else {
						this._send(true, "bye");
						this.close();
					}
				}.bind(this));
			} else {
				this._send(true, "bye");
				this.close();
			}
			break;
			
			case POP3State.UPDATE:
			this._send(true, "bye");
			this.close();
			break;
		}
	}
	
	_commandHandler_STAT(param) {
		if (this._state == POP3State.TRANSACTION) {
			this._send(true, this._maildrop.length + " " + (this._maildrop.length == 0 ? "0" : this._maildrop.map(entry => entry["size"]).reduce((a, b) => a + b, 0)));
		} else {
			this._send(false, "command not allowed");
		}
	}
	
	_commandHandler_LIST(param) {
		if (this._state == POP3State.TRANSACTION) {
			if (param != null && param != "" && !isNaN(param)) {
				if (this._maildrop[param - 1]) {
					//this._getMail(this._maildrop[param - 1]["id"], function(data) {
					this._send(true, param + " " + this._maildrop[param - 1]["size"] + "\r\n");
					//});
				} else {
					this._send(false, "no such message");
				}
			} else {
				//this._send(true, this._maildrop.length + " " + (this._maildrop.length == 0 ? "0" : this._maildrop.map(entry => entry["size"]).reduce((a, b) => a + b, 0)));
				this._send(false, "not implemented");
			}
		} else {
			this._send(false, "command not allowed");
		}
	}
	
	_commandHandler_RETR(param) {
		if (this._state == POP3State.TRANSACTION) {
			if (param != null && param != "" && !isNaN(param)) {
				if (this._maildrop[param - 1]) {
					this._getMail(this._maildrop[param - 1]["id"], function(data) {
						this._send(true, "message follows\r\n" + data + "\r\n.");
					});
				} else {
					this._send(false, "no such message");
				}
			} else {
				this._send(false, "invalid parameter");
			}
		} else {
			this._send(false, "command not allowed");
		}
	}
	
	_commandHandler_TOP(param) {
		if (this._state == POP3State.TRANSACTION) {
			if (param != null) {
				let params = param.split(" ");
				if (params.length == 2 && !isNaN(params[0]) && !isNaN(params[1])) {
					let messageId = params[0] - 1;
					let lines = params[1];
					if (this._maildrop[messageId]) {
						this._getMail(this._maildrop[messageId]["id"], function(mailContent) {
							let end = mailContent.indexOf("\r\n\r\n") + 4;
							for (let i = 0; i < lines; i++) {
								if (mailContent.indexOf("\r\n", end) == -1) {
									end = mailContent.length;
								} else {
									end = mailContent.indexOf("\r\n", end) + 2;
								}
							}
							
							this._send(true, "top of message follows\r\n" + mailContent.substring(0, end) + "\r\n.");
						});
					} else {
						this._send(false, "no such message");
					}
				} else {
					this._send(false, "invalid parameter");
				}
			} else {
				this._send(false, "invalid parameter");
			}
		} else {
			this._send(false, "command not allowed");
		}
	}
	
	_commandHandler_DELE(param) {
		if (this._state == POP3State.TRANSACTION) {
			if (param != null && param != "" && !isNaN(param)) {
				if (this._maildrop[param - 1]) {
					this._maildrop[param - 1]["deleted"] = true;
					this._send(true, "message deleted");
				} else {
					this._send(false, "no such message");
				}
			} else {
				this._send(false, "invalid parameter");
			}
		} else {
			this._send(false, "command not allowed");
		}
    }
	
	_commandHandler_RSET(param) {
		if (this._state == POP3State.TRANSACTION) {
			for (let i = 0; i < this._maildrop.length; i++) {
				this._maildrop[i]["deleted"] = false;
			}
			this._send(true, "reset successful");
		} else {
			this._send(false, "command not allowed");
		}
    }
	
	_getMail(id, callback) {
		this._server.mysql.query("select content, concat(substring(dayname(date), 1, 3), ', ', day(date), ' ', substring(monthname(date), 1, 3), ' ', year(date), ' ', time(date), ' +0000')as date from mail where id = ?", [id], function (error, results, fields) {
			if (error) {
				this._onError(error);
			} else {
				// Add date header
				let mailContent = results[0]["content"];
				let endOfHeaders = mailContent.indexOf("\r\n\r\n") + 2;
				mailContent = mailContent.slice(0, endOfHeaders) + "Date: " + results[0]["date"] + "\r\n" + mailContent.slice(endOfHeaders);
				
				callback.call(this, mailContent);
			}
		}.bind(this));
	}
}
module.exports.POP3Connection = POP3Connection;