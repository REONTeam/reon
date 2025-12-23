<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/monopoly.php");

	$config = getConfig();

	$input = fopen("php://input", "rb");
	$name = fread($input, 4);
	$email = rtrim(fread($input, 32), "\0");
	$today = unpack("C", fread($input, 1))[1];
	fseek($input, 40);
	$points = unpack("N", fread($input, 4))[1];
	$money = unpack("N", fread($input, 4))[1];
	$gender = unpack("C", fread($input, 1))[1];
	$age = unpack("C", fread($input, 1))[1];
	$state = unpack("C", fread($input, 1))[1];
	$today2 = unpack("C", fread($input, 1))[1];
	fclose($input);

	if ($today != $today2) {
		//http_response_code(400); // the game explicitly says that bad data will be accepted, but not saved
		return;
	}
	if (ord($name) == 0xFF) {
		//http_response_code(400);
		return;
	}
	if (str_contains(substr(rtrim($name, "\0"), 0, -1), "\xFF")) {
		//http_response_code(400);
		return;
	}
	if (substr($email, 8) !== "@".$config["email_domain_dion"]) {
		//http_response_code(400);
		return;
	}
	if ($points % 5 != 0 || $points > 60 || $gender > 1 || $age > 99 || $state > 46) {
		//http_response_code(400);
		return;
	}

	$db = connectMySQL();
	
	$game_region = getCurrentGameRegion();
	if ($game_region === null) {
		http_response_code(500);
		return;
	}
$stmt = $db->prepare("select dion_email_local from sys_users where id = ?");
	$stmt->bind_param("i", $_SESSION['userId']);
	$stmt->execute();
	$result = fancy_get_result($stmt);

	if (sizeof($result) == 0 || $result[0]["dion_email_local"] !== substr($email, 0, 8)) {
		//http_response_code(400);
		return;
	}

	$year = date("Y", time() + 32400);
	$month = date("m", time() + 32400);

	if ($today == 0) {
		if ($month == 1) {
			$timestamp = ($year-1)."-12-31 15:00:00";
		else {
			$timestamp = sprintf("%04d-%02d-%02d 15:00:00", $year, $month-1, cal_days_in_month(CAL_GREGORIAN, $month-1, $year));
		}
		$db->begin_transaction();
		try {
			$stmt = $db->prepare("delete ignore from amo_ranking where (valid = 0 or timestamp >= ?) and acc_id = ? and name = ? and gender = ? and age = ? and state = ? and game_region = ?");
			$stmt->bind_param("sisiiis", $timestamp, $_SESSION['userId'], $name, $gender, $age, $state, $game_region);
			$stmt->execute();

			$stmt = $db->prepare("insert into amo_ranking (game_region, acc_id, name, email, points, money, gender, age, state) values (?,?,?,?,?,?,?,?,?)");
			$stmt->bind_param("sissiiiii", $game_region, $_SESSION['userId'], $name, $email, $points, $money, $gender, $age, $state);
			$stmt->execute();
		} catch (mysqli_sql_exception $e) {
			$db->rollback();
			http_response_code(500);
			throw $e;
		}
		$db->commit();
	} else {
		if (($today >> 4) != ($year % 16) || ($today & 0xF) != $month) {
			$stmt = $db->prepare("delete ignore from amo_ranking where valid = 0 and acc_id = ? and name = ? and points = ? and money = ? and gender = ? and age = ? and state = ? and game_region = ?");
			$stmt->bind_param("isiiiiis", $_SESSION['userId'], $name, $points, $money, $gender, $age, $state, $game_region);
			$stmt->execute();
			//http_response_code(400);
			return;
		}
		if (empty($config["amoj_regist"])) {
			return;
		}
		$stmt = $db->prepare("update amo_ranking set valid = 1 where valid = 0 and acc_id = ? and name = ? and points = ? and money = ? and gender = ? and age = ? and state = ?");
		$stmt->bind_param("isiiiiis", $_SESSION['userId'], $name, $points, $money, $gender, $age, $state, $game_region);
		$stmt->execute();
		if ($config["amoj_regist"][0] === "h") {
			http_response_code(intval(substr($config["amoj_regist"], 1)));
		} else if ($config["amoj_regist"][0] === "g") {
			header("Gb-Status: ".substr($config["amoj_regist"], 1));
		}
	}

	if ($config["amoj_regist"] === "e") {
		echo "\0";
	}
?>
