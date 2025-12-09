<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");

	function validatePlayerID($month, $day, $hour, $minute, $email_id, $email_svr, $name0) {
		if ($month == 0 || $month > 12) return false;
		if ($day == 0) return false;
		if ($day == 31 && ($month == 2 || $month == 4 || $month == 6 || $month == 9 || $month == 11)) return false;
		if ($day == 30 && $month == 2) return false;
		if ($hour > 23) return false;
		if ($minute > 59) return false;

		$email_chars = "0123456789abcdefghijklmnopqrstuvwxyz";
		if (trim($email_id, $email_chars) !== "") return false;
		if (!str_contains($email_chars, substr($email_svr, 0, 1))) return false;
		if (!str_contains($email_chars.".", substr($email_svr, 1))) return false;

		if (ord($name0) == 0) return false;
		if (str_contains(rtrim($name0, "\x00"), "\x00")) return false;

		return true;
	}

	function decodePlayerID($myid) {
		$data16 = array(0, 0, 0, 0, 0, 0, 0, 0);
		for ($i = 0; $i < 16; $i++) {
			$cur = ord(substr($myid, $i));
			for ($j = 0; $cur != 0; $j++) {
				$data16[$j] = $data16[$j] + (($cur & 1) << (15 - $i));
				$cur = $cur >> 1;
			}
		}

		$month = $data16[0] >> 12;
		$day = ($data16[0] >> 7) & 0x1F;
		$hour = ($data16[0] >> 2) & 0x1F;
		$minute = ($data16[0] & 3) << 4 | ($data16[1] >> 12);

		$email_id = chr(($data16[1] >> 5) & 0x7F);
		$email_id = $email_id.chr(($data16[1] & 0x1F) << 2 | ($data16[2] >> 14));
		$email_id = $email_id.chr(($data16[2] >> 7) & 0x7F);
		$email_id = $email_id.chr($data16[2] & 0x7F);
		$email_id = $email_id.chr($data16[3] >> 9);
		$email_id = $email_id.chr(($data16[3] >> 2) & 0x7F);
		$email_id = $email_id.chr(($data16[3] & 3) << 5 | ($data16[4] >> 11));
		$email_id = $email_id.chr(($data16[4] >> 4) & 0x7F);

		$email_svr = chr(($data16[4] & 0xF) << 3 | ($data16[5] >> 13));
		$email_svr = $email_svr.chr(($data16[5] >> 6) & 0x7F);

		$name = chr(($data16[5] & 0x3F) << 2 | ($data16[6] >> 14));
		$name = $name.chr(($data16[6] >> 6) & 0xFF);
		$name = $name.chr(($data16[6] & 0x3F) << 2 | ($data16[7] >> 14));
		$name = $name.chr(($data16[7] >> 6) & 0xFF);
		$last = ($data16[7] & 0x3F) << 2;

		$name0 = $name.chr($last);
		$name1 = $name.chr($last | 1);
		$name2 = $name.chr($last | 2);
		$name3 = $name.chr($last | 3);

		return array(
			"month" => $month,
			"day" => $day,
			"hour" => $hour,
			"minute" => $minute,
			"email_id" => $email_id,
			"email_svr" => $email_svr,
			"name" => array($name0, $name1, $name2, $name3),
			"valid" => validatePlayerID($month, $day, $hour, $minute, $email_id, $email_svr, $name0)
		);
	}

	function checkPlayerID($myid, $user_id) {
		$config = getConfig();

		$decoded = decodePlayerID($myid);
		if (!$decoded["valid"] || $decoded["email_svr"] !== substr($config["email_domain_dion"], 2, 2)) {
			return false;
		}

		$db = connectMySQL();
		$stmt = $db->prepare("select dion_email_local from sys_users where id = ?");
		$stmt->bind_param("i", $user_id);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0) {
			return false;
		}
		
		if ($result[0]["dion_email_local"] !== $decoded["email_id"]) {
			return false;
		}
		
		return true;
	}

	function getCurrentMobileGP() {
		$db = connectMySQL();
		$stmt = $db->prepare("select id from amkj_rule order by id desc limit 1");
		$stmt->execute();
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0) {
			return -1;
		}
		return $result[0]["id"];
	}

	function getTotalRankingEntries($course) { // total.cgb
		$db = connectMySQL();
		$stmt = $db->prepare("select count(*) from amkj_ghosts where course = ?");
		$stmt->bind_param("i", $course);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		return $result[0]["count(*)"];
	}

	function getTotalRankingEntriesState($course, $state) {
		$db = connectMySQL();
		$stmt = $db->prepare("select count(*) from amkj_ghosts where course = ? and state = ?");
		$stmt->bind_param("ii", $course, $state);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		return $result[0]["count(*)"];
	}

	function getTotalRankingEntriesDriver($course, $driver) {
		$db = connectMySQL();
		$stmt = $db->prepare("select count(*) from amkj_ghosts where course = ? and driver = ?");
		$stmt->bind_param("ii", $course, $driver);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		return $result[0]["count(*)"];
	}

	function getTotalRankingEntriesMobileGP($gp_id) { // total.cgb
		$db = connectMySQL();
		$stmt = $db->prepare("select count(*) from amkj_ghosts where gp_id = ?");
		$stmt->bind_param("i", $gp_id);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		return $result[0]["count(*)"];
	}

	function getOwnRank($course, $myid) {
		$db = connectMySQL();

		$stmt = $db->prepare("select id, time, driver from amkj_ghosts where course = ? and player_id = ?");
		$stmt->bind_param("is", $course, $myid);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0) {
			return array(
				"rank" => 0
			);
		}

		$id = $result[0]["id"];
		$time = $result[0]["time"];
		$driver = $result[0]["driver"];
		
		$stmt = $db->prepare("select count(*) from amkj_ghosts where course = ? and (time < ? or (time = ? and id <= ?))");
		$stmt->bind_param("iiii", $course, $time, $time, $id);
		$stmt->execute();
		$result = fancy_get_result($stmt);

		return array(
			"rank" => $result[0]["count(*)"],
			"driver" => $driver,
			"time" => $time
		);
	}

	function getOwnRankState($course, $myid, $state) {
		$db = connectMySQL();

		$stmt = $db->prepare("select id, time, driver, state from amkj_ghosts where course = ? and player_id = ?");
		$stmt->bind_param("is", $course, $myid);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0 || $result[0]["state"] != $state) {
			return array(
				"rank" => 0
			);
		}

		$id = $result[0]["id"];
		$time = $result[0]["time"];
		$driver = $result[0]["driver"];
		
		$stmt = $db->prepare("select count(*) from amkj_ghosts where course = ? and state = ? and (time < ? or (time = ? and id <= ?))");
		$stmt->bind_param("iiiii", $course, $state, $time, $time, $id);
		$stmt->execute();
		$result = fancy_get_result($stmt);

		return array(
			"rank" => $result[0]["count(*)"],
			"driver" => $driver,
			"time" => $time
		);
	}

	function getOwnRankDriver($course, $myid, $driver) {
		$db = connectMySQL();

		$stmt = $db->prepare("select id, time, driver from amkj_ghosts where course = ? and player_id = ?");
		$stmt->bind_param("is", $course, $myid);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0 || $result[0]["driver"] != $driver) {
			return array(
				"rank" => 0
			);
		}

		$id = $result[0]["id"];
		$time = $result[0]["time"];
		
		$stmt = $db->prepare("select count(*) from amkj_ghosts where course = ? and driver = ? and (time < ? or (time = ? and id <= ?))");
		$stmt->bind_param("iiiii", $course, $driver, $time, $time, $id);
		$stmt->execute();
		$result = fancy_get_result($stmt);

		return array(
			"rank" => $result[0]["count(*)"],
			"driver" => $driver,
			"time" => $time
		);
	}

	function getOwnRankMobileGP($gp_id, $myid) {
		$db = connectMySQL();

		$stmt = $db->prepare("select id, time, driver from amkj_ghosts_mobilegp where gp_id = ? and player_id = ?");
		$stmt->bind_param("is", $gp_id, $myid);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0) {
			return array(
				"rank" => 0
			);
		}

		$id = $result[0]["id"];
		$time = $result[0]["time"];
		$driver = $result[0]["driver"];
		
		$stmt = $db->prepare("select count(*) from amkj_ghosts_mobilegp where gp_id = ? and (time < ? or (time = ? and id <= ?))");
		$stmt->bind_param("iiiii", $gp_id, $time, $time, $id);
		$stmt->execute();
		$result = fancy_get_result($stmt);

		return array(
			"rank" => $result[0]["count(*)"],
			"driver" => $driver,
			"time" => $time
		);
	}

	function makeGhostDownload($result, $course) {
		$output = pack("n", date("Y", time() + 32400)) // Year
		         .pack("C", date("m", time() + 32400)) // Month
		         .pack("C", date("d", time() + 32400)) // Day
		         .pack("C", date("H", time() + 32400)) // Hour
		         .pack("C", date("i")); // Minute

		$total = getTotalRankingEntries($course);
		
		$output = $output.pack("N", $total); // Seems to indicate if something was found
		$output = $output.pack("n", sizeof($result)); // Seems to indicate if ghost data is present
		
		if (sizeof($result) != 0) {
			$output = $output.pack("C", $result[0]["driver"]);
			$output = $output.$result[0]["name"];
			$output = $output.pack("n", $result[0]["time"]);
			$output = $output.$result[0]["input_data"];
			$output = $output.$result[0]["player_id"];
			$output = $output.pack("N", $total);
			$output = $output.pack("N", $total);
			$output = $output.pack("N", getTotalRankingEntriesState($result[0]["state"], $course));
			$output = $output.pack("N", getTotalRankingEntriesDriver($result[0]["driver"], $course));
		} else {
			$output = $output.pack("N", $total); // Player's rank
		}
		
		return $output;
	}

	function getGhostByRank($course) { // 0.dlghost.cgb
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("ghostrank", $params) || strlen($params["ghostrank"]) != 8) {
			http_response_code(400);
			return;
		}
		$ghostrank = hexdec($params["ghostrank"]);
		if ($ghostrank == 0) {
			http_response_code(400);
			return;
		}
		$ghostrank = $ghostrank - 1;

		$db = connectMySQL();
		$stmt = $db->prepare("select * from amkj_ghosts where course = ? order by time asc limit 1 offset ?");
		$stmt->bind_param("ii", $course, $ghostrank);
		$stmt->execute();

		return makeGhostDownload(fancy_get_result($stmt), $course);
	}

	function getGhostByScore($course) { // 0.dlghost2.cgb
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("ghostscore", $params) || strlen($params["ghostscore"]) != 4) {
			http_response_code(400);
			return;
		}
		$ghostscore = hexdec($params["ghostscore"]);
		if ($ghostscore == 0) {
			http_response_code(400);
			return;
		}

		$db = connectMySQL();
		$stmt = $db->prepare("select * from amkj_ghosts where course = ? and time < ? order by time desc limit 1");
		$stmt->bind_param("ii", $course, $ghostscore);
		$stmt->execute();

		return makeGhostDownload(fancy_get_result($stmt), $course);
	}

	function getGhostByPlayerID($course) { // 0.dlghost3.cgb
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("myid", $params) || strlen($params["myid"]) != 32) {
			http_response_code(400);
			return;
		}
		$myid = hex2bin($params["myid"]);
		if ($myid === false) {
			http_response_code(400);
		}
		$decoded = decodePlayerID($myid);
		if (!$decoded["valid"]) {
			return makeGhostDownload(array(), $course);
		}

		$db = connectMySQL();
		$stmt = $db->prepare("select * from amkj_ghosts where course = ? and player_id = ?");
		$stmt->bind_param("is", $course, $myid);
		$stmt->execute();

		return makeGhostDownload(fancy_get_result($stmt), $course);
	}

	function getPlayerIDByRank($course) { // 0.dlghostid.cgb (unused)
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("ghostrank", $params) || strlen($params["ghostrank"]) != 8) {
			http_response_code(400);
			return;
		}
		$ghostrank = hexdec($params["ghostrank"]);
		if ($ghostrank == 0) {
			http_response_code(400);
			return;
		}
		$ghostrank = $ghostrank - 1;

		$db = connectMySQL();
		$stmt = $db->prepare("select player_id from amkj_ghosts where course = ? order by time asc limit 1 offset ?");
		$stmt->bind_param("ii", $course, $ghostrank);
		$stmt->execute();

		$output = pack("n", date("Y", time() + 32400)) // Year
		         .pack("C", date("m", time() + 32400)) // Month
		         .pack("C", date("d", time() + 32400)) // Day
		         .pack("C", date("H", time() + 32400)) // Hour
		         .pack("C", date("i")); // Minute
		
		$output = $output.pack("N", getTotalRankingEntries($course)); // Seems to indicate if something was found
		$output = $output.pack("n", sizeof($result)); // Seems to indicate if data is present
		
		if (sizeof($result) != 0) {
			$output = $output.$result[0]["player_id"];
		}
		
		return $output;
	}

	function getPlayerIDByStateRank($course) { // 0.dlghostst.cgb
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("ghostrank", $params) || strlen($params["ghostrank"]) != 8 || !array_key_exists("state", $params) || strlen($params["state"]) != 2) {
			http_response_code(400);
			return;
		}
		$ghostrank = hexdec($params["ghostrank"]);
		$state = hexdec($params["state"]);
		if ($ghostrank == 0) {
			http_response_code(400);
			return;
		}
		$ghostrank = $ghostrank - 1;

		$db = connectMySQL();
		$stmt = $db->prepare("select player_id from amkj_ghosts where course = ? and state = ? order by time asc limit 1 offset ?");
		$stmt->bind_param("iii", $course, $state, $ghostrank);
		$stmt->execute();

		$output = pack("n", date("Y", time() + 32400)) // Year
		         .pack("C", date("m", time() + 32400)) // Month
		         .pack("C", date("d", time() + 32400)) // Day
		         .pack("C", date("H", time() + 32400)) // Hour
		         .pack("C", date("i")); // Minute
		
		$output = $output.pack("N", getTotalRankingEntriesState($course, $state)); // Seems to indicate if something was found
		$output = $output.pack("n", sizeof($result)); // Seems to indicate if data is present
		
		if (sizeof($result) != 0) {
			$output = $output.$result[0]["player_id"];
		}
		
		return $output;
	}

	function getPlayerIDByDriverRank($course) { // 0.dlghostdr.cgb
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("ghostrank", $params) || strlen($params["ghostrank"]) != 8 || !array_key_exists("driver", $params) || strlen($params["driver"]) != 2) {
			http_response_code(400);
			return;
		}
		$ghostrank = hexdec($params["ghostrank"]);
		$driver = hexdec($params["driver"]);
		if ($ghostrank == 0) {
			http_response_code(400);
			return;
		}
		$ghostrank = $ghostrank - 1;

		$db = connectMySQL();
		$stmt = $db->prepare("select player_id from amkj_ghosts where course = ? and driver = ? order by time asc limit 1 offset ?");
		$stmt->bind_param("iii", $course, $driver, $ghostrank);
		$stmt->execute();

		$output = pack("n", date("Y", time() + 32400)) // Year
		         .pack("C", date("m", time() + 32400)) // Month
		         .pack("C", date("d", time() + 32400)) // Day
		         .pack("C", date("H", time() + 32400)) // Hour
		         .pack("C", date("i")); // Minute
		
		$output = $output.pack("N", getTotalRankingEntriesDriver($course, $driver)); // Seems to indicate if something was found
		$output = $output.pack("n", sizeof($result)); // Seems to indicate if data is present
		
		if (sizeof($result) != 0) {
			$output = $output.$result[0]["player_id"];
		}
		
		return $output;
	}
	
	function makeRankingEntry($rank, $playerData) {
		$data = pack("N", $rank);
		$data = $data.pack("C", $playerData["driver"]);
		$data = $data.$playerData["name"];
		$data = $data.pack("n", $playerData["time"]);
		$data = $data.$playerData["player_id"];
		return $data;
	}
	
	function getTop10($course) {
		$db = connectMySQL();
		$stmt = $db->prepare("select player_id, name, driver, time from amkj_ghosts where course = ? order by time asc limit 11");
		$stmt->bind_param("i", $course);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		$entries = array();
		for ($i = 0; $i < sizeof($result); $i++) {
			array_push($entries, makeRankingEntry($i + 1, $result[$i]));
		}
		return $entries;
	}
	
	function getRivals($course, $myid) {
		$myrank = getOwnRank($course, $myid);
		if ($myrank["rank"] <= 11) {
			return array();
		}
		$myrankOffset = $myrank["rank"] - 12;
		
		$db = connectMySQL();
		$stmt = $db->prepare("select player_id, name, driver, time from amkj_ghosts where course = ? order by time asc limit 11 offset ?");
		$stmt->bind_param("ii", $course, $myrankOffset);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		$entries = array();
		for ($i = 0; $i < sizeof($result); $i++) {
			array_push($entries, makeRankingEntry($myrankOffset + $i + 1, $result[$i]));
		}
		return $entries;
	}
	
	function getPlayerAtSpecificRank($course, $rank) {
		$offset = $rank - 1;
		
		$db = connectMySQL();
		$stmt = $db->prepare("select player_id, name, driver, time from amkj_ghosts where course = ? order by time asc limit 1 offset ?");
		$stmt->bind_param("ii", $course, $offset);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		if (sizeof($result) > 0) {
			return makeRankingEntry($rank, $result[0]);
		}
	}
	
	function getTop10State($course, $state) {
		$db = connectMySQL();
		$stmt = $db->prepare("select player_id, name, driver, time from amkj_ghosts where course = ? and state = ? order by time asc limit 11");
		$stmt->bind_param("ii", $course, $state);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		$entries = array();
		for ($i = 0; $i < sizeof($result); $i++) {
			array_push($entries, makeRankingEntry($i + 1, $result[$i]));
		}
		return $entries;
	}
	
	function getTop10Driver($course, $driver) {
		$db = connectMySQL();
		$stmt = $db->prepare("select player_id, name, driver, time from amkj_ghosts where course = ? and driver = ? order by time asc limit 11");
		$stmt->bind_param("ii", $course, $driver);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		$entries = array();
		for ($i = 0; $i < sizeof($result); $i++) {
			array_push($entries, makeRankingEntry($i + 1, $result[$i]));
		}
		return $entries;
	}

	function query($course) { // query.cgb
		// Get the parameters and validate them
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("myid", $params) || strlen($params["myid"]) != 32
			|| !array_key_exists("state", $params) || strlen($params["state"]) != 2
			|| !array_key_exists("driver", $params) || strlen($params["driver"]) != 2
			|| !array_key_exists("rk_1", $params) || strlen($params["rk_1"]) != 8
			|| !array_key_exists("rk_2", $params) || strlen($params["rk_2"]) != 8
			|| !array_key_exists("rk_3", $params) || strlen($params["rk_3"]) != 8
			|| !array_key_exists("rk_4", $params) || strlen($params["rk_4"]) != 8
			|| !array_key_exists("rk_5", $params) || strlen($params["rk_5"]) != 8
			|| !array_key_exists("rk_6", $params) || strlen($params["rk_6"]) != 8
			|| !array_key_exists("rk_7", $params) || strlen($params["rk_7"]) != 8
			|| !array_key_exists("rk_8", $params) || strlen($params["rk_8"]) != 8
			|| !array_key_exists("rk_9", $params) || strlen($params["rk_9"]) != 8
			|| !array_key_exists("rk_10", $params) || strlen($params["rk_10"]) != 8
			|| !array_key_exists("rk_11", $params) || strlen($params["rk_11"]) != 8) {
			http_response_code(400);
			return;
		}
		$myid = hex2bin($params["myid"]);
		/*if (!checkPlayerID($myid, null)) {
			http_response_code(400);
			return;
		}*/
		$state = hexdec($params["state"]);
		$driver = hexdec($params["driver"]);
		$rk = array();
		for ($i = 1; $i < 12; $i++) {
			array_push($rk, hexdec($params["rk_".$i]));
		}
		
		echo pack("n", date("Y", time() + 32400)); // Year
		echo pack("C", date("m", time() + 32400)); // Month
		echo pack("C", date("d", time() + 32400)); // Day
		echo pack("C", date("H", time() + 32400)); // Hour
		echo pack("C", date("i")); // Minute
		
		echo pack("N", getTotalRankingEntries($course)); // Probably total amount of ranked players
		
		echo pack("N", getTotalRankingEntries($course)); // ? If lower than 50, the ranking category below rivals uses the top 10 data
		
		// Worldwide top 10 (for some reason the game accepts up to 11 entries but only displays 10)
		$top10 = getTop10($course);
		echo pack("n", sizeof($top10));
		for ($i = 0; $i < sizeof($top10); $i++) {
			echo $top10[$i];
		}
		
		// Rivals (same as above, up to 11 entries are accepted. If less than 11, this is ignored and the worldwide top 10 are displayed instead)
		$rivals = getRivals($course, $myid);
		echo pack("n", sizeof($rivals));
		for ($i = 0; $i < sizeof($rivals); $i++) {
			echo $rivals[$i];
		}
		
		// Own rank
		$myrank = getOwnRank($course, $myid);
		if ($myrank["rank"] != 0) {
			echo pack("n", 1);
			echo pack("N", $myrank["rank"]);

			echo pack("n", 1);
			echo pack("N", $myrank["rank"]);
			echo pack("C", $myrank["driver"]);
			echo pack("n", $myrank["time"]);
		} else {
			echo pack("n", 0);
			echo pack("n", 0);
		}

		// That category below rivals
		for ($i = 0; $i < sizeof($rk); $i++) {
			$player = getPlayerAtSpecificRank($course, $rk[$i]);
			echo pack("n", !empty($player));
			if (!empty($player)) {
				echo $player;
			}
		}

	// --- State top 10 --- //
		
		echo pack("n", date("Y", time() + 32400)); // Year
		echo pack("C", date("m", time() + 32400)); // Month
		echo pack("C", date("d", time() + 32400)); // Day
		echo pack("C", date("H", time() + 32400)); // Hour
		echo pack("C", date("i")); // Minute
		
		echo pack("N", getTotalRankingEntriesState($course, $state)); // Probably total amount of ranked players
		
		echo pack("N", getTotalRankingEntriesState($course, $state)); // ? If lower than 50, the ranking category below rivals uses the top 10 data
		
		// Worldwide top 10 (for some reason the game accepts up to 11 entries but only displays 10)
		$top10s = getTop10State($course, $state);
		echo pack("n", sizeof($top10s));
		for ($i = 0; $i < sizeof($top10s); $i++) {
			echo $top10s[$i];
		}
		
		// Own rank
		$myranks = getOwnRankState($course, $myid, $state);
		if ($myranks["rank"] != 0) {
			echo pack("n", 1);
			echo pack("N", $myranks["rank"]);

			echo pack("n", 1);
			echo pack("N", $myranks["rank"]);
			echo pack("C", $myranks["driver"]);
			echo pack("n", $myranks["time"]);
		} else {
			echo pack("n", 0);
			echo pack("n", 0);
		}

	// --- Driver top 10 --- //
		
		echo pack("n", date("Y", time() + 32400)); // Year
		echo pack("C", date("m", time() + 32400)); // Month
		echo pack("C", date("d", time() + 32400)); // Day
		echo pack("C", date("H", time() + 32400)); // Hour
		echo pack("C", date("i")); // Minute
		
		echo pack("N", getTotalRankingEntriesDriver($course, $driver)); // Probably total amount of ranked players
		
		echo pack("N", getTotalRankingEntriesDriver($course, $driver)); // ? If lower than 50, the ranking category below rivals uses the top 10 data
		
		// Worldwide top 10 (for some reason the game accepts up to 11 entries but only displays 10)
		$top10d = getTop10Driver($course, $driver);
		echo pack("n", sizeof($top10d));
		for ($i = 0; $i < sizeof($top10d); $i++) {
			echo $top10d[$i];
		}
		
		// Own rank
		$myrankd = getOwnRankDriver($course, $myid, $driver);
		if ($myrankd["rank"] != 0) {
			echo pack("n", 1);
			echo pack("N", $myrankd["rank"]);

			echo pack("n", 1);
			echo pack("N", $myrankd["rank"]);
			echo pack("C", $myrankd["driver"]);
			echo pack("n", $myrankd["time"]);
		} else {
			echo pack("n", 0);
			echo pack("n", 0);
		}

		echo pack("N", getTotalRankingEntries($course));
	}
	
	function parseGhostUpload($input) {
		$data = array();
		$data["player_id"] = fread($input, 0x10);
		$data["course_no"] = unpack("C", fread($input, 0x1))[1];
		$data["driver"] = unpack("C", fread($input, 0x1))[1];
		$data["name"] = fread($input, 0x5);
		$data["state"] = unpack("C", fread($input, 0x1))[1];
		$data["unk18"] = unpack("n", fread($input, 0x2))[1];
		$data["time"] = unpack("n", fread($input, 0x2))[1];
		$data["input_data"] = fread($input, 0x1000);
		$data["full_name"] = fread($input, 0x10);
		$data["phone_number"] = fread($input, 0xc);
		$data["postal_code"] = fread($input, 0x8);
		$data["address"] = fread($input, 0x80);
		return $data;
	}

	function validateGhostUpload($data) {
		$k = ord($data["player_id"]);
		for ($i = 1; $i < 16; $i++) {
			$k += ord($data["player_id"][$i]);
		}
		if (($k ^ $data["course_no"]) & 1) {
			$a = $data["driver"] + $k * ($k + 1);
			$b = $data["driver"] + $data["course_no"] - $k;
		} else {
			$a = $data["driver"] + $k * ($k - 1);
			$b = $data["driver"] - $data["course_no"] - $k;
		}

		for ($i = 0; $i < 5; $i++) {
			$k = ord($data["name"][$i]);
			if (($b ^ $k) & 1) {
				$a += $k;
				$b -= $k;
			} else {
				$a -= $k;
				$b += $k;
			}
		}

		$k = $data["state"] + ($data["time"] >> 8);
		$a = ($a + $k) | $data["time"];
		$b = ($b + $k) ^ $data["time"];

		for ($i = 0; $i < 4096; $i++) {
			$k = ord($data["input_data"][$i]);
			$a ^= $k;
			$b += $k;
		}
		for ($i = 0; $i < 16; $i++) {
			$k = ord($data["full_name"][$i]);
			$a += $k;
			$b ^= $k;
		}
		for ($i = 0; $i < 12; $i++) {
			$k = ord($data["phone_number"][$i]);
			$a ^= $k;
			$b -= $k;
		}
		for ($i = 0; $i < 8; $i++) {
			$k = ord($data["postal_code"][$i]);
			$a -= $k;
			$b += $k;
		}
		for ($i = 0; $i < 128; $i++) {
			$k = ord($data["address"][$i]);
			$a ^= $k;
			$b ^= $k;
		}
		return $data["unk18"] == (($a & 0xff) << 8 | ($b & 0xff));
	}

	function entry($course) { // 0.entry.cgb
		$size = (int) $_SERVER['CONTENT_LENGTH'];
		if ($size != 0x10c0) {
			http_response_code(400);
			return;
		}
		$data = parseGhostUpload(fopen("php://input", "rb"));

		if ($data["course_no"] != $course || $data["driver"] > 7) {
			http_response_code(400);
			return;
		}

		if (!validateGhostUpload($data)) {
			http_response_code(400);
			return;
		}

		// Validate sent player ID
		if (!checkPlayerID($data["player_id"], $_SESSION['userId'])) {
			// Player ID invalid (eg email mismatch)
			http_response_code(400);
			return;
		}

		$db = connectMySQL();
		$db->begin_transaction();
		try {
			// Delete existing record
			$stmt = $db->prepare("delete ignore from amkj_ghosts where player_id = ? and course = ?");
			$stmt->bind_param("si", $data["player_id"], $course);
			$stmt->execute();

			// Insert new record
			$stmt = $db->prepare("insert into amkj_ghosts (player_id, course_no, name, state, unk18, course, driver, time, input_data, full_name, phone_number, postal_code, address) values (?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$stmt->bind_param("iisiiiiisssss", $data["player_id"], $course, $data["name"], $data["state"], $data["unk18"], $course, $data["driver"], $data["time"], $data["input_data"], $data["full_name"], $data["phone_number"], $data["postal_code"], $data["address"]);
			$stmt->execute();

			$db->commit();
		} catch (mysqli_sql_exception $e) {
			$db->rollback();
			http_response_code(500);
			throw $e;
		}
	}
?>
