<?php
	// SPDX-License-Identifier: MIT
	// Ghost download by rank (worldwide)
	
	require_once(CORE_PATH."/mario_kart.php");
	
	if (!isset($_GET["course"]) || strlen($_GET["course"]) != 2 || ((int) $_GET["course"]) > 19) {
		http_response_code(404);
		return;
	}
	$course = (int) $_GET["course"];
	
	// Get the parameters and validate them
	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists("ghostrank", $params) || strlen($params["ghostrank"]) != 8) {
		http_response_code(400);
		return;
	}
	$ghostrank = hexdec($params["ghostrank"]);
	if ($ghostrank == 0) {
		http_response_code(400);
		return;
	}
	$ghostrank = $ghostrank - 1;
	
	echo getGhostByRank($course, $ghostrank);
?>