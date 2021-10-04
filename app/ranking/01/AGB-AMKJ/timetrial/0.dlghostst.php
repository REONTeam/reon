<?php
	// SPDX-License-Identifier: MIT
	// Ghost download by rank in state ranking
	
	require_once(CORE_PATH."/mario_kart.php");
	
	if (!isset($_GET["course"]) || strlen($_GET["course"]) != 2 || ((int) $_GET["course"]) > 19) {
		http_response_code(404);
		return;
	}
	$course = (int) $_GET["course"];
	
	// Get the parameters and validate them
	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists("ghostrank", $params) || strlen($params["ghostrank"]) != 8 || !array_key_exists("state", $params) || strlen($params["state"]) != 2) {
		http_response_code(400);
		return;
	}
	$ghostrank = hexdec($params["ghostrank"]);
	$state = hexdec($params["state"]);
	if ($ghostrank == 0 || $state > 46) {
		http_response_code(400);
		return;
	}
	$ghostrank = $ghostrank - 1;
	
	echo getPlayerIDByStateRank($course, $ghostrank, $state);
?>