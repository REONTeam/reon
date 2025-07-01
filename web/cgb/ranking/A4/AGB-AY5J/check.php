<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/core.php");
	require_once(CORE_PATH."/database.php");

	if ($_SERVER['REQUEST_METHOD'] === "GET") {
		serveFileOrExecScript("/A4/AGB-AY5J/index.txt", "download");
		return;
	}

	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists("ident", $params) || strlen($params["ident"]) != 128 || !array_key_exists("score", $params) || strlen($params["score"]) != 8) {
		http_response_code(400);
		return;
	}

	$ident = hex2bin($params["ident"]);
	$score = hexdec($params["score"]);

	if ($ident === false || $score === false) {
		http_response_code(400);
		return;
	}

	$db = connectMySQL();
	$stmt = $db->prepare("select id from ay5j_rankings where ident = ? and score = ?");
	$stmt->bind_param("si", $ident, $score);
	$stmt->execute();
	$result = fancy_get_result($stmt);
	if (sizeof($result) == 0) {
		http_response_code(400);
		return;
	}
	$id = $result[0]["id"];

	$stmt = $db->prepare("select score from ay5j_rankings order by score desc limit 1");
	$stmt->execute();
	$result = fancy_get_result($stmt);
	$top = $result[0]["score"];

	$stmt = $db->prepare("select count(*) from ay5j_rankings where score > ? or (score = ? and id <= ?)");
	$stmt->bind_param("iii", $score, $score, $id);
	$stmt->execute();
	$result = fancy_get_result($stmt);
	$rank = $result[0]["count(*)"];
	if ($rank > 99999) {
		$rank = 0;
	}

	echo pack("N", $top);
	echo pack("n", 1);
	echo pack("N", $top);

	echo pack("C", 1);
	echo pack("C", 0);

	echo pack("C", 100 + (int)($rank / 1000));
	echo pack("C", 100 + (int)($rank / 10) % 100);
	echo pack("C", 0);
	echo pack("C", $rank % 10);
?>