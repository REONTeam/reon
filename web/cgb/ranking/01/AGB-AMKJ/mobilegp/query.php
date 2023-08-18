<?php
	// SPDX-License-Identifier: MIT
	// Rankings download (Mobile GP)
	
	require_once(CORE_PATH."/mario_kart.php");
	
	// Get the parameters and validate them
	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists("myid", $params) || strlen($params["myid"]) != 32
		|| !array_key_exists("state", $params) || strlen($params["state"]) != 2
		|| !array_key_exists("driver", $params) || strlen($params["driver"]) != 2) {
		http_response_code(400);
		return;
	}
	$myid = hex2bin($params["myid"]);
	if (!checkPlayerID($myid, null)) {
		http_response_code(400);
		return;
	}
	$state = hexdec($params["state"]);
	$driver = hexdec($params["driver"]);

	$gp_id = getCurrentMobileGP();
	
	echo pack("n", date("Y", time() + 32400)); // Year
	echo pack("C", date("m", time() + 32400)); // Month
	echo pack("C", date("d", time() + 32400)); // Day
	echo pack("C", date("H", time() + 32400)); // Hour
	echo pack("C", date("i")); // Minute
	
	echo pack("N", getTotalRankingEntriesMobileGP($gp_id));
	
	echo pack("N", getTotalRankingEntriesMobileGP($gp_id));
	
	$top10 = getTop10MobileGP($gp_id);
	echo pack("n", sizeof($top10));
	for ($i = 0; $i < sizeof($top10); $i++) {
		echo $top10[$i];
	}
	
	echo pack("n", 0);
	
	$myrank = getOwnRankMobileGP($gp_id, $myid);
	if ($myrank["rank"] != 0) {
		echo pack("n", 1);
		echo pack("N", $myrank["rank"]);

		echo pack("n", 1);
		echo pack("N", $myrank["rank"]);
		echo pack("C", $myrank["driver"]);
		echo pack("n", $myrank["time"]);
	} else {
		echo pack("n", 0);
		echo pack("n", 0);
	}
	
	echo pack("n", 0); // rival count
	
	for ($i = 0; $i < 11; $i++) {
		echo pack("n", 0);
	}

?>