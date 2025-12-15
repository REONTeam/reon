<?php
	require_once("../../classes/SessionUtil.php");
	require_once("../../classes/DBUtil.php");
	session_start();
	
	if (SessionUtil::getInstance()->isSessionActive()) {
        $db_util = DBUtil::getInstance();
        
        $tradeRegions = $_POST["tradeRegions"];
        if (!in_array($tradeRegions,array("e,f,d,s,i,p,u,j","efdsipu,j","efdsipuj")))
            $tradeRegions = "e,f,d,s,i,p,u,j";
        
        $db = DBUtil::getInstance()->getDB();
        $stmt = $db->prepare("update sys_users set trade_region_allowlist = ? where id = ?");
        $stmt->bind_param("si", $tradeRegions, $_SESSION["user_id"]);
        $stmt->execute();
        
		header("Location: /user/summary.php");
	} else {
		header("Location: /index.php");
	}
