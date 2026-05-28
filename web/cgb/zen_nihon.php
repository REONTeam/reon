<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");
	require_once(CORE_PATH."/timezone.php");

	function makeRankingEntry($raceData) {
		$time = get_user_local_time($datetime = $raceData["date"]);
		$output = pack("C", $raceData["course"]);
		$output = $output.pack("C", $raceData["weather"]);
		$output = $output.pack("C", $raceData["car"]);
		$output = $output.pack("C", $raceData["trans"]);
		$output = $output.pack("C", $raceData["gear"]);
		$output = $output.pack("C", $raceData["steer"]);
		$output = $output.pack("C", $raceData["brake"]);
		$output = $output.pack("C", $raceData["tire"]);
		$output = $output.pack("C", $raceData["aero"]);
		$output = $output.pack("C", $raceData["excrs"]);
		$output = $output."\0\0";
		$output = $output.pack("v", $raceData["handicap"]);
		$output = $output.$raceData["name"];
		$output = $output.pack("V", $raceData["time"]);
		$output = $output.pack("v", $time->format("Y"));
		$output = $output.pack("C", $time->format("m"));
		$output = $output.pack("C", $time->format("d"));
		$output = $output.pack("C", $time->format("H"));
		$output = $output.pack("C", $time->format("i"));
		$output = $output.pack("C", $time->format("s"));
		$output = $output."\0";
		$output = $output.pack("V", $raceData["id"]);
		return $output;
	}

	function getExtraCourse() {
		$excrs = 0x80;
		$bit = 0x80;
		while ($bit) {
			// this assumes that, if a file exists for course #N,
			// files must exist for courses #(N & 0x80),
			// #(N & 0xC0), #(N & 0xE0), ..., and #(N & 0xFE),
			// except that a file should never exist for course #0.
			// under that assumption, the greatest number of any
			// existing course file will end up in $excrs.
			$bit >>= 1;
			if (realpath(__DIR__.DIRECTORY_SEPARATOR.sprintf("100.gtexcrs%03d.cgb", $excrs)) !== false) {
				$excrs += $bit;
			} else {
				$excrs -= ($bit ?: 1);
			}
		}
		return $excrs;
	}

	function gtgst($course) {
		$db = connectMySQL();
		if ($course != 6) {
			$stmt = $db->prepare("select * from agt_ghosts where (course = ? or course = ?) and dl_ok is not null and game_region = ? order by dl_ok desc limit 30");
			$game_region = getCurrentGameRegion();
			$stmt->bind_param("iis", $course, $course + 9, $game_region);
		} else {
			$excrs = getExtraCourse();
			if (!$excrs) {
				http_response_code(404);
				return;
			}
			$stmt = $db->prepare("select * from agt_ghosts where (course = 6 or course = 7 or course = 8 or course = 15 or course = 16 or course = 17) and dl_ok is not null and excrs = ? and game_region = ? order by dl_ok desc limit 30");
			$game_region = getCurrentGameRegion();
			$stmt->bind_param("is", $excrs, $game_region);
		}
		$stmt->execute();
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0) {
			http_response_code(404);
			return;
		}

		$time = date_create_immutable($result[0]["dl_ok"])->setTimezone("+0900");
		echo pack("v", $time->format("Y"));
		echo pack("C", $time->format("m"));
		echo pack("C", $time->format("d"));
		echo pack("C", $time->format("H"));
		echo pack("C", $time->format("i"));
		echo pack("C", $time->format("s"));
		echo "\0";

		for ($i = 0; $i < sizeof($result); $i++) {
			echo makeRankingEntry($result[$i]);
			$filename = sprintf("%d.ghost.cgb&agtj=%08x", $result[$i]["price"], $result[$i]["id"]);
			echo $filename;
			for ($j = strlen($filename); $j < 32; $j++) {
				echo "\0";
			}
		}
		for ($i = sizeof($result) * 84; $i < 2520; $i++) {
			// 2520 = 30 * 84
			echo "\0";
		}
	}

	function gtrk($course) {
		$db = connectMySQL();
		if ($course != 6) {
			$stmt = $db->prepare("select * from agt_ghosts where (course = ? or course = ?) and game_region = ? order by time desc limit 50");
			$game_region = getCurrentGameRegion();
			$stmt->bind_param("iis", $course, $course + 9, $game_region);
		} else {
			$excrs = getExtraCourse();
			if (!$excrs) {
				http_response_code(404);
				return;
			}
			$stmt = $db->prepare("select * from agt_ghosts where (course = 6 or course = 7 or course = 8 or course = 15 or course = 16 or course = 17) and excrs = ? and game_region = ? order by time desc limit 50");
			$game_region = getCurrentGameRegion();
			$stmt->bind_param("is", $excrs, $game_region);
		}
		$stmt->execute();
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0) {
			http_response_code(404);
			return;
		}

		$date = $result[0]["date"];
		for ($i = 1; $i < sizeof($result); $i++) {
			$date = max($date, $result[$i]["date"]);
		}

		$time = date_create_immutable($date)->setTimezone("+0900");
		echo pack("v", $time->format("Y"));
		echo pack("C", $time->format("m"));
		echo pack("C", $time->format("d"));
		echo pack("C", $time->format("H"));
		echo pack("C", $time->format("i"));
		echo pack("C", $time->format("s"));
		echo "\0";

		for ($i = 0; $i < sizeof($result); $i++) {
			echo makeRankingEntry($result[$i]);
		}
		for ($i = sizeof($result) * 52; $i < 2600; $i++) {
			// 2600 = 50 * 52
			echo "\0";
		}
	}

	function ghostDownload($price) {
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
		$stmt = $db->prepare("select * from agt_ghosts where dl_ok is not null and id = ? and game_region = ? limit 1")
		$game_region = getCurrentGameRegion();
		$stmt->bind_param("is", $id, $game_region);
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0 || $result[0]["price"] != $price) {
			http_response_code(404);
			return;
		}

		echo makeRankingEntry($result[0]);
		echo $result[0]["input_data"];
	}
?>
