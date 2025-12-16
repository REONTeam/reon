<?php
	require_once("../../classes/TemplateUtil.php");
	require_once("../../classes/DBUtil.php");
	require_once("../../classes/SessionUtil.php");
	session_start();
	
	if (SessionUtil::getInstance()->isSessionActive()) {
		$db_util = DBUtil::getInstance();
  
        $errors = array(); //~To contain multiple problems at once
        //~Update user settings if needed, before preparing to render the page
        if (array_key_exists("tradeRegions",$_POST)) {
            if (
                in_array($_POST["tradeRegions"],array("e,f,d,s,i,p,u,j","efdsipu,j","efdsipuj"))
            ) {
                $db = DBUtil::getInstance()->getDB();
                $stmt = $db->prepare("update sys_users set trade_region_allowlist = ? where id = ?");
                $stmt->bind_param("si", $_POST["tradeRegions"], $_SESSION["user_id"]);
                $stmt->execute();
            } else { //~If region setting is invalid, make no changes and issue an error
                $errors[] = "regionValue";
            }
        }
		
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
			"inbox_size" => $inbox_size,
            "errors" => $errors
		]);
	} else {
		header("Location: /index.php");
	}
