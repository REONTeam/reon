<?php
	require_once("../../classes/TemplateUtil.php");
	require_once("../../classes/DBUtil.php");
	require_once("../../classes/SessionUtil.php");
	session_start();
	
	if (SessionUtil::getInstance()->isSessionActive()) {
		$db_util = DBUtil::getInstance();
		
		$db = $db_util->getDB();
		$stmt = $db->prepare("select email, dion_ppp_id, dion_email_local, log_in_password, money_spent, trade_region_allowlist from sys_users where id = ?");
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->execute();
		$result = DBUtil::fancy_get_result($stmt)[0];
		
		$db = $db_util->getDB();
		$stmt = $db->prepare("select count(*) from sys_inbox where recipient = ?");
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->execute();
		$inbox_size = DBUtil::fancy_get_result($stmt)[0]["count(*)"];
		
		echo TemplateUtil::render("/user/summary", [
			"email" => $result["email"],
			"dion_ppp_id" => $result["dion_ppp_id"],
			"dion_email" => $result["dion_email_local"]."@".ConfigUtil::getInstance()->getConfig()["email_domain_dion"],
			"log_in_password" => $result["log_in_password"],
			"money_spent" => $result["money_spent"],
            "trade_region_allowlist" => $result["trade_region_allowlist"],
			"inbox_size" => $inbox_size
		]);
	} else {
		header("Location: /index.php");
	}
