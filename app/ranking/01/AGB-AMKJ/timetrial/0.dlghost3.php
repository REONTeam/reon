<?php
	// Ghost download by ID
	
	require_once(CORE_PATH."/mario_kart.php");
	
	if (!isset($_GET["course"]) || strlen($_GET["course"]) != 2 || ((int) $_GET["course"]) > 19) {
		http_response_code(404);
		return;
	}
	$course = (int) $_GET["course"];
	
	// Get the parameters and validate them
	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists("myid", $params) || strlen($params["myid"]) != 32) {
		http_response_code(400);
		return;
	}
	$myid = hex2bin($params["myid"]);
	
	echo getGhostByPlayerID($course, $myid);
?>