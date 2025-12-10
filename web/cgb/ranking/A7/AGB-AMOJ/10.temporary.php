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
	$stmt = $db->prepare("select id from amoj_ranking where acc_id = ? and points = ? and money = ? order by id desc limit 1");
	$stmt->bind_param("iii", $_SESSION['userId'], $points, $money);
	$stmt->execute();
	$result = fancy_get_result($stmt);
	if (sizeof($result) == 0) {
		//http_response_code(400);
		return;
	}

	$stmt = $db->prepare("update amoj_ranking set valid = 1 where id = ?");
	$stmt->bind_param("i", $result[0]["id"]);
	$stmt->execute();

	$year = date("Y", time() + 32400);
	$month = date("m", time() + 32400);
	if ($month == 1) {
		$timestamp = ($year-1)."-12-31 15:00:00";
	} else {
		$timestamp = sprintf("%04d-%02d-%02d 15:00:00", $year, $month-1, cal_days_in_month(CAL_GREGORIAN, $month-1, $year));
	}

	$stmt = $db->prepare("select count(*) from amoj_ranking where valid = 1 and timestamp >= ? and (points > ? or (points = ? and (money > ? or (money = ? and id <= ?)))) group by acc_id, name, gender, age, state");
	$stmt->bind_param("siiiii", $timestamp, $points, $points, $money, $money, $result[0]["id"]);
	$stmt->execute();
	$result = fancy_get_result($stmt);

	echo pack("n", 1);
	echo pack("N", $result[0]["count(*)"]);
?>
