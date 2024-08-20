<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/zen_nihon.php");

	echo "\0";
	$cksum = 0;

	$excrs = getExtraCourse();

	$db = connectMySQL();
	$ghosts_avail = 0x7F;
	for ($i = 0; $i < 7; $i++) {
		if ($i != 6) {
			$stmt = $db->prepare("select count(*) from agtj_ghosts where (course = ? or course = ?) and dl_ok is not null limit 1");
			$stmt->bind_param("ii", $i, $i + 9);
		} else {
			$stmt = $db->prepare("select count(*) from agtj_ghosts where (course = 6 or course = 7 or course = 8 or course = 15 or course = 16 or course = 17) and dl_ok is not null and excrs = ? limit 1");
			$stmt->bind_param("i", $excrs);
		}
		$stmt->execute();
		$result = fancy_get_result($stmt);
		$ghosts_avail &= ~($result["count(*)"] << $i);
	}
	echo pack("C", $ghosts_avail);
	$cksum = $cksum + $ghosts_avail;

	echo pack("C", $excrs);
	$cksum = $cksum + $excrs;
	echo pack("C", $excrs);
	$cksum = $cksum + $excrs;

	$ranking_prefix = "http://gameboy.datacenter.ne.jp/cgb/download?name=/01/AGB-AGTJ/kemco/";
	echo $ranking_prefix;
	$i = 0;
	while ($i < strlen($ranking_prefix)) {
		$cksum = $cksum + ord($ranking_prefix[$i]);
		$i++;
	}
	while ($i < 0xC0) {
		echo "\0";
		$i++;
	}

	echo pack("V", $cksum);
?>
