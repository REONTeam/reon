<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/monopoly.php");

	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists("myscore", $params) || strlen($params["myscore"]) != 16) {
		//http_response_code(400); // the game explicitly says that bad data will be accepted, but not saved
		return;
	}

	$myscore = hex2bin($params["myscore"]);
	if ($myscore === false) {
		//http_response_code(400);
		return;
	}

	$points = unpack("N", substr($myscore, 0, 4))[1];
	$money = unpack("N", substr($myscore, 4))[1];

	$db = connectMySQL();
	
	$game_region = getCurrentGameRegion();
	if ($game_region === null) {
		http_response_code(500);
		return;
	}

	$config = getConfig();
	$valid = !empty($config["amoj_regist"]);
	$stmt = $db->prepare("select id from amo_ranking where valid = ? and acc_id = ? and points = ? and money = ? and game_region = ? order by id desc limit 1");
	$stmt->bind_param("iiiis", $valid, $_SESSION['userId'], $points, $money, $game_region);
	$stmt->execute();
	$result = fancy_get_result($stmt);
	if (sizeof($result) == 0) {
		//http_response_code(400);
		return;
	}

	if (!$valid) {
		$stmt = $db->prepare("update amo_ranking set valid = 1 where id = ?");
		$stmt->bind_param("i", $result[0]["id"]);
		$stmt->execute();
	}

	$timestamp = date_create_immutable_from_format("j,u", "1,0", timezone_open("+0900"))
		->setTimezone(timezone_open(date_default_timezone_get()))->format("Y-m-d H:i:s");

	$stmt = $db->prepare("select count(*) from amo_ranking where valid = 1 and timestamp >= ? and (points > ? or (points = ? and (money > ? or (money = ? and id <= ?)))) group by acc_id, name, gender, age, state, game_region");
	$stmt->bind_param("siiiii", $timestamp, $points, $points, $money, $money, $result[0]["id"]);
	$stmt->execute();
	$result = fancy_get_result($stmt);

	echo pack("n", 1);
	echo pack("N", $result[0]["count(*)"]);
?>
