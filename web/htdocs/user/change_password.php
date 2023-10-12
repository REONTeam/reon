<?php
	require_once("../../classes/SessionUtil.php");
	require_once("../../classes/UserUtil.php");
	session_start();
	
	if (SessionUtil::getInstance()->isSessionActive()) {
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (isset($_POST["currentPassword"]) && isset($_POST["newPassword"]) && isset($_POST["newPasswordConfirm"])) {
				$result = UserUtil::getInstance()->changePassword($_POST["currentPassword"], $_POST["newPassword"], $_POST["newPasswordConfirm"]);
				echo TemplateUtil::render("/user/change_password", [
					"result" => $result
				]);
			} else {
				http_response_code(400);
			}
		} else {
			echo TemplateUtil::render("/user/change_password", [
				"result" => -1
			]);
		}
	} else {
		header("Location: /index.php");
	}