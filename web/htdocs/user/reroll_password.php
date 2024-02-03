<?php
	require_once("../../classes/SessionUtil.php");
	require_once("../../classes/UserUtil.php");
	session_start();
	
	if (SessionUtil::getInstance()->isSessionActive()) {
		$result = UserUtil::getInstance()->rerollLoginPassword();
		echo TemplateUtil::render("/user/reroll_password");
	} else {
		header("Location: /index.php");
	}