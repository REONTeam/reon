<?php
	include("../classes/DBUtil.php");
	
	$db = DBUtil::getInstance()->getDB();
	$stmt = $db->prepare("select id, log_in_password, dion_ppp_id from sys_users where email = '' and password = ''");
	$stmt->execute();
	$result = DBUtil::fancy_get_result($stmt);
	for ($i = 0; $i < sizeof($result); $i++) {
		$email = $result[$i]["dion_ppp_id"]."@example.net";
		$pwd = password_hash($result[$i]["log_in_password"], PASSWORD_DEFAULT);
		$stmt = $db->prepare("update sys_users set password = ?, email = ? where id = ?");
		$stmt->bind_param("ssi", $pwd, $email, $result[$i]["id"]);
		$stmt->execute();
	}
	echo "ok";