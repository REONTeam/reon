<?php
	require_once("../../classes/TemplateUtil.php");
	require_once("../../classes/DBUtil.php");
	require_once("../../classes/SessionUtil.php");
	require_once("../../classes/PokemonUtil.php");
	require_once("../../scripts/bxt_decode_helpers.php");
	session_start();

	function bxt_rankings_table_columns($db, $table_name) {
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

	function bxt_rankings_has_column($columns_map, $column_name) {
		$key = strtolower((string)$column_name);
		return isset($columns_map[$key]);
	}

	function bxt_rankings_easy_chat_table_id($game_region) {
		$region = strtolower((string)$game_region);

		switch ($region) {
			case 'f':
				return 'btxf_easy_chat';
			case 'd':
				return 'btxd_easy_chat';
			case 's':
				return 'btxs_easy_chat';
			case 'i':
				return 'btxi_easy_chat';
			case 'j':
				return 'btxj_easy_chat';
			case 'e':
			case 'p':
			case 'u':
			default:
				return 'btxe_btxp_btxu_easy_chat';
		}
	}

	function bxt_rankings_token_has_word($token) {
		return preg_match('/[\p{L}\p{N}]/u', (string)$token) === 1;
	}

	function bxt_rankings_tokens_to_text($tokens) {
		$out = "";
		$nbsp = "\xC2\xA0";
		foreach ($tokens as $token) {
			$token = trim((string)$token);
			if ($token === "") {
				continue;
			}
			$token = str_replace(" ", $nbsp, $token);
			if ($out === "") {
				$out = $token;
				continue;
			}

			if (bxt_rankings_token_has_word($token)) {
				$out .= $nbsp . $token;
			} else {
				$out .= $token;
			}
		}
		return $out;
	}

	function bxt_rankings_message_wrap_word_limit($game_region) {
		return (strtolower((string)$game_region) === "j") ? 3 : 2;
	}

	function bxt_rankings_decode_message_tokens($game_region, $raw_message_binary) {
		if (!is_string($raw_message_binary) || $raw_message_binary === "") {
			return [];
		}

		if (!function_exists('bxt_load_encoding_json')) {
			return [];
		}

		$cfg = bxt_load_encoding_json();
		if (!is_array($cfg)) {
			return [];
		}

		$table_id = bxt_rankings_easy_chat_table_id($game_region);
		if (!isset($cfg[$table_id]) || !is_array($cfg[$table_id])) {
			return [];
		}

		$table = $cfg[$table_id];
		$hex = strtoupper(bin2hex($raw_message_binary));
		$tokens = [];

		for ($i = 0; $i + 3 < strlen($hex); $i += 4) {
			$code = substr($hex, $i, 4);
			if (isset($table[$code])) {
				$tokens[] = trim((string)$table[$code]);
			} elseif ($code !== '0000') {
				$tokens[] = $code;
			}
		}

		$tokens = array_values(array_filter($tokens, function ($token) {
			return $token !== "";
		}));

		return $tokens;
	}

	function bxt_rankings_format_message_tokens($tokens, $game_region) {
		if (!is_array($tokens) || count($tokens) === 0) {
			return "";
		}

		$line_one_word_limit = bxt_rankings_message_wrap_word_limit($game_region);
		$line_one_tokens = [];
		$line_two_tokens = [];
		$word_count = 0;

		foreach ($tokens as $token) {
			if ($word_count < $line_one_word_limit) {
				$line_one_tokens[] = $token;
				if (bxt_rankings_token_has_word($token)) {
					$word_count++;
				}
			} else {
				$line_two_tokens[] = $token;
			}
		}

		$line_one = bxt_rankings_tokens_to_text($line_one_tokens);
		$line_two = bxt_rankings_tokens_to_text($line_two_tokens);

		if ($line_two === "") {
			return $line_one;
		}

		return $line_one . "\n" . $line_two;
	}

	function bxt_rankings_player_region_table_id($region) {
		switch (strtolower((string)$region)) {
			case 'e':
				return 'btxe_player_region';
			case 'p':
				return 'btxp_player_region';
			case 'u':
				return 'btxu_player_region';
			case 'j':
				return 'btxj_player_region';
			case 'f':
				return 'btxf_player_region';
			case 'd':
				return 'btxd_player_region';
			case 's':
				return 'btxs_player_region';
			case 'i':
				return 'btxi_player_region';
			default:
				return '';
		}
	}

	function bxt_rankings_address_options($country_code) {
		if ($country_code === "global") {
			return [];
		}

		$table_id = bxt_rankings_player_region_table_id($country_code);
		if ($table_id === "") {
			return [];
		}

		$cfg = bxt_load_encoding_json();
		if (!isset($cfg[$table_id]) || !is_array($cfg[$table_id])) {
			return [];
		}

		$addresses = [];
		foreach ($cfg[$table_id] as $value) {
			$address = strtoupper(trim((string)$value));
			if ($address !== "" && !isset($addresses[$address])) {
				$addresses[$address] = $address;
			}
		}
		return array_values($addresses);
	}

	function bxt_rankings_address_whitelist($country_code) {
		$options = bxt_rankings_address_options($country_code);
		$whitelist = [];
		foreach ($options as $address) {
			$address_key = strtoupper(trim((string)$address));
			if ($address_key !== "") {
				$whitelist[$address_key] = true;
			}
		}
		return $whitelist;
	}

	function bxt_rankings_sort_address_mode(&$rows) {
		usort($rows, function ($a, $b) {
			$address_cmp = strcasecmp((string)$a["address"], (string)$b["address"]);
			if ($address_cmp !== 0) {
				return $address_cmp;
			}

			$score_a = intval($a["score"]);
			$score_b = intval($b["score"]);
			if ($score_a !== $score_b) {
				return ($score_b <=> $score_a);
			}

			$time_a = strtotime((string)($a["timestamp"] ?? ""));
			$time_b = strtotime((string)($b["timestamp"] ?? ""));
			if ($time_a !== $time_b) {
				return $time_a <=> $time_b;
			}

			return intval($a["_source_index"] ?? 0) <=> intval($b["_source_index"] ?? 0);
		});
	}

	$country_options = array(
		array("value" => "global", "label" => "Global"),
		array("value" => "j", "label" => "JPN: Japan"),
		array("value" => "e", "label" => "ENG: USA & Canada"),
		array("value" => "p", "label" => "ENG: Europe"),
		array("value" => "u", "label" => "ENG: Australia"),
		array("value" => "f", "label" => "FRA: France"),
		array("value" => "d", "label" => "GER: Germany"),
		array("value" => "i", "label" => "ITA: Italy"),
		array("value" => "s", "label" => "SPA: Spain"),
	);

	$int_countries = array("e", "f", "d", "i", "s", "u", "p");
	$valid_countries = array_merge($int_countries, ["j"]);

	$country = strtolower((string)($_GET["country"] ?? 'global'));
	if ($country !== "global" && !in_array($country, $valid_countries, true)) {
		$country = "global";
	}

	$view_mode = strtolower((string)($_GET["mode"] ?? "national"));
	if (!in_array($view_mode, array("national", "address"), true)) {
		$view_mode = "national";
	}
	if ($country === "global") {
		$view_mode = "national";
	}

	$mode_options = array(
		array("value" => "national", "label" => "National Top 10!"),
	);
	if ($country !== "global") {
		$mode_options[] = array("value" => "address", "label" => "Address Top 10!");
	}

	$address_options = ($country !== "global") ? bxt_rankings_address_options($country) : [];
	$selected_address = strtoupper(trim((string)($_GET["address"] ?? "")));
	if ($country === "global" || $view_mode !== "address") {
		$selected_address = "";
	} elseif (count($address_options) > 0) {
		if ($selected_address === "" || !in_array($selected_address, $address_options, true)) {
			$selected_address = strtoupper((string)$address_options[0]);
		}
	} else {
		$selected_address = "";
	}

	// Optional: force custom Pokemon News rankings preview in the web UI.
	$force_pokemon_news_custom = (($_GET["pokemon_news_custom"] ?? '') === '1');
	if ($country === "global") {
		$force_pokemon_news_custom = false;
	}

	$pkm_util = PokemonUtil::getInstance();
	$db_util = DBUtil::getInstance();
	$db = $db_util->getDB();
	$session_util = SessionUtil::getInstance();

	$ranking_columns = bxt_rankings_table_columns($db, "bxt_ranking");
	$news_columns = bxt_rankings_table_columns($db, "bxt_news");
	$regions = ($country === "global") ? $valid_countries : [$country];
	$region_sql_list = join(", ", array_map(function ($c) {
		return "'" . $c . "'";
	}, $regions));

	// Determine whether we should use the custom Pokemon News track for this viewer.
	$custom_opt_in = 0;
	$user_wants_custom = $force_pokemon_news_custom;
	if ($country === "global") {
		$user_wants_custom = false;
	}

	if (!$user_wants_custom && $session_util->isSessionActive() && isset($_SESSION["user_id"])) {
		$user_id = intval($_SESSION["user_id"]);
		$stmt = $db->prepare("select custom_pokemon_news_opt_in from sys_users where id = ? limit 1");
		if ($stmt) {
			$stmt->bind_param("i", $user_id);
			$stmt->execute();
			$rows = DBUtil::fancy_get_result($stmt);
			if (count($rows) > 0) {
				$custom_opt_in = intval($rows[0]["custom_pokemon_news_opt_in"]);
				$user_wants_custom = ($custom_opt_in === 1);
			}
		}
	}

	function fetchLatestNewsRow($db, $region_sql_list, $is_custom) {
		$stmt = $db->prepare(
			"select id, is_custom, game_region, ranking_category_1, ranking_category_2, ranking_category_3
			 from bxt_news
			 where is_custom = ? and game_region in (" . $region_sql_list . ")
			 order by id desc limit 1"
		);
		if (!$stmt) {
			return null;
		}
		$stmt->bind_param("i", $is_custom);
		$stmt->execute();
		$rows = DBUtil::fancy_get_result($stmt);
		return (count($rows) > 0) ? $rows[0] : null;
	}

	$custom_row = null;
	$selected_row = null;

	if ($user_wants_custom) {
		$custom_row = fetchLatestNewsRow($db, $region_sql_list, 1);
		if ($custom_row !== null) {
			$selected_row = $custom_row;
		} else {
			$selected_row = fetchLatestNewsRow($db, $region_sql_list, 0);
		}
	} else {
		$selected_row = fetchLatestNewsRow($db, $region_sql_list, 0);
	}

	$pokemon_news_custom_available = ($custom_row !== null) ? 1 : 0;
	$pokemon_news_custom_used = ($selected_row !== null && intval($selected_row["is_custom"]) === 1) ? 1 : 0;

	if ($selected_row === null) {
		echo TemplateUtil::render("/pokemon/rankings", [
			'categories' => [],
			'selected_country' => $country,
			'selected_mode' => $view_mode,
			'selected_address' => $selected_address,
			'country_options' => $country_options,
			'mode_options' => $mode_options,
			'address_options' => $address_options,
			'pokemon_news_custom' => 0,
			'pokemon_news_custom_available' => $pokemon_news_custom_available,
		]);
		exit;
	}

	// Canonicalize ranking news_id to vanilla when categories are identical (prefer vanilla).
	$canonical_news_id = intval($selected_row["id"]);

	if ($pokemon_news_custom_used === 1) {
		$stmt = $db->prepare(
			"select id, ranking_category_1, ranking_category_2, ranking_category_3
			 from bxt_news
			 where is_custom = 0 and game_region = ?
			 order by id desc limit 1"
		);
		if ($stmt) {
			$gr = $selected_row["game_region"];
			$stmt->bind_param("s", $gr);
			$stmt->execute();
			$rows = DBUtil::fancy_get_result($stmt);
			if (count($rows) > 0) {
				$vanilla = $rows[0];
				if (
					intval($vanilla["ranking_category_1"]) === intval($selected_row["ranking_category_1"]) &&
					intval($vanilla["ranking_category_2"]) === intval($selected_row["ranking_category_2"]) &&
					intval($vanilla["ranking_category_3"]) === intval($selected_row["ranking_category_3"])
				) {
					$canonical_news_id = intval($vanilla["id"]);
				}
			}
		}
	}

	$category_ids = array(
		intval($selected_row["ranking_category_1"] ?? 0),
		intval($selected_row["ranking_category_2"] ?? 0),
		intval($selected_row["ranking_category_3"] ?? 0),
	);

	$category_name_by_id = array();
	$stmt = $db->prepare(
		"select id, name
		 from bxt_ranking_categories
		 where id in (?, ?, ?)"
	);
	if ($stmt) {
		$rc1 = $category_ids[0];
		$rc2 = $category_ids[1];
		$rc3 = $category_ids[2];
		$stmt->bind_param("iii", $rc1, $rc2, $rc3);
		$stmt->execute();
		$cat_rows = DBUtil::fancy_get_result($stmt);
		foreach ($cat_rows as $cat_row) {
			$category_name_by_id[intval($cat_row["id"])] = (string)$cat_row["name"];
		}
	}

	$category_name_region = ($country === "global")
		? strtolower((string)($selected_row["game_region"] ?? "e"))
		: $country;

	$ranking_select_name_decode = bxt_rankings_has_column($ranking_columns, "player_name_decode")
		? "r.player_name_decode"
		: "'' AS player_name_decode";
	$ranking_select_region_decode = bxt_rankings_has_column($ranking_columns, "player_region_decode")
		? "r.player_region_decode"
		: "'' AS player_region_decode";
	$ranking_select_message = bxt_rankings_has_column($ranking_columns, "player_message")
		? "r.player_message"
		: "NULL AS player_message";
	$ranking_select_message_decode = bxt_rankings_has_column($ranking_columns, "player_message_decode")
		? "r.player_message_decode"
		: "'' AS player_message_decode";
	$ranking_timestamp_column = bxt_rankings_has_column($ranking_columns, "timestamp")
		? "timestamp"
		: (bxt_rankings_has_column($ranking_columns, "entry_time") ? "entry_time" : null);
	$ranking_select_timestamp = $ranking_timestamp_column !== null
		? ("r.`" . str_replace("`", "``", $ranking_timestamp_column) . "` AS timestamp")
		: "NOW() AS timestamp";
	$ranking_order_timestamp = $ranking_timestamp_column !== null
		? ("r.`" . str_replace("`", "``", $ranking_timestamp_column) . "` ASC")
		: "r.trainer_id ASC";
	$has_ranking_news_id = bxt_rankings_has_column($ranking_columns, "news_id");
	$has_news_id = bxt_rankings_has_column($news_columns, "id");
	$has_news_is_custom = bxt_rankings_has_column($news_columns, "is_custom");
	$has_news_game_region = bxt_rankings_has_column($news_columns, "game_region");
	$can_join_news = $has_ranking_news_id && $has_news_id;

	$rankings = array();
	$max_rows = 200;
	$address_whitelist = ($view_mode === "address") ? bxt_rankings_address_whitelist($country) : [];

	for ($i = 0; $i < 3; $i++) {
		$cat_id = intval($category_ids[$i] ?? 0);
		$category_name = "";

		if (function_exists('bxt_decode_ranking_category')) {
			$category_name = trim((string)bxt_decode_ranking_category($category_name_region, $cat_id));
		}
		if ($category_name === "" && isset($category_name_by_id[$cat_id])) {
			$category_name = (string)$category_name_by_id[$cat_id];
		}
		if ($category_name === "") {
			$category_name = "CATEGORY " . $cat_id;
		}

		$from_clause = " from bxt_ranking r ";
		if ($can_join_news) {
			$from_clause .= "inner join bxt_news n on n.id = r.news_id ";
		}

		$where_parts = array();
		if ($country !== "global") {
			$where_parts[] = "r.game_region = ?";
		} else {
			$where_parts[] = "r.game_region in (" . $region_sql_list . ")";
		}
		$where_parts[] = "r.category_id = ?";
		$where_parts[] = "r.score > 0";
		if ($can_join_news && $has_news_is_custom) {
			$where_parts[] = "n.is_custom = 0";
		}
		if ($can_join_news && $has_news_game_region) {
			$where_parts[] = "n.game_region = r.game_region";
		}

		$query =
			"select
				r.game_region,
				r.account_id,
				r.trainer_id,
				r.secret_id,
				r.player_name,
				" . $ranking_select_name_decode . ",
				r.player_region,
				" . $ranking_select_region_decode . ",
				r.player_zip,
				" . $ranking_select_message . ",
				" . $ranking_select_message_decode . ",
				r.score,
				" . $ranking_select_timestamp . " " .
			$from_clause .
			"where " . implode(" and ", $where_parts) . " " .
			"order by r.score desc, " . $ranking_order_timestamp . " " .
			"limit " . $max_rows;

		$stmt = $db->prepare($query);
		if ($stmt) {
			if ($country !== "global") {
				$country_bind = $country;
				$stmt->bind_param("si", $country_bind, $cat_id);
			} else {
				$stmt->bind_param("i", $cat_id);
			}
		}

		$rows = [];
		if ($stmt) {
			$stmt->execute();
			$data = DBUtil::fancy_get_result($stmt);

			$source_index = 0;
			foreach ($data as $entry) {
				$pc = strtolower((string)($entry["game_region"] ?? "e"));
				$player_name = trim((string)($entry["player_name_decode"] ?? ""));
				if ($player_name === "" && isset($entry["player_name"])) {
					$player_name = trim((string)$pkm_util->getString($pc, $entry["player_name"]));
				}
				if ($player_name === "") {
					$player_name = "UNKNOWN";
				}

				$address = trim((string)($entry["player_region_decode"] ?? ""));
				if ($address === "" && function_exists('bxt_decode_player_region')) {
					$address = trim((string)bxt_decode_player_region($pc, intval($entry["player_region"] ?? 0)));
				}
				if ($address === "") {
					$address = trim((string)$pkm_util->getSubregion($pc, $entry["player_region"] ?? 0));
				}
				if ($address === "") {
					$address = "---";
				}

				$message_tokens = bxt_rankings_decode_message_tokens($pc, $entry["player_message"] ?? null);
				$message = bxt_rankings_format_message_tokens($message_tokens, $pc);
				if ($message === "") {
					$message = trim((string)($entry["player_message_decode"] ?? ""));
					$message = preg_replace('/\s+/u', ' ', $message);
				}
				if ($message === "") {
					$message = "...";
				}

				$rows[] = array(
					"rank" => 0,
					"name" => $player_name,
					"address" => $address,
					"message" => $message,
					"score" => intval($entry["score"] ?? 0),
					"timestamp" => (string)($entry["timestamp"] ?? ""),
					"_source_index" => $source_index,
				);
				$source_index++;
			}
		}

		if ($view_mode === "address" && $country !== "global") {
			if (count($address_whitelist) > 0) {
				$rows = array_values(array_filter($rows, function ($row) use ($address_whitelist) {
					$address = strtoupper(trim((string)($row["address"] ?? "")));
					return ($address !== "" && isset($address_whitelist[$address]));
				}));
			}

			if ($selected_address !== "") {
				$rows = array_values(array_filter($rows, function ($row) use ($selected_address) {
					$address = strtoupper(trim((string)($row["address"] ?? "")));
					return ($address === $selected_address);
				}));
			}
			bxt_rankings_sort_address_mode($rows);
		}

		for ($rank_index = 0; $rank_index < count($rows); $rank_index++) {
			$rows[$rank_index]["rank"] = $rank_index + 1;
			unset($rows[$rank_index]["_source_index"]);
		}

		$rankings[] = array(
			"id" => $cat_id,
			"name" => $category_name,
			"entries" => $rows,
		);
	}

	echo TemplateUtil::render("/pokemon/rankings", [
		'categories' => $rankings,
		'selected_country' => $country,
		'selected_mode' => $view_mode,
		'selected_address' => $selected_address,
		'country_options' => $country_options,
		'mode_options' => $mode_options,
		'address_options' => $address_options,
		'pokemon_news_custom' => $pokemon_news_custom_used,
		'pokemon_news_custom_available' => $pokemon_news_custom_available,
	]);
