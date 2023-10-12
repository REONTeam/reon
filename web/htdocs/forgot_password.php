<?php
	require_once("../classes/SessionUtil.php");
	require_once("../classes/UserUtil.php");
	session_start();
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!isset($_POST["email"])) return;
		$result = UserUtil::getInstance()->sendPasswordResetEmail($_POST["email"]);
		$result = 0;
		echo TemplateUtil::render("forgot_password", [
			"result" => $result,
			"email" => $_POST["email"]
		]);
	} else {
		echo TemplateUtil::render("forgot_password", [
			"result" => -1
		]);
	}