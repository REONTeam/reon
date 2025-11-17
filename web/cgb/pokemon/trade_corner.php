<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");
	
	function process_trade_request($region, $request_data) {
		$decoded_data = decode_exchange($region, $request_data, true); // Decode request payload
		$db = connectMySQL(); // Connect to DION Database

		// All regions now write into the unified `bxt_exchange` table.
		// Region differences are tracked via the `game_region` column.
		$stmt = $db->prepare(
			"REPLACE INTO `bxt_exchange` (email, account_id, game_region, trainer_id, secret_id, "
			. "offer_gender, offer_species, request_gender, request_species, "
			. "player_name, pokemon, mail) "
			. "VALUES (?,?,?,?,?,?,?,?,?,?,?,?)"
		);

		// Bind the parameters. Pokémon species are the DECIMAL index, not hex.
		$stmt->bind_param(
			"sisiiiiiisss",
			$decoded_data["email"],        // email (ASCII, up to 30 chars)
			$_SESSION["userId"],           // account_id
			$region,                       // game_region (single-letter region code)
			$decoded_data["trainer_id"],   // trainer_id
			$decoded_data["secret_id"],    // secret_id
			$decoded_data["offer_gender"], // offer_gender
			$decoded_data["offer_species"],// offer_species
			$decoded_data["req_gender"],   // request_gender
			$decoded_data["req_species"],  // request_species
			$decoded_data["player_name"],  // player_name (raw encoded bytes)
			$decoded_data["pokemon"],      // pokemon blob
			$decoded_data["mail"]          // mail blob
		);

		$stmt->execute();
	}
	
	function process_cancel_request($region, $request_data) {
		$data = decode_exchange($region, $request_data, false); // Decode without blobs
		$db = connectMySQL(); // Connect to DION Database

		// Cancel from unified table, scoped by account + region + IDs.
		$stmt = $db->prepare(
			"DELETE FROM `bxt_exchange` "
			. "WHERE account_id = ? AND game_region = ? AND trainer_id = ? AND secret_id = ? "
			. "LIMIT 1"
		);
		$stmt->bind_param(
			"isii",
			$_SESSION["userId"],
			$region,
			$data["trainer_id"],
			$data["secret_id"]
		);
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