<?php
	// Ghost download by score
	
	require_once(CORE_PATH."/mario_kart.php");
	
	if (!isset($_GET["course"]) || strlen($_GET["course"]) != 2 || ((int) $_GET["course"]) > 19) {
		http_response_code(404);
		return;
	}
	$course = (int) $_GET["course"];
	
	// Get the parameters and validate them
	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists("ghostscore", $params) || strlen($params["ghostscore"]) != 4) {
		http_response_code(400);
		return;
	}
	$ghostscore = hexdec($params["ghostscore"]);
	
	echo getGhostByScore($course, $ghostscore);
?>