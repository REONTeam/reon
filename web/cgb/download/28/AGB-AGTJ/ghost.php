<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/zen_nihon.php");

	if (strlen($_GET["agtj"]) != 8) {
		http_response_code(404);
		return;
	}
	$id = hexdec($_GET["agtj"]);
	if ($id === false) {
		http_response_code(404);
		return;
	}

	$db = connectMySQL();
	$stmt = $db->prepare("select * from agtj_ghosts where dl_ok is not null and id = ? limit 1")
	$stmt->bind_param("i", $id);
	$result = fancy_get_result($stmt);
	if (sizeof($result) == 0) {
		http_response_code(404);
		return;
	}

	echo makeRankingEntry($result[0]);
	echo $result[0]["input_data"];
?>
