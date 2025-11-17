<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");
	
	function process_trade_request($region, $request_data) {
		$decoded_data = decode_exchange($region, $request_data, true); // This makes a nice array of data.
		$db = connectMySQL(); // Connect to DION Database!

		// Now, begin adding the new trade data...
		if ($region == "j") {
			$stmt = $db->prepare("REPLACE INTO `bxtj_exchange` (account_id, trainer_id, secret_id, email, offer_gender, offer_species, request_gender, request_species, player_name, pokemon, mail) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
			//var_dump($db->error);
			// Bind the parameters. REMEMBER: Pokémon Species are the DECIMAL index, not hex!
			$stmt->bind_param("iiisiiiisss", $_SESSION["userId"], $decoded_data["trainer_id"], $decoded_data["secret_id"], $decoded_data["email"], $decoded_data["offer_gender"], $decoded_data["offer_species"], $decoded_data["req_gender"], $decoded_data["req_species"], $decoded_data["player_name"], $decoded_data["pokemon"], $decoded_data["mail"]);
			$stmt->execute();
		} else {
			$stmt = $db->prepare("REPLACE INTO `bxt_exchange` (account_id, game_region, trainer_id, secret_id, email, offer_gender, offer_species, request_gender, request_species, player_name, pokemon, mail) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->bind_param("isiisiiiisss", $_SESSION["userId"], $region, $decoded_data["trainer_id"], $decoded_data["secret_id"], $decoded_data["email"], $decoded_data["offer_gender"], $decoded_data["offer_species"], $decoded_data["req_gender"], $decoded_data["req_species"], $decoded_data["player_name"], $decoded_data["pokemon"], $decoded_data["mail"]);
			$stmt->execute();
		}
	}
	
	function process_cancel_request($region, $request_data) {
		$data = decode_exchange($region, $request_data, false); // This makes a nice array of data.
		$db = connectMySQL(); // Connect to DION Database!

		$stmt = $db->prepare("DELETE FROM `bxt".($region == "j" ? "j" : "")."_exchange` WHERE account_id = ? and trainer_id = ? and secret_id = ?;"); // Delete the trade from Database.
		$stmt->bind_param("iii", $_SESSION["userId"], $data["trainer_id"], $data["secret_id"]);
		$stmt->execute();
	}
	
	function decode_exchange($region, $stream, $full = true) {
		$postdata = fopen($stream, "rb");
		$decData = array();
		$decData["email"] = str_replace(chr(0), '', fread($postdata, 0x1E)); // $00 DION e-mail address (null-terminated ASCII, 30 characters max.)
		$decData["trainer_id"] = unpack("n", fread($postdata, 0x2))[1]; // $1E Trainer ID
		$decData["secret_id"] = unpack("n", fread($postdata, 0x2))[1]; // $20 Secret ID
		$decData["offer_gender"] = unpack("C", fread($postdata, 0x1))[1]; // $22 Offered Pokémon’s gender
		$decData["offer_species"] = unpack("C", fread($postdata, 0x1))[1]; // $23 Offered Pokémon’s species
		$decData["req_gender"] = unpack("C", fread($postdata, 0x1))[1]; // $24 Requested Pokémon’s gender
		$decData["req_species"] = unpack("C", fread($postdata, 0x1))[1]; // $25 Requested Pokémon’s species
		$decData["player_name"] = $full ? fread($postdata, $region == "j" ? 0x5 : 0x7) : NULL; // $26 Name of trainer who requests the trade
		$decData["pokemon"] = $full ? fread($postdata, $region == "j" ? 0x3F : 0x41) : NULL; // $2B Pokémon data
		$decData["mail"] = $full ? fread($postdata, $region == "j" ? 0x2A : 0x2F) : NULL; // $65 Held mail data
		return $decData;
	}