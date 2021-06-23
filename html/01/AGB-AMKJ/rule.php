<?php
	// Mobile GP rules download

	require_once(CORE_PATH."/database.php");

	$db = connectMySQL();
	$stmt = $db->prepare("select * from amkj_rule order by id desc limit 1;");
	$stmt->execute();
	$result = fancy_get_result($stmt);
	
	if (sizeof($result) > 0) {
		$data = $result[0]["file_name"]."\r\n"
		.str_replace("-", "", $result[0]["start_date"])."\r\n"
		.str_replace("-", "", $result[0]["end_date"])."\r\n"
		.str_replace("-", "", $result[0]["next_start_date"])."\r\n"
		.str_replace("-", "", $result[0]["next_end_date"])."\r\n"
		.str_replace("-", "", $result[0]["entry_start_date"])."\r\n"
		.str_replace("-", "", $result[0]["entry_end_date"])."\r\n"
		.str_replace("-", "", $result[0]["ranking_start_date"])."\r\n"
		.str_replace("-", "", $result[0]["ranking_end_date"])."\r\n"
		.$result[0]["coins_enabled"]
		.$result[0]["items_enabled"]
		.$result[0]["start_item_triple_shroom_enabled"]
		.$result[0]["shrooms_only_enabled"]
		.$result[0]["cpu_enabled"]
		.$result[0]["character"]
		.$result[0]["start_coins"]
		.$result[0]["five_laps_enabled"]
		."00"
		.sprintf('%02d', $result[0]["course"])
		."00"
		.sprintf('%02d', $result[0]["num_attempts"])
		."00"
		."00";
		
		echo $data;
	}
?>