<?php
	// SPDX-License-Identifier: MIT
	// Probably total amount of entries in the ranking (probably)
	// Seems to influence the rk parameters sent to query.cgb
	
	require_once(CORE_PATH."/mario_kart.php");
	
	//if (!isset($_GET["course"]) || strlen($_GET["course"]) != 2 || ((int) $_GET["course"]) > 19) {
	//	http_response_code(404);
	//	return;
	//}
	$course = 6;//(int) $_GET["course"];
	
	echo pack("N", getTotalRankingEntries($course));
?>