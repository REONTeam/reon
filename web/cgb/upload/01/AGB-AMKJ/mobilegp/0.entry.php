<?php
	// SPDX-License-Identifier: MIT
	// Ghost upload (Mobile GP)
	
	require_once(CORE_PATH."/mario_kart.php");
	require_once(CORE_PATH."/database.php");
	
	$size = (int) $_SERVER['CONTENT_LENGTH'];
	if ($size != 0x10c0) {
		http_response_code(400);
		return;
	}
	$data = parseGhostUpload(fopen("php://input", "rb"));
	
	if ($data["driver"] > 7 || $data["course"] != 20) {
		http_response_code(400);
		return;
	}
	
	// Validate sent player ID
	if (!checkPlayerID($data["player_id"], $_SESSION['userId'])) {
		// Player ID claimed by a different user
		http_response_code(400);
		return;
	}
	
	$gp_id = getCurrentMobileGP();
	
	$db = connectMySQL();
	
	$game_region = getCurrentGameRegion();
	if ($game_region === null) {
		http_response_code(500);
		return;
	}
$db->begin_transaction();
	try {
		// Delete existing record
		$stmt = $db->prepare("delete ignore from amk_ghosts_mobilegp where gp_id = ? and player_id = ? and game_region = ?");
		$stmt->bind_param("iss", $gp_id, $data["player_id"], $game_region);
		$stmt->execute();

		// Insert new record
		$stmt = $db->prepare("insert into amk_ghosts_mobilegp (game_region, acc_id, gp_id, player_id, name, state, driver, time, input_data, full_name, phone_number, postal_code, address) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("siissiiisssss", $game_region, $_SESSION['userId'], $gp_id, $data["player_id"], $data["name"], $data["state"], $data["driver"], $data["time"], $data["input_data"], $data["full_name"], $data["phone_number"], $data["postal_code"], $data["address"]);
		$stmt->execute();
		
		$db->commit();
	} catch (mysqli_sql_exception $e) {
		$db->rollback();
		http_response_code(500);
		throw $e;
	}
?>
