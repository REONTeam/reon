<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");

	function makeRankingEntry($raceData) {
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
		$output = $output.pack("v", (int)substr($raceData["date"], 0, 4));
		$output = $output.pack("C", (int)substr($raceData["date"], 5, 2));
		$output = $output.pack("C", (int)substr($raceData["date"], 8, 2));
		$output = $output.pack("C", (int)substr($raceData["date"], 11, 2));
		$output = $output.pack("C", (int)substr($raceData["date"], 14, 2));
		$output = $output.pack("C", (int)substr($raceData["date"], 17, 2));
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
			$stmt = $db->prepare("select * from agtj_ghosts where (course = ? or course = ?) and dl_ok is not null order by dl_ok desc limit 30");
			$stmt->bind_param($course, $course + 9);
		} else {
			$excrs = getExtraCourse();
			if (!$excrs) {
				http_response_code(404);
				return;
			}
			$stmt = $db->prepare("select * from agtj_ghosts where (course = 6 or course = 7 or course = 8 or course = 15 or course = 16 or course = 17) and dl_ok is not null and excrs = ? order by dl_ok desc limit 30");
			$stmt->bind_param($excrs);
		}
		$stmt->execute();
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0) {
			http_response_code(404);
			return;
		}

		echo pack("v", (int)substr($result[0]["dl_ok"], 0, 4));
		echo pack("C", (int)substr($result[0]["dl_ok"], 5, 2));
		echo pack("C", (int)substr($result[0]["dl_ok"], 8, 2));
		echo pack("C", (int)substr($result[0]["dl_ok"], 11, 2));
		echo pack("C", (int)substr($result[0]["dl_ok"], 14, 2));
		echo pack("C", (int)substr($result[0]["dl_ok"], 17, 2));
		echo "\0";

		for ($i = 0; $i < sizeof($result); $i++) {
			echo makeRankingEntry($result[$i]);
			$filename = sprintf("ghost.cgb&agtj=%08x", $result[$i]["id"]);
			if ($result[$i]["price"] != 0) {
				$filename = sprintf("%d", $result[$i]["price"]).".".$filename;
			}
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
			$stmt = $db->prepare("select * from agtj_ghosts where course = ? or course = ? order by time desc limit 50");
			$stmt->bind_param($course, $course + 9);
		} else {
			$excrs = getExtraCourse();
			if (!$excrs) {
				http_response_code(404);
				return;
			}
			$stmt = $db->prepare("select * from agtj_ghosts where (course = 6 or course = 7 or course = 8 or course = 15 or course = 16 or course = 17) and excrs = ? order by time desc limit 50");
			$stmt->bind_param($excrs);
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
		$date_array = array(
			(int)substr($date, 0, 4),
			(int)substr($date, 5, 2),
			(int)substr($date, 8, 2),
			(int)substr($date, 11, 2) + 9,
			(int)substr($date, 14, 2),
			(int)substr($date, 17, 2)
		);
		if ($date_array[3] >= 24) {
			$date_array[3] -= 24;
			$date_array[2]++;
		}
		if ($date_array[2] > cal_days_in_month(CAL_GREGORIAN, $date_array[1], $date_array[0])) {
			$date_array[2] = 1;
			$date_array[1]++;
		}
		if ($date_array[1] > 12) {
			$date_array[1] = 1;
			$date_array[0]++;
		}

		echo pack("v", $date_array[0]);
		echo pack("C", $date_array[1]);
		echo pack("C", $date_array[2]);
		echo pack("C", $date_array[3]);
		echo pack("C", $date_array[4]);
		echo pack("C", $date_array[5]);
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
		$stmt = $db->prepare("select * from agtj_ghosts where dl_ok is not null and id = ? limit 1")
		$stmt->bind_param("i", $id);
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0 || $result[0]["price"] != $price) {
			http_response_code(404);
			return;
		}

		echo makeRankingEntry($result[0]);
		echo $result[0]["input_data"];
	}
?>
