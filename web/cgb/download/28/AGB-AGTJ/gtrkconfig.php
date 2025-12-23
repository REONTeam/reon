<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/zen_nihon.php");

	$excrs = getExtraCourse();

	$db = connectMySQL();
	
	$game_region = getCurrentGameRegion();
	if ($game_region === null) {
		http_response_code(500);
		return;
	}
$rankings_avail = $excrs ? 0x7F : 0x3F;
	for ($i = 0; $i < ($excrs ? 7 : 6); $i++) {
		if ($i != 6) {
			$j = $i + 9;
			$stmt = $db->prepare("select count(*) from agt_ghosts where (course = ? or course = ?) and game_region = ? limit 1");
			$stmt->bind_param("iis", $i, $j, $game_region);
		} else {
			$stmt = $db->prepare("select count(*) from agt_ghosts where (course = 6 or course = 7 or course = 8 or course = 15 or course = 16 or course = 17) and excrs = ? and game_region = ? limit 1");
			$stmt->bind_param("is", $excrs, $game_region);
		}
		$stmt->execute();
		$result = fancy_get_result($stmt);
		$rankings_avail &= ~($result[0]["count(*)"] << $i);
	}
	echo pack("C", $rankings_avail)."\0\0\0";
	echo pack("V", $rankings_avail);
?>
