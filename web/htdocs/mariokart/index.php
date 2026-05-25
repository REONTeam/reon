<?php
	require_once("../../classes/TemplateUtil.php");
	require_once("../../classes/DBUtil.php");
	require_once("../../classes/SessionUtil.php");
	require_once("../../classes/MarioKartUtil.php");
	session_start();

	function mk_normalize_player_name($text) {
		$value = (string)$text;
		if ($value === '') {
			return $value;
		}

		if (function_exists('mb_convert_kana')) {
			$value = mb_convert_kana($value, 'asKV', 'UTF-8');
		}

		$map = array(
			'　' => ' ',
			'＂' => '"',
			'＇' => "'",
			'，' => ',',
			'．' => '.',
			'：' => ':',
			'；' => ';',
			'？' => '?',
			'！' => '!',
			'（' => '(',
			'）' => ')',
			'［' => '[',
			'］' => ']',
			'｛' => '{',
			'｝' => '}',
			'－' => '-',
			'＿' => '_',
			'＋' => '+',
			'＝' => '=',
			'／' => '/',
			'＼' => '\\',
			'＆' => '&',
			'％' => '%',
			'＃' => '#',
			'＠' => '@',
			'＊' => '*',
		);

		$out = strtr($value, $map);
		return trim($out);
	}

	function mk_table_columns($db, $table_name) {
		$table_name = str_replace("`", "``", (string)$table_name);
		$columns = array();

		$result = $db->query("SHOW COLUMNS FROM `" . $table_name . "`");
		if (!$result) {
			return $columns;
		}

		while ($row = $result->fetch_assoc()) {
			$field = strtolower((string)($row["Field"] ?? ""));
			if ($field !== "") {
				$columns[$field] = true;
			}
		}
		$result->close();
		return $columns;
	}

	function mk_pick_column($columns_map, $candidates) {
		foreach ($candidates as $candidate) {
			$key = strtolower((string)$candidate);
			if (isset($columns_map[$key])) {
				return (string)$candidate;
			}
		}
		return null;
	}

	function mk_safe_ident($name) {
		return str_replace("`", "``", (string)$name);
	}
	
    $mk_util = MarioKartUtil::getInstance();

    $db_util = DBUtil::getInstance();
    
	// TODO: Add region checks when we support more than Japanese

    $db = $db_util->getDB();

    $tracks = array();
	$driver_icon_by_id = array(
		0 => 'mario',
		1 => 'luigi',
		2 => 'browser',
		3 => 'peach',
		4 => 'dk',
		5 => 'wario',
		6 => 'toad',
		7 => 'yoshi',
	);
	$track_icon_by_course = array(
		0 => 'peach_circuit',
		1 => 'shy_guy_beach',
		2 => 'riverside_park',
		3 => 'bowser_castle_1',
		4 => 'mario_circuit',
		5 => 'boo_lake',
		6 => 'shy_cheese_land',
		7 => 'bowser_castle_2',
		8 => 'luigi_circuit',
		9 => 'sky_garden',
		10 => 'cheep_cheep_island',
		11 => 'sunset_wilds',
		12 => 'snow_land',
		13 => 'ribbon_road',
		14 => 'yoshi_desert',
		15 => 'bowser_castle_3',
		16 => 'lakeside_park',
		17 => 'broken_pier',
		18 => 'bowser_castle_4',
		19 => 'rainbow_road',
	);

    try {
		$columns = mk_table_columns($db, "amk_ghosts");
		$has_course = isset($columns["course"]);
		$has_course_no = isset($columns["course_no"]);
		$course_col = mk_pick_column($columns, array("course", "course_no"));
		$time_col = mk_pick_column($columns, array("time"));
		$name_col = mk_pick_column($columns, array("name", "player_name"));
		$driver_col = mk_pick_column($columns, array("driver"));
		$timestamp_col = mk_pick_column($columns, array("timestamp", "entry_time", "created_at"));

		if ($course_col === null || $time_col === null) {
			throw new \RuntimeException("amk_ghosts table is missing required columns");
		}

		$time_ident = mk_safe_ident($time_col);
		$course_expr_a = "";
		$course_expr_b = "";
		if ($has_course && $has_course_no) {
			$course_expr_a = "COALESCE(a.`course`, a.`course_no`)";
			$course_expr_b = "COALESCE(b.`course`, b.`course_no`)";
		} elseif ($has_course) {
			$course_ident = mk_safe_ident("course");
			$course_expr_a = "a.`" . $course_ident . "`";
			$course_expr_b = "b.`" . $course_ident . "`";
		} else {
			$course_ident = mk_safe_ident("course_no");
			$course_expr_a = "a.`" . $course_ident . "`";
			$course_expr_b = "b.`" . $course_ident . "`";
		}
		$name_select = ($name_col !== null)
			? ("a.`" . mk_safe_ident($name_col) . "` AS name")
			: "'' AS name";
		$driver_select = ($driver_col !== null)
			? ("a.`" . mk_safe_ident($driver_col) . "` AS driver")
			: "0 AS driver";
		$timestamp_select = ($timestamp_col !== null)
			? ("a.`" . mk_safe_ident($timestamp_col) . "` AS `timestamp`")
			: "NOW() AS `timestamp`";
		$order_timestamp = ($timestamp_col !== null)
			? ("a.`" . mk_safe_ident($timestamp_col) . "` ASC")
			: "a.id ASC";

		$sql =
			"SELECT a.id, " .
				$course_expr_a . " AS course, " .
				$driver_select . ", " .
				$name_select . ", " .
				"a.`" . $time_ident . "` AS `time`, " .
				$timestamp_select . " " .
			"FROM amk_ghosts a " .
			"WHERE " . $course_expr_a . " IS NOT NULL " .
			"AND a.`" . $time_ident . "` IS NOT NULL " .
			"ORDER BY " . $course_expr_a . " ASC, CAST(a.`" . $time_ident . "` AS UNSIGNED) ASC, " . $order_timestamp;

		$stmt = $db->prepare($sql);

		if (!$stmt) {
			error_log("Mario Kart query prepare failed: " . $db->error);
		} elseif (!$stmt->execute()) {
			error_log("Mario Kart query execute failed: " . $stmt->error);
		} else {
			$data = DBUtil::fancy_get_result($stmt);
			$saw_course_zero = false;
			$saw_course_twenty = false;
			$max_course = -1;

			foreach ($data as &$entry) {
				$entry["player_name"] = $mk_util->getString('j', $entry['name']);
				$entry["player_name_display"] = mk_normalize_player_name($entry["player_name"]);
				$entry["time"] = $time = $mk_util->convertTime($entry['time']);
				$entry["formatted_time"] = sprintf('%d\'%02d"%02d', $time->i, $time->s, round($time->f * 100));
				$driver_id = intval($entry["driver"] ?? -1);
				$entry["driver_icon_slug"] = $driver_icon_by_id[$driver_id] ?? "mario";
				$course_id_raw = intval($entry["course"] ?? -1);
				$entry["course_raw"] = $course_id_raw;
				if ($course_id_raw === 0) {
					$saw_course_zero = true;
				}
				if ($course_id_raw === 20) {
					$saw_course_twenty = true;
				}
				if ($course_id_raw > $max_course) {
					$max_course = $course_id_raw;
				}

				$candidates = array();
				if ($course_id_raw >= 0 && $course_id_raw <= 19) {
					$candidates[] = $course_id_raw;
				}
				if ($course_id_raw >= 1 && $course_id_raw <= 20) {
					$candidates[] = $course_id_raw - 1;
				}
				$candidates = array_values(array_unique($candidates));
				if (count($candidates) === 0) {
					continue;
				}

				$target_course = $candidates[0];
				if (count($candidates) === 2) {
					$prefer_zero_based = ($saw_course_zero && !$saw_course_twenty);
					$prefer_one_based = ($saw_course_twenty && !$saw_course_zero);
					if ($prefer_one_based) {
						$target_course = $candidates[1];
					}

					if (isset($tracks[$target_course])) {
						$alt_course = ($target_course === $candidates[0]) ? $candidates[1] : $candidates[0];
						if (!isset($tracks[$alt_course])) {
							$target_course = $alt_course;
						}
					}
				}

				if (!isset($tracks[$target_course])) {
					$entry["track_icon_slug"] = $track_icon_by_course[$target_course] ?? "peach_circuit";
					$tracks[$target_course] = $entry;
				}
			}
		}
	}
	catch (\Throwable $e) {
		$tracks = array();
		error_log("Mario Kart page load failed: " . $e->getMessage());
	}

	echo TemplateUtil::render("mariokart/index", [
        'ghosts' => $tracks,
		'track_icon_by_course' => $track_icon_by_course,
	]);
