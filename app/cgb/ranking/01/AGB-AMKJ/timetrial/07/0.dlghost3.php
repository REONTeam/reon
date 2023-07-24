<?php
	// SPDX-License-Identifier: MIT
	// Ghost download by ID
	
	require_once(CORE_PATH."/mario_kart.php");
	
	//if (!isset($_GET["course"]) || strlen($_GET["course"]) != 2 || ((int) $_GET["course"]) > 19) {
	//	http_response_code(404);
	//	return;
	//}
	$course = 7;//(int) $_GET["course"];
	
	echo getGhostByPlayerID($course);
?>