<?php
// SPDX-License-Identifier: MIT

// Note: $result should have 7 entries. If not, the game will not accept the file.
function encodeBattleTowerRoomData($result, $bxte = false) {
	$output = "";
	for ($i = 0; $i < sizeof($result); $i++) {
		$output .= $result[$i]["name"]; // $00 Trainer name
		$output .= pack("C", $result[$i]["class"]); // $05 Trainer class
		$output .= $result[$i]["pokemon1"]; // $06 1st Pokemon
		$output .= $result[$i]["pokemon2"]; // $3c 2nd Pokemon
		$output .= $result[$i]["pokemon3"]; // $72 3rd Pokemon
		$output .= $result[$i]["message_start"]; // $ae Easy Chat message for when the battle starts
		$output .= $result[$i]["message_win"]; // $ba Easy Chat message for winning a battle
		$output .= $result[$i]["message_lose"]; // $c6 Easy Chat message for losing a battle
	}
	return $output;
}

function encodeLeaderList($result, $bxte = false) {
	$output = "";
	for ($i = 0; $i < 30; $i++) {
		if (array_key_exists($i, $result)) {
			$output .= $result[$i]["hex(name)"];
		} else {
			// empty spot
			for ($j = 0; $j < ($bxte ? 7 : 5); $j++) {
				$output .= "00";
			}
		}
	}
	return hex2bin($output);
}

function decodeBattleTowerRecord($stream, $bxte = false) {
	$postdata = fopen($stream, "rb");
	$decData = array();

	// $00 Room number
	$roomNo = unpack("n", fread($postdata, 0x2))[1];
	$decData["room"] = roomNoToRoom($roomNo); // selected room (0 = room 001, 1 = room 002...)
	$decData["level"] = roomNoToLevel($roomNo); // selected level (0 = L:10, 9 = L:100)

	// $02 DION e-mail address (null-terminated ASCII)
	$decData["email"] = str_replace("\0", "", fread($postdata, 0x1e));

	// $20 Trainer / Secret IDs
	$decData["trainer_id"] = unpack("n", fread($postdata, 0x2))[1]; // $20 Trainer ID
	$decData["secret_id"]  = unpack("n", fread($postdata, 0x2))[1]; // $22 Secret ID

	// $24 Trainer name
	$decData["name"] = fread($postdata, $bxte ? 0x7 : 0x5);

	// $29 Trainer class
	$decData["class"] = unpack("C", fread($postdata, 0x1))[1];

	// Pokémon blocks
	$decData["pokemon1"] = fread($postdata, $bxte ? 0x3b : 0x36); // $2a 1st Pokemon
	$decData["pokemon2"] = fread($postdata, $bxte ? 0x3b : 0x36); // $60 2nd Pokemon
	$decData["pokemon3"] = fread($postdata, $bxte ? 0x3b : 0x36); // $96 3rd Pokemon

	// Messages
	$decData["message_start"] = fread($postdata, $bxte ? 8 : 12); // Easy Chat message for when the battle starts
	$decData["message_win"]   = fread($postdata, $bxte ? 8 : 12); // Easy Chat message for winning a battle
	$decData["message_lose"]  = fread($postdata, $bxte ? 8 : 12); // Easy Chat message for losing a battle

	// Trailer: stats are stored as (0xFFFF - N) / (0xFF - N)
	$raw_ntd     = unpack("C", fread($postdata, 0x1))[1]; // num_trainers_defeated (0..7)
	$raw_turns   = unpack("n", fread($postdata, 0x2))[1]; // encoded turns
	$raw_damage  = unpack("n", fread($postdata, 0x2))[1]; // encoded damage
	$raw_fainted = unpack("C", fread($postdata, 0x1))[1]; // encoded fainted count

	$decData["num_trainers_defeated"] = $raw_ntd;

	if ($raw_turns >= 0x8000) {
		$decData["num_turns_required"] = 0xFFFF - $raw_turns;
	} else {
		$decData["num_turns_required"] = $raw_turns;
	}

	if ($raw_damage >= 0x8000) {
		$decData["damage_taken"] = 0xFFFF - $raw_damage;
	} else {
		$decData["damage_taken"] = $raw_damage;
	}

	if ($raw_fainted >= 0x80) {
		$decData["num_fainted_pokemon"] = 0xFF - $raw_fainted;
	} else {
		$decData["num_fainted_pokemon"] = $raw_fainted;
	}

	return $decData;
}

function roomNoToRoom($roomNo) {
	return ($roomNo - 1) / 10;
}

function roomNoToLevel($roomNo) {
	return ($roomNo - 1) % 10;
}
