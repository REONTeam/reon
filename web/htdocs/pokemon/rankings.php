<?php
	require_once("../../classes/TemplateUtil.php");
	require_once("../../classes/DBUtil.php");
	require_once("../../classes/SessionUtil.php");
	require_once("../../classes/PokemonUtil.php");
	session_start();

	$int_countries = array("e", "f", "d", "i", "s", "u", "p");
	$valid_countries = array_merge($int_countries, ["j"]);

	$country = strtolower($_GET["country"] ?? '');
	$country = in_array($country, $valid_countries) ? $country : "global";

	// Optional: force custom Pokémon News rankings preview in the web UI.
	$force_pokemon_news_custom = (($_GET["pokemon_news_custom"] ?? '') === '1');

	$pkm_util = PokemonUtil::getInstance();
	$db_util = DBUtil::getInstance();
	$db = $db_util->getDB();
	$session_util = SessionUtil::getInstance();

	$regions = ($country === "global") ? $valid_countries : [$country];
	$region_sql_list = join(", ", array_map(function($c) { return "'" . $c . "'"; }, $regions));

	// Determine whether we should use the custom Pokémon News track for this viewer.
	$custom_opt_in = 0;
	$user_wants_custom = $force_pokemon_news_custom;

	if (!$user_wants_custom && $session_util->isSessionActive() && isset($_SESSION["user_id"])) {
		$user_id = intval($_SESSION["user_id"]);
		$stmt = $db->prepare("select custom_pokemon_news_opt_in from sys_users where id = ? limit 1");
		$stmt->bind_param("i", $user_id);
		$stmt->execute();
		$rows = DBUtil::fancy_get_result($stmt);
		if (count($rows) > 0) {
			$custom_opt_in = intval($rows[0]["custom_pokemon_news_opt_in"]);
			$user_wants_custom = ($custom_opt_in === 1);
		}
	}

	function fetchLatestNewsRow($db, $region_sql_list, $is_custom) {
		$stmt = $db->prepare(
			"select id, is_custom, game_region, ranking_category_1, ranking_category_2, ranking_category_3
			 from bxt_news
			 where is_custom = ? and game_region in (" . $region_sql_list . ")
			 order by id desc limit 1"
		);
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

	if ($selected_row === null) {
		echo TemplateUtil::render("/pokemon/rankings", [
			'categories' => [],
			'selected_country' => $country,
			'pokemon_news_custom' => 0,
			'pokemon_news_custom_available' => 0,
		]);
		exit;
	}

	// Canonicalize ranking news_id to vanilla when categories are identical (prefer vanilla).
	$canonical_news_id = intval($selected_row["id"]);
	$pokemon_news_custom_available = ($custom_row !== null) ? 1 : 0;
	$pokemon_news_custom_used = intval($selected_row["is_custom"]) === 1 ? 1 : 0;

	if ($pokemon_news_custom_used === 1) {
		$stmt = $db->prepare(
			"select id, ranking_category_1, ranking_category_2, ranking_category_3
			 from bxt_news
			 where is_custom = 0 and game_region = ?
			 order by id desc limit 1"
		);
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

	// Resolve category names for the selected news row.
	$stmt = $db->prepare(
		"select
			c1.id as category_1_id, c1.name as category_1_name,
			c2.id as category_2_id, c2.name as category_2_name,
			c3.id as category_3_id, c3.name as category_3_name
		 from bxt_ranking_categories c1, bxt_ranking_categories c2, bxt_ranking_categories c3
		 where c1.id = ? and c2.id = ? and c3.id = ?
		 limit 1"
	);
	$rc1 = intval($selected_row["ranking_category_1"]);
	$rc2 = intval($selected_row["ranking_category_2"]);
	$rc3 = intval($selected_row["ranking_category_3"]);
	$stmt->bind_param("iii", $rc1, $rc2, $rc3);
	$stmt->execute();
	$cat_rows = DBUtil::fancy_get_result($stmt);

	if (count($cat_rows) === 0) {
		echo TemplateUtil::render("/pokemon/rankings", [
			'categories' => [],
			'selected_country' => $country,
			'pokemon_news_custom' => $pokemon_news_custom_used,
			'pokemon_news_custom_available' => $pokemon_news_custom_available,
		]);
		exit;
	}

	$categories = $cat_rows[0];
	$categories["news_id"] = $canonical_news_id;

	$rankings = array();

	for ($i = 1; $i <= 3; $i++) {
		$max_rows = 20;

		if ($country !== "global") {
			$stmt = $db->prepare(
				"select game_region, account_id, trainer_id, secret_id, player_name, player_region, player_zip, score, timestamp
				 from bxt_ranking
				 where game_region = ? and news_id = ? and category_id = ? and score > 0
				 order by score desc, timestamp asc
				 limit " . $max_rows
			);
						$news_id = intval($categories["news_id"]);
			$cat_id = intval($categories["category_" . $i . "_id"]);
			$stmt->bind_param("sii", $country, $news_id, $cat_id);
		} else {
			$stmt = $db->prepare(
				"select game_region, account_id, trainer_id, secret_id, player_name, player_region, player_zip, score, timestamp
				 from bxt_ranking
				 where game_region in (" . $region_sql_list . ") and news_id = ? and category_id = ? and score > 0
				 order by score desc, timestamp asc
				 limit " . $max_rows
			);
						$news_id = intval($categories["news_id"]);
			$cat_id = intval($categories["category_" . $i . "_id"]);
			$stmt->bind_param("ii", $news_id, $cat_id);
		}

		$stmt->execute();
		$data = DBUtil::fancy_get_result($stmt);

		foreach ($data as &$entry) {
			$pc = $entry["game_region"];
			$entry["player_name"] = $pkm_util->getString($pc, $entry["player_name"]);
			$entry["player_zip"] = ($pc == "j") ? $entry["player_zip"] : $pkm_util->getString($pc, $entry["player_zip"]);
			$entry["player_region"] = $pkm_util->getSubregion($pc, $entry["player_region"]);
		}

		array_push($rankings, [
			"id" => $categories["category_" . $i . "_id"],
			"name" => $categories["category_" . $i . "_name"],
			"entries" => $data,
		]);
	}

	echo TemplateUtil::render("/pokemon/rankings", [
		'categories' => $rankings,
		'selected_country' => $country,
		'pokemon_news_custom' => $pokemon_news_custom_used,
		'pokemon_news_custom_available' => $pokemon_news_custom_available,
	]);
