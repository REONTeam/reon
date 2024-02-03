<?php
	require_once("../classes/TemplateUtil.php");
	require_once("../classes/DBUtil.php");
	require_once("../classes/SessionUtil.php");
	session_start();
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!(isset($_POST["email"]) && isset($_POST["password"]))) return;
		$db = DBUtil::getInstance()->getDB();
		$stmt = $db->prepare("select id, password, email from sys_users where email = ? limit 1");
		$stmt->bind_param("s", $_POST["email"]);
		$stmt->execute();
		$result = DBUtil::fancy_get_result($stmt);
		if (array_key_exists(0, $result) && password_verify($_POST["password"], $result[0]["password"])) {
			SessionUtil::getInstance()->initSession($result[0]["id"]);
			//$_SESSION["user_email"] = $result[0]["email"];
			header("Location: index.php");
		} else {
			echo TemplateUtil::render("login", [
				"login_fail" => true
			]);
		}
	} else {
		echo TemplateUtil::render("login");
	}