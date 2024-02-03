<?php
	require_once("../../classes/SessionUtil.php");
	require_once("../../classes/UserUtil.php");
	session_start();
	
	if (SessionUtil::getInstance()->isSessionActive()) {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (isset($_POST["currentPassword"]) && isset($_POST["newEmail"])) {
				$result = UserUtil::getInstance()->requestChangeEmailAction($_POST["currentPassword"], $_POST["newEmail"]);
				echo TemplateUtil::render("/user/change_email", [
					"result" => $result,
					"email" => $_POST["newEmail"]
				]);
			} else {
				http_response_code(400);
			}
		} else {
			echo TemplateUtil::render("/user/change_email", [
				"result" => -1
			]);
		}
	} else {
		header("Location: /index.php");
	}