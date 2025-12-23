<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/core.php");
	require_once(CORE_PATH."/database.php");

	$config = getConfig();

	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists("ident", $params) || strlen($params["ident"]) != 92
		|| !array_key_exists("score", $params) || strlen($params["score"]) != 8) {
		http_response_code(400);
		return;
	}

	$ident = rtrim(hex2bin($params["ident"]), "\0");
	if ($ident === false || $ident[8] !== "@" || substr($ident, 9) !== $config["email_domain_dion"]) {
		http_response_code(400);
		return;
	}
	$dion_email_local = substr($ident, 0, 8);
	
	$db = connectMySQL();
	
	$game_region = getCurrentGameRegion();
	if ($game_region === null) {
		http_response_code(500);
		return;
	}
$stmt = $db->prepare("select count(*) from sys_users where dion_email_local = ?");
	$stmt->bind_param("s", $dion_email_local);
	$stmt->execute();
	$result = fancy_get_result($stmt);
	if ($result[0]["count(*)"] == 0) {
		http_response_code(400);
		return;
	}

	$score = hexdec($params["score"]);
	if ($score === false) {
		http_response_code(400);
		return;
	}

	echo pack("n", date("Y", time() + 32400));
	echo pack("C", date("m", time() + 32400));
	echo pack("C", date("d", time() + 32400));

	$stmt = $db->prepare("select id, weight from amg_rankings where ident = ? and game_region = ?");
	$stmt->bind_param("ss", $ident, $game_region);
	$stmt->execute();
	$result = fancy_get_result($stmt);

	if (sizeof($result) == 0) {
		echo pack("n", 0);
		echo pack("N", 0);
		echo pack("N", 0);
		echo pack("n", 0);
	} else {
		$id = $result[0]["id"];
		$weight = $result[0]["weight"];

		$stmt = $db->prepare("select count(*) from amg_rankings where (weight > ? or (weight = ? and id <= ?)) and game_region = ?");
		$stmt->bind_param("iiis", $weight, $weight, $id, $game_region);
		$stmt->execute();
		$result = fancy_get_result($stmt);

		echo pack("n", 1);
		echo pack("N", $result[0]["count(*)"]);
		echo pack("N", $weight);
		echo pack("n", ($score > $weight) ? 1 : 0);
	}
?>
