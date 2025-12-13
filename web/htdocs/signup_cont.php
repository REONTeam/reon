<?php
	require_once("../classes/SessionUtil.php");
	require_once("../classes/UserUtil.php");
	session_start();
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["id"]) && isset($_POST["key"]) && isset($_POST["reonEmail"]) && isset($_POST["password"]) && isset($_POST["passwordConfirm"]) && isset($_POST["tradeRegions"])) {
			$result = UserUtil::getInstance()->completeSignupAction($_POST["id"], $_POST["key"], $_POST["reonEmail"], $_POST["password"], $_POST["passwordConfirm"], $_POST["tradeRegions"]);
			echo TemplateUtil::render("signup_cont", [
				"result" => $result,
				"email" => $email
			]);
		} else {
			http_response_code(400);
		}
	} else {
		if (isset($_GET["id"]) && isset($_GET["key"])) {
			$email = UserUtil::getInstance()->verifySignupRequest($_GET["id"], $_GET["key"]);
			if (isset($email)) {
				echo TemplateUtil::render("signup_cont", [
					"result" => -1,
					"id" => $_GET["id"],
					"key" => $_GET["key"],
					"email" => $email
				]);
			} else {
				echo TemplateUtil::render("signup_cont", [
					"result" => 1
				]);
			}
		} else {
			echo TemplateUtil::render("signup_cont", [
				"result" => 1
			]);
		}
	}
