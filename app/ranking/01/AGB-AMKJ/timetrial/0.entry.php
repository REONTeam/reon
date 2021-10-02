<?php
	// Ghost upload
	
	require_once(CORE_PATH."/mario_kart.php");
	require_once(CORE_PATH."/database.php");
	
	if (!isset($_GET["course"]) || strlen($_GET["course"]) != 2 || ((int) $_GET["course"]) > 19) {
		http_response_code(404);
		return;
	}
	
	$size = (int) $_SERVER['CONTENT_LENGTH'];
	if ($size != 0x10c0) {
		http_response_code(400);
		return;
	}
	$data = parseGhostUpload(fopen("php://input", "rb"));
	
	if ($data["driver"] > 7 || $data["state"] > 46) {
		http_response_code(400);
		return;
	}
	
	$data["course"] = (int) $_GET["course"];
	
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
		$stmt = $db->prepare("delete ignore from amkj_ghosts where player_id = ? and course = ?");
		$stmt->bind_param("si", $data["player_id"], $data["course"]);
		$stmt->execute();
		
		// Insert new record
		$stmt = $db->prepare("insert into amkj_ghosts (player_id, name, state, driver, time, course, input_data) values (?,?,?,?,?,?,?)");
		$stmt->bind_param("ssiiiis", $data["player_id"], $data["name"], $data["state"], $data["driver"], $data["time"], $data["course"], $data["input_data"]);
		$stmt->execute();
		
		$db->commit();
	} catch (mysqli_sql_exception $e) {
		$db->rollback();
		http_response_code(500);
		throw $e;
	}
?>