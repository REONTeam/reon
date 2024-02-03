<?php
	require_once("../classes/SessionUtil.php");
	require_once("../classes/UserUtil.php");
	session_start();
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["id"]) && isset($_POST["key"]) && isset($_POST["password"]) && isset($_POST["passwordConfirm"])) {
			$result = UserUtil::getInstance()->resetPassword($_POST["id"], $_POST["key"], $_POST["password"], $_POST["passwordConfirm"]);
			echo TemplateUtil::render("reset_password", [
				"result" => $result,
				"id" => $_POST["id"],
				"key" => $_POST["key"]
			]);
		} else {
			http_response_code(400);
		}
	} else {
		if (isset($_GET["id"]) && isset($_GET["key"])) {
			$result = UserUtil::getInstance()->verifyResetPassword($_GET["id"], $_GET["key"]);
			echo TemplateUtil::render("reset_password", [
				"result" => $result,
				"id" => $_GET["id"],
				"key" => $_GET["key"]
			]);
		} else {
			echo TemplateUtil::render("reset_password", [
				"result" => 1
			]);
		}
	}