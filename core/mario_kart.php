<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");
	
	function getGhostByPlayerID($course) {
		// Get the parameters and validate them
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("myid", $params) || strlen($params["myid"]) != 32) {
			http_response_code(400);
			return;
		}
		$myid = hex2bin($params["myid"]);

		$db = connectMySQL();
		$stmt = $db->prepare("select name, driver, course, time, input_data from amkj_ghosts where course = ? and player_id = ? limit 1");
		$stmt->bind_param("is", $course, $myid);
		$stmt->execute();
		
		return makeGhostDownload(fancy_get_result($stmt));
	}
	
	function getGhostByRank($course) {
		// Get the parameters and validate them
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
		$stmt = $db->prepare("select name, driver, course, time, input_data from amkj_ghosts where course = ? order by time asc limit 1 offset ?");
		$stmt->bind_param("ii", $course, $ghostrank);
		$stmt->execute();
		
		return makeGhostDownload(fancy_get_result($stmt));
	}
	
	function getGhostByScore($course) {
		// Get the parameters and validate them
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("ghostscore", $params) || strlen($params["ghostscore"]) != 4) {
			http_response_code(400);
			return;
		}
		$ghostscore = hexdec($params["ghostscore"]);

		$db = connectMySQL();
		$stmt = $db->prepare("select name, driver, course, time, input_data from amkj_ghosts where course = ? and time < ? order by time desc limit 1");
		$stmt->bind_param("ii", $course, $ghostscore);
		$stmt->execute();
		
		return makeGhostDownload(fancy_get_result($stmt));
	}
	
	function getPlayerIDByDriverRank($course) {
		// Get the parameters and validate them
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("ghostrank", $params) || strlen($params["ghostrank"]) != 8 || !array_key_exists("driver", $params) || strlen($params["driver"]) != 2) {
			http_response_code(400);
			return;
		}
		$ghostrank = hexdec($params["ghostrank"]);
		$driver = hexdec($params["driver"]);
		if ($ghostrank == 0 || $driver > 7) {
			http_response_code(400);
			return;
		}
		$ghostrank = $ghostrank - 1;

		$db = connectMySQL();
		$stmt = $db->prepare("select player_id from amkj_ghosts where course = ? and driver = ? order by time asc limit 1 offset ?");
		$stmt->bind_param("iii", $course, $driver, $ghostrank);
		$stmt->execute();
		
		return makePlayerIDDownload(fancy_get_result($stmt));
	}
	
	function getPlayerIDByStateRank($course) {
		// Get the parameters and validate them
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("ghostrank", $params) || strlen($params["ghostrank"]) != 8 || !array_key_exists("state", $params) || strlen($params["state"]) != 2) {
			http_response_code(400);
			return;
		}
		$ghostrank = hexdec($params["ghostrank"]);
		$state = hexdec($params["state"]);
		if ($ghostrank == 0 || $state > 46) {
			http_response_code(400);
			return;
		}
		$ghostrank = $ghostrank - 1;

		$db = connectMySQL();
		$stmt = $db->prepare("select player_id from amkj_ghosts where course = ? and state = ? order by time asc limit 1 offset ?");
		$stmt->bind_param("iii", $course, $state, $ghostrank);
		$stmt->execute();
		
		return makePlayerIDDownload(fancy_get_result($stmt));
	}
	
	function makeGhostDownload($result) {
		$output = hex2bin("000000000000");
		
		if (sizeof($result) == 0) {
			$output = $output.pack("N", 0); // Seems to indicate if something was found
		} else {
			$output = $output.pack("N", 1); // Seems to indicate if something was found
			if (true) {
				$output = $output.pack("n", 1); // Seems to indicate if ghost data is present
				
				$output = $output.hex2bin("00");
				$output = $output.$result[0]["name"];
				$output = $output.hex2bin("00000000000000000000");
				$output = $output.pack("C", $result[0]["driver"]);
				$output = $output.hex2bin("00");
				$output = $output.pack("v", $result[0]["time"]);
				$output = $output.hex2bin("0000000000");
				$output = $output.pack("C", $result[0]["course"] + 4);
				$output = $output.hex2bin("0000");
				$output = $output.hex2bin("0000");
				$output = $output.hex2bin("0000");
				$output = $output.hex2bin("326f1c15322319001c3c13232b110118");
				$output = $output.$result[0]["input_data"];
			} else {
				$output = $output.pack("n", 0); // Seems to indicate if ghost data is present
				
				$output = $output.pack("N", 10000); // Player's rank
			}
		}
		
		return $output;
	}
	
	function makePlayerIDDownload($result) {
		$output = hex2bin("000000000000");
		
		if (sizeof($result) == 0) {
			$output = $output.pack("N", 0); // Seems to indicate if something was found
		} else {
			$output = $output.pack("N", 1); // Seems to indicate if something was found
			$output = $output.pack("n", 1); // Seems to indicate if data is present
			
			$output = $output.$result[0]["player_id"];
		}
		
		return $output;
	}
	
	function getTop10($course, $myid) {
		$db = connectMySQL();
		$stmt = $db->prepare("select player_id, name, driver, time from amkj_ghosts where course = ? and player_id != ? order by time asc limit 11");
		$stmt->bind_param("is", $course, $myid);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		$entries = array();
		for ($i = 0; $i < sizeof($result); $i++) {
			array_push($entries, makeRankingEntry($i + 2, $result[$i]));
		}
		return $entries;
	}
	
	function getTop10MobileGP($myid) {
		$db = connectMySQL();
		$stmt = $db->prepare("select player_id, name, driver, time from amkj_ghosts_mobilegp where player_id != ? order by time asc limit 11");
		$stmt->bind_param("s", $myid);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		$entries = array();
		for ($i = 0; $i < sizeof($result); $i++) {
			array_push($entries, makeRankingEntry($i + 2, $result[$i]));
		}
		return $entries;
	}
	
	function getRivals($course, $myid, $myrecord) {
		$myrank = getOwnRank($course, $myid, $myrecord);
		if ($myrank <= 11) {
			return array();
		}
		$myrankOffset = $myrank - 12;
		
		$db = connectMySQL();
		$stmt = $db->prepare("select player_id, name, driver, time from amkj_ghosts where course = ? and player_id != ? order by time asc limit 11 offset ?");
		$stmt->bind_param("isi", $course, $myid, $myrankOffset);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		$entries = array();
		for ($i = 0; $i < sizeof($result); $i++) {
			array_push($entries, makeRankingEntry($myrank - 10 + $i, $result[$i]));
		}
		return $entries;
	}
	
	function getOwnRank($course, $myid, $myrecord) {
		$db = connectMySQL();
		$stmt = $db->prepare("select count(*) from amkj_ghosts where course = ? and (time < ? or (time = ? and id <= (select id from amkj_ghosts where player_id = ? limit 1)))");
		$stmt->bind_param("iiis", $course, $myrecord, $myrecord, $myid);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		$myrank = $result[0]["count(*)"];
		return $myrank;
	}
	
	function getOwnRankMobileGP($myid, $myrecord) {
		$db = connectMySQL();
		$stmt = $db->prepare("select count(*) from amkj_ghosts_mobilegp where (time < ? or (time = ? and id <= (select id from amkj_ghosts where player_id = ? limit 1)))");
		$stmt->bind_param("iis", $myrecord, $myrecord, $myid);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		$myrank = $result[0]["count(*)"];
		return $myrank;
	}
	
	function getTotalRankingEntries($course) {
		$db = connectMySQL();
		$stmt = $db->prepare("select count(*) from amkj_ghosts where course = ?");
		$stmt->bind_param("i", $course);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		return $result[0]["count(*)"];
	}
	
	function getTotalRankingEntriesMobileGP() {
		$db = connectMySQL();
		$stmt = $db->prepare("select count(*) from amkj_ghosts_mobilegp");
		$stmt->execute();
		$result = fancy_get_result($stmt);
		return $result[0]["count(*)"];
	}
	
	function getPlayerAtSpecificRank($course, $myid, $rank) {
		$offset = $rank - 1;
		
		$db = connectMySQL();
		$stmt = $db->prepare("select player_id, name, driver, time from amkj_ghosts where course = ? and player_id != ? order by time asc limit 1 offset ?");
		$stmt->bind_param("isi", $course, $myid, $offset);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		if (sizeof($result) > 0) {
			return makeRankingEntry($rank, $result[0]);
		}
	}
	
	function makeRankingEntry($rank, $playerData) {
		$data = pack("N", $rank);
		$data = $data.pack("C", $playerData["driver"]);
		$data = $data.$playerData["name"];
		$data = $data.pack("n", $playerData["time"]);
		$data = $data.$playerData["player_id"];
		return $data;
	}
	
	function checkPlayerID($playerId, $userId) {
		$db = connectMySQL();
		$stmt = $db->prepare("select user_id from amkj_user_map where player_id = ?");
		$stmt->bind_param("s", $playerId);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		if (sizeof($result) > 0) {
			if ($userId != $result[0]["user_id"]) {
				// Player ID claimed by a different user
				return false;
			}
		} else {
			// Player ID not claimed yet
			$stmt = $db->prepare("insert into amkj_user_map (player_id, user_id) values (?,?)");
			$stmt->bind_param("si", $playerId, $userId);
			$stmt->execute();
		}
		return true;
	}
	
	function parseGhostUpload($input) {
		$data = array();
		$data["player_id"] = fread($input, 0x10);
		fseek($input, 0x11);
		$data["driver"] = unpack("C", fread($input, 0x1))[1];
		$data["name"] = fread($input, 0x5);
		$data["state"] = unpack("C", fread($input, 0x1))[1];
		fseek($input, 0x1a);
		$data["time"] = unpack("n", fread($input, 0x2))[1];
		fseek($input, 0x44);
		$data["input_data"] = fread($input, 0xfd6);
		//fseek($input, 0x101c);
		//$data["full_name"] = fread($input, 0x10);
		//$data["phone_number"] = fread($input, 0xc);
		//$data["postal_code"] = fread($input, 0x7);
		//fseek($input, 0x1040);
		//$data["address"] = fread($input, 0x80);
		return $data;
	}

	function query($course) {
		// Get the parameters and validate them
		parse_str(file_get_contents("php://input"), $params);
		if (!array_key_exists("myid", $params) || strlen($params["myid"]) != 32
			|| !array_key_exists("myrecord", $params) || strlen($params["myrecord"]) != 4
			|| !array_key_exists("pickuprecord", $params) || strlen($params["pickuprecord"]) != 4
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
		$myrecord = hexdec($params["myrecord"]);
		$pickuprecord = hexdec($params["pickuprecord"]);
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
		
		echo pack("N", 50); // ? If lower than 50, the ranking category below rivals uses the top 10 data
		
		// Worldwide top 10 (for some reason the game accepts up to 11 entries but only displays 10)
		$top10 = getTop10($course, $myid);
		echo pack("n", sizeof($top10));
		for ($i = 0; $i < sizeof($top10); $i++) {
			echo $top10[$i];
		}
		
		// Rivals (same as above, up to 11 entries are accepted. If less than 11, this is ignored and the worldwide top 10 are displayed instead)
		$rivals = getRivals($course, $myid, $myrecord);
		echo pack("n", sizeof($rivals));
		for ($i = 0; $i < sizeof($rivals); $i++) {
			echo $rivals[$i];
		}
		
		// Own rank
		$myrank = getOwnRank($course, $myid, $myrecord);
		echo pack("n", $myrank != 0);
		if ($myrank != 0) {
			echo pack("N", $myrank);
		}
		
		// Unknown
		echo pack("n", 0);
		//echo(pack("N",0));
		//echo(pack("C",0));
		//echo(pack("C",0));
		//echo(pack("C",0));

		// That category below rivals
		for ($i = 0; $i < sizeof($rk); $i++) {
			$player = getPlayerAtSpecificRank($course, $myid, $rk[$i]);
			echo pack("n", !empty($player));
			if (!empty($player)) {
				echo $player;
			}
		}
	}

	function entry($course) {
		$size = (int) $_SERVER['CONTENT_LENGTH'];
		if ($size != 0x10c0) {
			http_response_code(400);
			return;
		}
		$data = parseGhostUpload(fopen("php://input", "rb"));
		
		if ($data["driver"] > 7 || $data["state"] > 46) {
			http_response_code(400);
			return;
		}
		
		$data["course"] = $course;
		
		// Validate sent player ID
		if (!checkPlayerID($data["player_id"], $_SESSION['userId'])) {
			// Player ID claimed by a different user
			http_response_code(400);
			return;
		}
		
		$db = connectMySQL();
		$db->begin_transaction();
		try {
			// Delete existing record
			$stmt = $db->prepare("delete ignore from amkj_ghosts where player_id = ? and course = ?");
			$stmt->bind_param("si", $data["player_id"], $data["course"]);
			$stmt->execute();
			
			// Insert new record
			$stmt = $db->prepare("insert into amkj_ghosts (player_id, name, state, driver, time, course, input_data) values (?,?,?,?,?,?,?)");
			$stmt->bind_param("ssiiiis", $data["player_id"], $data["name"], $data["state"], $data["driver"], $data["time"], $data["course"], $data["input_data"]);
			$stmt->execute();
			
			$db->commit();
		} catch (mysqli_sql_exception $e) {
			$db->rollback();
			http_response_code(500);
			throw $e;
		}
	}
?>