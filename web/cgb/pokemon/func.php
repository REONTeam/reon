<?php
// SPDX-License-Identifier: MIT

// Note: $result should have 7 entries. If not, the game will not accept the file.
function encodeBattleTowerRoomData($result, $bxte = false) {
	// Encode a 7-trainer Battle Tower room in the on-wire format
	// expected by the mobile client. The layout differs for Japanese
	// vs. non-Japanese regions:
	//
	//  JP ('j'):
	//    name:          5 bytes
	//    class:         1 byte
	//    pokemon1..3:   3 × 54 bytes
	//    messages:      3 × 12 bytes
	//    struct size:   204 bytes
	//
	//  non-JP ('e','f','d','s','i',...):
	//    name:          7 bytes
	//    class:         1 byte
	//    pokemon1..3:   3 × 59 bytes
	//    messages:      3 ×  8 bytes (EASY_CHAT_MESSAGE_LENGTH)
	//    struct size:   209 bytes
	//
	// Our unified DB stores larger superset blobs (typically 7-byte
	// names, 65-byte pokemon*, and 12-byte messages). Here we trim
	// or pad each field to the exact protocol lengths.
	$output = "";
	$isJP   = !$bxte;

	$nameLen = $isJP ? 5 : 10;
	$monLen  = $isJP ? 54 : 59;
	$msgLen  = $isJP ? 12 : 8;

	for ($i = 0; $i < sizeof($result); $i++) {
		$row = $result[$i];

		// Trainer name: prefer 'name', fall back to 'player_name'.
		$name = "";
		if (isset($row["name"])) {
			$name = $row["name"];
		} elseif (isset($row["player_name"])) {
			$name = $row["player_name"]; // legacy alias
		}
		if (!is_string($name)) {
			$name = "";
		}
		$name = substr($name, 0, $nameLen);
		if (strlen($name) < $nameLen) {
			$name = str_pad($name, $nameLen, "\0", STR_PAD_RIGHT);
		}

		// Trainer class: single byte.
		$class = isset($row["class"]) ? (int)$row["class"] : 0;

		// Helper to trim/pad a blob to a fixed length.
		$fixBlob = function ($value, $len) {
			if (!is_string($value)) {
				$value = "";
			}
			$value = substr($value, 0, $len);
			if (strlen($value) < $len) {
				$value = str_pad($value, $len, "\0", STR_PAD_RIGHT);
			}
			return $value;
		};

		$p1 = $fixBlob(isset($row["pokemon1"]) ? $row["pokemon1"] : null, $monLen);
		$p2 = $fixBlob(isset($row["pokemon2"]) ? $row["pokemon2"] : null, $monLen);
		$p3 = $fixBlob(isset($row["pokemon3"]) ? $row["pokemon3"] : null, $monLen);

		$m_start = $fixBlob(isset($row["message_start"]) ? $row["message_start"] : null, $msgLen);
		$m_win   = $fixBlob(isset($row["message_win"])   ? $row["message_win"]   : null, $msgLen);
		$m_lose  = $fixBlob(isset($row["message_lose"])  ? $row["message_lose"]  : null, $msgLen);

		$output .= $name;                // Trainer name
		$output .= pack("C", $class);   // Trainer class
		$output .= $p1;                  // 1st Pokémon blob
		$output .= $p2;                  // 2nd Pokémon blob
		$output .= $p3;                  // 3rd Pokémon blob
		$output .= $m_start;             // Start message
		$output .= $m_win;               // Win message
		$output .= $m_lose;              // Lose message
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
