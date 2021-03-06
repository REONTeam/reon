<?php
// SPDX-License-Identifier: MIT
function decodeExchange ($stream, $pkm = true) {
    $postdata = fopen($stream, "rb");
    $decData = array();
    $decData["email"] = fread($postdata, 0x18); // $00 DION e-mail address (null-terminated ASCII)
    fseek($postdata, 0x1E); // Jump to Trainer ID
    $decData["trainer_id"] = bin2hex(fread($postdata, 0x2)); // $1E Trainer ID
    $decData["secret_id"] = bin2hex(fread($postdata, 0x2)); // $20 Secret ID
    $decData["offer_gender"] = bin2hex(fread($postdata, 0x1)); // $22 Offered Pokémon’s gender
    $decData["offer_species"] = hexdec(bin2hex(fread($postdata, 0x1))); // $23 Offered Pokémon’s species
    $decData["req_gender"] = bin2hex(fread($postdata, 0x1)); // $24 Requested Pokémon’s gender
    $decData["req_species"] = hexdec(bin2hex(fread($postdata, 0x1))); // $25 Requested Pokémon’s species
    $decData["b64_pokemon"] = $pkm ? base64_encode(fread($postdata, 0x69)) : NULL; // Base64 of the Pokémon that needs to be sent back in email.
    // These bytes (except for b64_pokemon) are all that the web scripts need to deal with.
    return $decData;
}

// Note: $result should have 7 entries. If not, the game will not accept the file.
function encodeBattleTowerRoomData($result, $bxte = false) {
	$output = "";
	for($i = 0; $i < sizeof($result); $i++) {
		$output .= $result[$i]["hex(name)"]; // $00 Trainer name
		$output .= $result[$i]["hex(class)"]; // $05 Trainer class
		$output .= $result[$i]["hex(pokemon1)"]; // $06 1st Pokemon
		$output .= $result[$i]["hex(pokemon2)"]; // $3c 2nd Pokemon
		$output .= $result[$i]["hex(pokemon3)"]; // $72 3rd Pokemon
		$output .= $result[$i]["hex(message_start)"]; // $ae Easy Chat message for when the battle starts
		$output .= $result[$i]["hex(message_win)"]; // $ba Easy Chat message for winning a battle
		$output .= $result[$i]["hex(message_lose)"]; // $c6 Easy Chat message for losing a battle
	}
	return hex2bin($output);
}

function encodeLeaderList($result, $bxte = false) {
	$output = "";
	for($i = 0; $i < 30; $i++) {
		if(array_key_exists($i, $result)) {
			$output .= $result[$i]["hex(name)"];
		} else {
			// empty spot
			for($j = 0; $j < ($bxte ? 7 : 5); $j++) {
				$output .= "00";
			}
		}
	}
	return hex2bin($output);
}

function decodeBattleTowerRecord($stream, $bxte = false) {
	$postdata = fopen($stream, "rb");
	$decData = array();
	$roomNo = unpack("n",fread($postdata, 0x2))["1"]; // $00 Room number
	$decData["room"] = roomNoToRoom($roomNo); // selected room (0 = room 001, 1 = room 002...)
	$decData["level"] = roomNoToLevel($roomNo); // selected level (0 = L:10, 9 = L:100)
	$decData["email"] = str_replace("\0", "", fread($postdata, 0x1e)); // $02 DION e-mail address (null-terminated ASCII)
	$decData["trainer_id"] = unpack("n",fread($postdata, 0x2))["1"]; // $20 Trainer ID
	$decData["secret_id"] = unpack("n",fread($postdata, 0x2))["1"]; // $22 Secret ID
	$decData["name"] = fread($postdata, $bxte ? 0x7 : 0x5); // $24 Trainer name
	$decData["class"] = fread($postdata, 0x1); // $29 Trainer class
	$decData["pokemon1"] = fread($postdata, $bxte ? 0x3b : 0x36); // $2a 1st Pokemon
	$decData["pokemon2"] = fread($postdata, $bxte ? 0x3b : 0x36); // $60 2nd Pokemon
	$decData["pokemon3"] = fread($postdata, $bxte ? 0x3b : 0x36); // $96 3rd Pokemon
	$decData["message_start"] = fread($postdata, 0xc); // $cc Easy Chat message for when the battle starts
	$decData["message_win"] = fread($postdata, 0xc); // $d8 Easy Chat message for winning a battle
	$decData["message_lose"] = fread($postdata, 0xc); // $e4 Easy Chat message for losing a battle
	$decData["num_trainers_defeated"] = unpack("C",fread($postdata, 0x1))["1"]; // $f0 Number of trainers defeated
	$decData["num_turns_required"] = unpack("n",fread($postdata, 0x2))["1"]; // $f1 Number of turns required
	$decData["damage_taken"] = unpack("n",fread($postdata, 0x2))["1"]; // $f3 Total damage taken
	$decData["num_fainted_pokemon"] = unpack("C",fread($postdata, 0x1))["1"]; // $f5 Number of fainted Pokemon
	return $decData;
}

function roomNoToRoom($roomNo) {
	return ($roomNo - 1) / 10;
}

function roomNoToLevel($roomNo) {
	return ($roomNo - 1) % 10;
}