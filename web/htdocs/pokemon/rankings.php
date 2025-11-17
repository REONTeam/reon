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
    $pkm_util = PokemonUtil::getInstance();

    $db_util = DBUtil::getInstance();
    
    $db = $db_util->getDB();
    $stmt = $db->prepare("select n.id news_id, c1.id category_1_id, c1.name category_1_name, c2.id category_2_id, c2.name category_2_name, c3.id category_3_id, c3.name category_3_name
        from bxt_news n
        inner join bxt_ranking_categories c1 on n.ranking_category_1  = c1.id
        inner join bxt_ranking_categories c2 on n.ranking_category_2  = c2.id
        inner join bxt_ranking_categories c3 on n.ranking_category_3  = c3.id
        order by n.id desc limit 1");
    $stmt->execute();
    $categories = DBUtil::fancy_get_result($stmt)[0];

    $rankings = array();

    for ($i=1; $i <= 3; $i++) {
        $max_rows = 20;
        if ($country != "global") {
            $stmt = $db->prepare("select game_region, account_id, trainer_id, player_name, player_region, player_zip, score, timestamp from bxt_ranking where ".
            " game_region = '".$country."' and news_id = ? and category_id = ? and score > 0 order by score desc, timestamp asc limit ".$max_rows);
        } else {
            $stmt = $db->prepare("select game_region, news_id, category_id, account_id, trainer_id, player_name, player_region, player_zip, score, timestamp from bxt_ranking where game_region in ( ".
                join(", ", array_map(fn($c): string => "'".$c."'", $int_countries))." ) and news_id = ? and category_id = ? order by score desc, timestamp asc limit ".$max_rows);
        }
        $stmt->bind_param("ii",$categories["news_id"], $categories["category_".$i."_id"]);
        $stmt->execute();
        $data = DBUtil::fancy_get_result($stmt);
        foreach ($data as &$entry) {
            $pc = $entry['game_region'];
            $entry["player_name"] = $pkm_util->getString($pc, $entry['player_name']);
            $entry["player_zip"] = $pc == "j" ? $entry['player_zip'] : $pkm_util->getString($pc, $entry['player_zip']);
            $entry["player_region"] = $pkm_util->getSubregion($pc, $entry['player_region']);
        }
        array_push($rankings, ["id" => $categories["category_".$i."_id"], "name" => $categories["category_".$i."_name"], "entries" => $data]);
    }
    echo TemplateUtil::render("/pokemon/rankings", [
        'categories' => $rankings
    ]);
	