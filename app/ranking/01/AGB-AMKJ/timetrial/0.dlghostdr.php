<?php
	// SPDX-License-Identifier: MIT
	// Ghost download by rank in driver ranking
	
	require_once(CORE_PATH."/mario_kart.php");
	
	if (!isset($_GET["course"]) || strlen($_GET["course"]) != 2 || ((int) $_GET["course"]) > 19) {
		http_response_code(404);
		return;
	}
	$course = (int) $_GET["course"];
	
	// Get the parameters and validate them
	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists("ghostrank", $params) || strlen($params["ghostrank"]) != 8 || !array_key_exists("driver", $params) || strlen($params["driver"]) != 2) {
		http_response_code(400);
		return;
	}
	$ghostrank = hexdec($params["ghostrank"]);
	$driver = hexdec($params["driver"]);
	if ($ghostrank == 0 || $driver > 7) {
		http_response_code(400);
		return;
	}
	$ghostrank = $ghostrank - 1;
	
	echo getPlayerIDByDriverRank($course, $ghostrank, $driver);
?>