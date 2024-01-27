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
	
	if ($data["driver"] > 7) {
		http_response_code(400);
		return;
	}
	
	// Validate sent player ID
	if (!checkPlayerID($data["player_id"], $_SESSION['userId'])) {
		// Player ID claimed by a different user
		http_response_code(400);
		return;
	}
	
	$db = connectMySQL();
	$db->begin_transaction();
	try {
		// Delete existing record
		$stmt = $db->prepare("delete ignore from amkj_ghosts_mobilegp where player_id = ?");
		$stmt->bind_param("s", $data["player_id"]);
		$stmt->execute();
		
		$gp_id = getCurrentMobileGP();

		// Insert new record
		$stmt = $db->prepare("insert into amkj_ghosts_mobilegp (gp_id, player_id, course_no, driver, name, state, unk18, course, time, input_data, full_name, phone_number, postal_code, address) values (?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param("iiisiiiiisssss", $gp_id, $data["player_id"], $data["course_no"], $data["name"], $data["state"], $data["unk18"], $data["course"], $data["driver"], $data["time"], $data["input_data"], $data["full_name"], $data["phone_number"], $data["postal_code"], $data["address"]);
		$stmt->execute();
		
		$db->commit();
	} catch (mysqli_sql_exception $e) {
		$db->rollback();
		http_response_code(500);
		throw $e;
	}
?>