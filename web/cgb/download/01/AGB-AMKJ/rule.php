<?php
	// Mobile GP rules download

	require_once(CORE_PATH."/database.php");

	$db = connectMySQL();
	
	$game_region = getCurrentGameRegion();
	if ($game_region === null) {
		http_response_code(500);
		return;
	}
$stmt = $db->prepare("select * from amk_rule where game_region = ? order by id desc limit 1;");
	$stmt->bind_param("s", $game_region);
	$stmt->execute();
	$result = fancy_get_result($stmt);
	
	if (sizeof($result) > 0) {
		if ($result[0]["message"] == null) {
			$messageLength = 0;
		} else {
			$messageLength = substr_count($data = $result[0]["message"], "\r\n") + 1;
		}
		
		$data = $result[0]["file_name"]."\r\n"
		.str_replace("-", "", $result[0]["start_date"])."\r\n"
		.str_replace("-", "", $result[0]["end_date"])."\r\n"
		.str_replace("-", "", $result[0]["next_start_date"])."\r\n"
		.str_replace("-", "", $result[0]["next_end_date"])."\r\n"
		.str_replace("-", "", $result[0]["entry_start_date"])."\r\n"
		.str_replace("-", "", $result[0]["entry_end_date"])."\r\n"
		.str_replace("-", "", $result[0]["ranking_start_date"])."\r\n"
		.str_replace("-", "", $result[0]["ranking_end_date"])."\r\n"
		.dechex($result[0]["coins_enabled"])
		.dechex($result[0]["items_enabled"])
		.dechex($result[0]["start_item_triple_shroom_enabled"])
		.dechex($result[0]["shrooms_only_enabled"])
		.dechex($result[0]["cpu_enabled"])
		.dechex($result[0]["character"])
		.dechex($result[0]["start_coins"])
		.dechex($result[0]["five_laps_enabled"])
		."\r\n"
		.sprintf('%02d', $result[0]["course"])
		."\r\n"
		.sprintf('%02d', $result[0]["num_attempts"])
		."\r\n"
		.sprintf('%02d', $messageLength)
		."\r\n"
		.mb_convert_encoding($result[0]["message"], "SJIS", "UTF-8");
		
		echo $data;
	}
?>