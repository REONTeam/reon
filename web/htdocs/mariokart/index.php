<?php
	require_once("../../classes/TemplateUtil.php");
	require_once("../../classes/DBUtil.php");
	require_once("../../classes/SessionUtil.php");
	require_once("../../classes/MarioKartUtil.php");
	session_start();
	
    $mk_util = MarioKartUtil::getInstance();

    $db_util = DBUtil::getInstance();
    
	// TODO: Add region checks when we support more than Japanese

    $db = $db_util->getDB();

    $tracks = array();

    $stmt = $db->prepare(
		"SELECT a.id, a.player_id, a.course_no, a.driver, a.name, a.state, a.unk18, a.course, a.`time`, ".
		"a.input_data, a.full_name, a.phone_number, a.postal_code, a.address, a.`timestamp` ".
		"FROM amk_ghosts a ".
		"LEFT OUTER JOIN amk_ghosts b ".
		"	ON a.course = b.course AND a.time > b.time ".
		"WHERE b.id IS NULL ".
		"ORDER BY a.course ASC, a.`timestamp` ASC; ");
    
    $stmt->execute();
    $data = DBUtil::fancy_get_result($stmt);
    foreach ($data as &$entry) {
        $entry["player_name"] = $mk_util->getString('j', $entry['name']);
		$entry["time"] = $time = $mk_util->convertTime($entry['time']);
		$entry["formatted_time"] = sprintf('%d\'%02d"%02d', $time->i, $time->s, round($time->f * 100));
		$tracks[$entry["course"]] = $entry;
	}
	echo TemplateUtil::render("mariokart/index", [
        'ghosts' => $tracks
	]);