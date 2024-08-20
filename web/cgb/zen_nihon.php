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
		$excrs_bit = 0x80;
		while ($excrs_bit != 0) {
			// this assumes that, if a file exists for course #N, files must exist for courses #(N & 0x80), #(N & 0xC0), #(N & 0xE0), ..., #(N & 0xFE).
			// under that assumption, the greatest number of any existing course file will end up in $excrs.
			$excrs_bit >>= 1;
			if (realpath(__DIR__.DIRECTORY_SEPARATOR.sprintf("gtexcrs%03d.cgb", $excrs)) !== false) {
				$excrs += $excrs_bit;
			} else {
				$excrs -= max($excrs_bit, 1);
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
			$stmt = $db->prepare("select * from agtj_ghosts where (course = 6 or course = 7 or course = 8 or course = 15 or course = 16 or course = 17) and dl_ok is not null and excrs = ? order by dl_ok desc limit 30");
			$stmt->bind_param(getExtraCourse());
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
			$stmt = $db->prepare("select * from agtj_ghosts where (course = 6 or course = 7 or course = 8 or course = 15 or course = 16 or course = 17) and excrs = ? order by time desc limit 50");
			$stmt->bind_param(getExtraCourse());
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
		if ($date_array[2] == cal_days_in_month(CAL_GREGORIAN, $date_array[1], $date_array[0]) + 1) {
			$date_array[2] = 1;
			$date_array[1]++;
		}
		if ($date_array[1] == 13) {
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
?>
