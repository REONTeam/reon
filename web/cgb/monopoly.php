<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");

	function makeRankingEntry($rank, $result) {
		$output = pack("N", $rank);
		$output = $output.$result["name"];
		while (strlen($output) < 8) {
			$output = $output."\xFF";
		}
		$output = $output.$result["email"];
		while (strlen($output) < 40) {
			$output = $output."\0";
		}

		$year = (int)substr($result["timestamp"], 0, 4);
		$month = (int)substr($result["timestamp"], 5, 2);
		$hour = (int)substr($result["timestamp"], 11, 2);
		if ($hour >= 15) {
			$day = (int)substr($result["timestamp"], 8, 2);
			if ($day == cal_days_in_month(CAL_GREGORIAN, $month, $year)) {
				$month++;
				if ($month == 13) {
					$month = 1;
					$year++;
				}
			}
		}
		$today = ($year % 16) << 4 | $month;

		$output = $output.pack("C", $today);
		$output = $output."\0\0\0";
		$output = $output.pack("N", $result["points"]);
		$output = $output.pack("N", $result["money"]);
		$output = $output.pack("C", $result["gender"]);
		$output = $output.pack("C", $result["age"]);
		$output = $output.pack("C", $result["state"]);
		$output = $output.pack("C", $today);
		return $output;
	}

	function query($params) {
		if (!array_key_exists("myname", $params) || strlen($params["myname"]) != 80) {
			http_response_code(400);
			return;
		}
		if (strlen($params["today"]) != 2) {
			http_response_code(400);
			return;
		}
		$myname = hex2bin($params["myname"]);
		if ($myname === false) {
			http_response_code(400);
			return;
		}
		$today = hex2bin($params["today"]);
		if ($today !== $myname[36]) {
			http_response_code(400);
			return;
		}
		$today = unpack("C", $today)[1];

		$name = substr($myname, 0, 4);
		if (str_contains(substr(rtrim($name, "\0"), 0, -1), "\xFF")) {
			http_response_code(400);
			return;
		}

		$email = rtrim(substr($myname, 4, 32), "\0");
		if (str_contains($email, "\0")) {
			http_response_code(400);
			return;
		}

		$db = connectMySQL();
		if ($today == 0) {
			// this is the worst thing.
			$result = array();
			for ($i = 0; sizeof($result) < 10; $i++) {
				$stmt = $db->prepare("select * from amo_ranking where valid = 1 and game_region = ? order by points, money desc limit 1 offset ?");
				$game_region = getCurrentGameRegion();
				$stmt->bind_param("si", $game_region, $i);
				$stmt->execute();
				$result_i = fancy_get_result($stmt);
				if (sizeof($result_i) == 0) {
					break;
				}
				for ($j = 0; $j < sizeof($result); $j++) {
					if ($result_i[0]["acc_id"] == $result[$j]["acc_id"]
						&& $result_i[0]["name"] === $result[$j]["name"]
						&& $result_i[0]["gender"] == $result[$j]["gender"]
						&& $result_i[0]["age"] == $result[$j]["age"]
						&& $result_i[0]["state"] == $result[$j]["state"]) {
						break;
					}
				}
				if ($j == sizeof($result)) {
					$result[$j] = $result_i[0];
				}
			}
		} else {
			$year = date("Y", time() + 32400);
			$month = date("m", time() + 32400);
			if ($month == 1) {
				$timestamp = ($year-1)."-12-31 15:00:00";
			} else {
				$timestamp = sprintf("%04d-%02d-%02d 15:00:00", $year, $month-1, cal_days_in_month(CAL_GREGORIAN, $month-1, $year));
			}
			if (($today >> 4) == ($year % 16) && ($today & 0xF) == $month) {
				$stmt = $db->prepare("select * from amo_ranking where valid = 1 and timestamp >= ? and game_region = ? order by points, money desc limit 10");
				$stmt->bind_param("s", $timestamp);
			} else {
				if ($month == 1) {
					$year--;
					$month = 12;
				} else {
					$month--;
				}
				if (($today >> 4) != ($year % 16) || ($today & 0xF) != $month) {
					http_response_code(400);
					return;
				}
				if ($month == 1) {
					$timestamp2 = ($year-1)."-12-31 15:00:00";
				} else {
					$timestamp2 = sprintf("%04d-%02d-%02d 15:00:00", $year, $month-1, cal_days_in_month(CAL_GREGORIAN, $month-1, $year));
				}
				$stmt = $db->prepare("select * from amo_ranking where valid = 1 and timestamp >= ? and timestamp < ? and game_region = ? order by points, money desc limit 10");
				$stmt->bind_param("ss", $timestamp2, $timestamp);
			}
			$stmt->execute();
			$result = fancy_get_result($stmt);
		}

		echo pack("n", sizeof($result));
		for ($i = 0; $i < sizeof($result); $i++) {
			echo makeRankingEntry($i + 1, $result[$i]);
		}

		$stmt = $db->prepare("delete ignore from amo_ranking where valid = 0 and name = ? and email = ? and game_region = ?");
		$game_region = getCurrentGameRegion();
		$stmt->bind_param("sss", $name, $email, $game_region);
		$stmt->execute();

		if ($today == 0) {
			$stmt = $db->prepare("select * from amo_ranking where name = ? and email = ? and game_region = ? order by points, money desc limit 1");
			$game_region = getCurrentGameRegion();
		$stmt->bind_param("sss", $name, $email, $game_region);
		} else if (isset($timestamp2)) {
			$stmt = $db->prepare("select * from amo_ranking where name = ? and email = ? and timestamp >= ? and game_region = ? and timestamp < ? and game_region = ?");
			$stmt->bind_param("ssss", $name, $email, $timestamp2, $timestamp);
		} else {
			$stmt = $db->prepare("select * from amo_ranking where name = ? and email = ? and timestamp >= ? and game_region = ?");
			$stmt->bind_param("sss", $name, $email, $timestamp);
		}
		$stmt->execute();
		$result = fancy_get_result($stmt);

		echo pack("n", sizeof($result));
		if (sizeof($result) != 0) {
			if ($today == 0) {
				$stmt = $db->prepare("select count(distinct acc_id, name, gender, age, state) as `rank` from amo_ranking where valid = 1 and (points > ? or (points = ? and (money > ? or (money = ? and id <= ?))))");
				$game_region = getCurrentGameRegion();
				$stmt->bind_param("siiiii", $game_region, $result[0]["points"], $result[0]["points"], $result[0]["money"], $result[0]["money"], $result[0]["id"]);
			} else if (isset($timestamp2)) {
				$stmt = $db->prepare("select count(*) as `rank` from amo_ranking where valid = 1 and timestamp >= ? and timestamp < ? and (points > ? or (points = ? and (money > ? or (money = ? and id <= ?))))");
				$game_region = getCurrentGameRegion();
				$stmt->bind_param("sssiiiii", $game_region, $timestamp2, $timestamp, $result[0]["points"], $result[0]["points"], $result[0]["money"], $result[0]["money"], $result[0]["id"]);
			} else {
				$stmt = $db->prepare("select count(*) as `rank` from amo_ranking where valid = 1 and timestamp >= ? and (points > ? or (points = ? and (money > ? or (money = ? and id <= ?))))");
				$game_region = getCurrentGameRegion();
				$stmt->bind_param("ssiiiii", $game_region, $timestamp, $result[0]["points"], $result[0]["points"], $result[0]["money"], $result[0]["money"], $result[0]["id"]);
			}
			$stmt->execute();
			$rank = fancy_get_result($stmt)[0]["rank"];
			echo makeRankingEntry($rank, $result[0]);
		}
	}
?>
