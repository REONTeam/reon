<?php
	require_once("../classes/SessionUtil.php");
	require_once("../classes/UserUtil.php");
	session_start();
	
	if (isset($_GET["id"]) && isset($_GET["key"])) {
		$result = UserUtil::getInstance()->confirmEmailChangeAction($_GET["id"], $_GET["key"]);
		echo TemplateUtil::render("confirm_email", [
			"result" => $result
		]);
	} else {
		echo TemplateUtil::render("confirm_email", [
			"result" => 1
		]);
	}