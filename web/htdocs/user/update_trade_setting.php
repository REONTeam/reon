<?php
	require_once("../../classes/SessionUtil.php");
	require_once("../../classes/DBUtil.php");
	session_start();
	
	if (SessionUtil::getInstance()->isSessionActive()) {
        $db_util = DBUtil::getInstance();
        
        $db = DBUtil::getInstance()->getDB();
        $stmt = $db->prepare("update sys_users set trade_region_allowlist = ? where id = ?");
        $stmt->bind_param("si", $_POST["tradeRegions"], $_SESSION["user_id"]);
        $stmt->execute();
        
		header("Location: /user/summary.php");
	} else {
		header("Location: /index.php");
	}
