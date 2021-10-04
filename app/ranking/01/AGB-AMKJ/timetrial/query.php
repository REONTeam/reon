<?php
	// SPDX-License-Identifier: MIT
	// Rankings download
	
	require_once(CORE_PATH."/mario_kart.php");
	
	if (!isset($_GET["course"]) || strlen($_GET["course"]) != 2 || ((int) $_GET["course"]) > 19) {
		http_response_code(404);
		return;
	}
	$course = (int) $_GET["course"];
	
	// Get the parameters and validate them
	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists("myid", $params) || strlen($params["myid"]) != 32
		|| !array_key_exists("myrecord", $params) || strlen($params["myrecord"]) != 4
		|| !array_key_exists("pickuprecord", $params) || strlen($params["pickuprecord"]) != 4
		|| !array_key_exists("state", $params) || strlen($params["state"]) != 2
		|| !array_key_exists("driver", $params) || strlen($params["driver"]) != 2
		|| !array_key_exists("rk_1", $params) || strlen($params["rk_1"]) != 8
		|| !array_key_exists("rk_2", $params) || strlen($params["rk_2"]) != 8
		|| !array_key_exists("rk_3", $params) || strlen($params["rk_3"]) != 8
		|| !array_key_exists("rk_4", $params) || strlen($params["rk_4"]) != 8
		|| !array_key_exists("rk_5", $params) || strlen($params["rk_5"]) != 8
		|| !array_key_exists("rk_6", $params) || strlen($params["rk_6"]) != 8
		|| !array_key_exists("rk_7", $params) || strlen($params["rk_7"]) != 8
		|| !array_key_exists("rk_8", $params) || strlen($params["rk_8"]) != 8
		|| !array_key_exists("rk_9", $params) || strlen($params["rk_9"]) != 8
		|| !array_key_exists("rk_10", $params) || strlen($params["rk_10"]) != 8
		|| !array_key_exists("rk_11", $params) || strlen($params["rk_11"]) != 8) {
		http_response_code(400);
		return;
	}
	$myid = hex2bin($params["myid"]);
	$myrecord = hexdec($params["myrecord"]);
	$pickuprecord = hexdec($params["pickuprecord"]);
	$state = hexdec($params["state"]);
	$driver = hexdec($params["driver"]);
	$rk = array();
	for ($i = 1; $i < 12; $i++) {
		array_push($rk, hexdec($params["rk_".$i]));
	}
	
	
	echo pack("n", date("Y")); // Year
	echo pack("C", date("m")); // Month
	echo pack("C", date("d")); // Day
	echo pack("C", date("H")); // Hour
	echo pack("C", date("i")); // Minute
	
	echo pack("N", getTotalRankingEntries($course)); // Probably total amount of ranked players
	
	echo pack("N", 50); // ? If lower than 50, the ranking category below rivals uses the top 10 data
	
	// Worldwide top 10 (for some reason the game accepts up to 11 entries but only displays 10)
	$top10 = getTop10($course, $myid);
	echo pack("n", sizeof($top10));
	for ($i = 0; $i < sizeof($top10); $i++) {
		echo $top10[$i];
	}
	
	// Rivals (same as above, up to 11 entries are accepted. If less than 11, this is ignored and the worldwide top 10 are displayed instead)
	$rivals = getRivals($course, $myid, $myrecord);
	echo pack("n", sizeof($rivals));
	for ($i = 0; $i < sizeof($rivals); $i++) {
		echo $rivals[$i];
	}
	
	// Own rank
	$myrank = getOwnRank($course, $myid, $myrecord);
	echo pack("n", $myrank != 0);
	if ($myrank != 0) {
		echo pack("N", $myrank);
	}
	
	// Unknown
	echo pack("n", 0);
	//echo(pack("N",0));
	//echo(pack("C",0));
	//echo(pack("C",0));
	//echo(pack("C",0));

	// That category below rivals
	for ($i = 0; $i < sizeof($rk); $i++) {
		$player = getPlayerAtSpecificRank($course, $myid, $rk[$i]);
		echo pack("n", !empty($player));
		if (!empty($player)) {
			echo $player;
		}
	}

?>