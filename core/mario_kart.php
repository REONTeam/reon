<?php
	require_once(CORE_PATH."/database.php");
	
	function getGhostByPlayerID($course, $id) {
		$db = connectMySQL();
		$stmt = $db->prepare("select name, driver, course, time, input_data from amkj_ghosts where course = ? and player_id = ? limit 1");
		$stmt->bind_param("is", $course, $id);
		$stmt->execute();
		
		return makeGhostDownload(fancy_get_result($stmt));
	}
	
	function getGhostByRank($course, $rank) {
		$db = connectMySQL();
		$stmt = $db->prepare("select name, driver, course, time, input_data from amkj_ghosts where course = ? order by time asc limit 1 offset ?");
		$stmt->bind_param("ii", $course, $rank);
		$stmt->execute();
		
		return makeGhostDownload(fancy_get_result($stmt));
	}
	
	function getGhostByScore($course, $score) {
		$db = connectMySQL();
		$stmt = $db->prepare("select name, driver, course, time, input_data from amkj_ghosts where course = ? and time < ? order by rand() limit 1");
		$stmt->bind_param("ii", $course, $score);
		$stmt->execute();
		
		return makeGhostDownload(fancy_get_result($stmt));
	}
	
	function getPlayerIDByDriverRank($course, $rank, $driver) {
		$db = connectMySQL();
		$stmt = $db->prepare("select player_id from amkj_ghosts where course = ? and driver = ? order by time asc limit 1 offset ?");
		$stmt->bind_param("iii", $course, $driver, $rank);
		$stmt->execute();
		
		return makePlayerIDDownload(fancy_get_result($stmt));
	}
	
	function getPlayerIDByStateRank($course, $rank, $state) {
		$db = connectMySQL();
		$stmt = $db->prepare("select player_id from amkj_ghosts where course = ? and state = ? order by time asc limit 1 offset ?");
		$stmt->bind_param("iii", $course, $state, $rank);
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
?>