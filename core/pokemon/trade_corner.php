<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");
	require_once(CORE_PATH."/pokemon/func.php");
	
	function process_trade_request($request_data, $game_region) {
		$decoded_data = decodeExchange($request_data, true, $game_region); // This makes a nice array of data.
		$db = connectMySQL(); // Connect to DION Database!

		// First, delete any existing trades for that user.
		//$stmt = $db->prepare("DELETE IGNORE FROM `pkm_trades` WHERE email = ?;");
		//$stmt->bind_param("s",$decoded_data["email"]);
		//$stmt->execute();

		$pkm_file =

		// Now, begin adding the new trade data...
		$stmt = $db->prepare("INSERT INTO `bxt".$game_region."_exchange` (email, trainer_id, secret_id, offer_gender, offer_species, request_gender, request_species, base64_pokemon) VALUES (?,?,?,?,?,?,?,?)");

		// Bind the parameters. REMEMBER: Pokémon Species are the DECIMAL index, not hex!
		$stmt->bind_param("siisisis",$decoded_data["email"],$decoded_data["trainer_id"],$decoded_data["secret_id"],$decoded_data["offer_gender"],$decoded_data["offer_species"],$decoded_data["req_gender"],$decoded_data["req_species"],$decoded_data["base64_pokemon"]);
		$stmt->execute();
	}