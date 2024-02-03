<?php
	require_once("../classes/SessionUtil.php");
	require_once("../classes/UserUtil.php");
	session_start();
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["email"])) {
			$result = UserUtil::getInstance()->sendSignupEmailAction($_POST["agree"], $_POST["email"]);
			echo TemplateUtil::render("signup", [
				"result" => $result,
				"email" => $_POST["email"]
			]);
		} else {
			http_response_code(400);
		}
	} else {
		echo TemplateUtil::render("signup", [
			"result" => -1
		]);
	}