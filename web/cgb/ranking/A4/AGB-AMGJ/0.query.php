<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");

	$db = connectMySQL();
	
	$game_region = getCurrentGameRegion();
	if ($game_region === null) {
		http_response_code(500);
		return;
	}
$stmt = $db->prepare("select count(*) from amg_rankings");
	$stmt->execute();
	$result = fancy_get_result($stmt);
	$count = $result[0]["count(*)"];

	$stmt = $db->prepare("select * from amg_rankings order by weight desc limit 10");
	$stmt->execute();
	$top10 = fancy_get_result($stmt);

	if ($count <= 111) {
		$stmt = $db->prepare("select * from amg_rankings order by weight asc limit 101");
		$stmt->execute();
		$overall = fancy_get_result($stmt);
	} else {
		$overall = array();
		$not10 = $count - 10;
		for ($i = 0; $i < 101; $i++) {
			$offset = $i * $not10 / 101;
			$stmt = $db->prepare("select * from amg_rankings order by weight asc limit 1 offset ?");
			$stmt->bind_param("i", $offset);
			$stmt->execute();
			$result = fancy_get_result($stmt);
			$overall[$offset] = $result[0];
		}
	}

	echo pack("n", date("Y", time() + 32400));
	echo pack("C", date("m", time() + 32400));
	echo pack("C", date("d", time() + 32400));
	echo pack("C", date("H", time() + 32400));
	echo pack("C", date("i"));

	echo pack("n", sizeof($top10));
	for ($i = 0; $i < sizeof($top10); $i++) {
		echo pack("N", $i + 1);
		echo $top10[$i]["name"];
		echo pack("C", $top10[$i]["blood"]);
		echo pack("C", $top10[$i]["gender"]);
		echo pack("C", $top10[$i]["age"]);
		echo pack("N", $top10[$i]["weight"]);
	}

	echo pack("n", sizeof($overall));
	$offsets = array_reverse(array_keys($overall));
	foreach ($offsets as $i) {
		echo pack("N", $count - $i);
		echo $overall[$i]["name"];
		echo pack("C", $overall[$i]["blood"]);
		echo pack("C", $overall[$i]["gender"]);
		echo pack("C", $overall[$i]["age"]);
		echo pack("N", $overall[$i]["weight"]);
	}
?>
