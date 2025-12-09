<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/zen_nihon.php");

	$excrs = getExtraCourse();

	if ($excrs) {
		echo "\0";
		$cksum = 0;
	} else {
		echo "\x10";
		$cksum = 0x10;
	}

	$db = connectMySQL();
	$ghosts_avail = $excrs ? 0x7F : 0x3F;
	for ($i = 0; $i < ($excrs ? 7 : 6); $i++) {
		if ($i != 6) {
			$j = $i + 9;
			$stmt = $db->prepare("select count(*) from agtj_ghosts where (course = ? or course = ?) and dl_ok is not null limit 1");
			$stmt->bind_param("ii", $i, $j);
		} else {
			$stmt = $db->prepare("select count(*) from agtj_ghosts where (course = 6 or course = 7 or course = 8 or course = 15 or course = 16 or course = 17) and dl_ok is not null and excrs = ? limit 1");
			$stmt->bind_param("i", $excrs);
		}
		$stmt->execute();
		$result = fancy_get_result($stmt);
		$ghosts_avail &= ~($result[0]["count(*)"] << $i);
	}
	echo pack("C", $ghosts_avail);
	$cksum = $cksum + $ghosts_avail;

	echo pack("C", $excrs);
	$cksum = $cksum + $excrs;
	echo pack("C", $excrs);
	$cksum = $cksum + $excrs;

	$ranking_prefix = "http://gameboy.datacenter.ne.jp/cgb/download?name=/28/AGB-AGTJ/";
	echo $ranking_prefix;
	$i = 0;
	while ($i < strlen($ranking_prefix)) {
		$cksum = $cksum + ord($ranking_prefix[$i]);
		$i++;
	}
	while ($i < 0x40) {
		echo "\0";
		$i++;
	}

	$config = getConfig();
	$ghost_email = $config["agtj_email"];
	echo $ghost_email;
	$i = 0;
	while ($i < strlen($ghost_email)) {
		$cksum = $cksum + ord($ghost_email[$i]);
		$i++;
	}
	while ($i < 0x40) {
		echo "\0";
		$i++;
	}

	for ($i = 0; $i < 0x40; $i++) {
		echo "\0";
	}

	echo pack("V", $cksum);
?>
