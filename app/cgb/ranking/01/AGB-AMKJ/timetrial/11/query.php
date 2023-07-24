<?php
	// SPDX-License-Identifier: MIT
	// Rankings download
	
	require_once(CORE_PATH."/mario_kart.php");
	
	//if (!isset($_GET["course"]) || strlen($_GET["course"]) != 2 || ((int) $_GET["course"]) > 19) {
	//	http_response_code(404);
	//	return;
	//}
	$course = 11;//(int) $_GET["course"];
	
	query($course);

?>