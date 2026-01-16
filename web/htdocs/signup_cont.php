<?php
	require_once("../classes/SessionUtil.php");
	require_once("../classes/UserUtil.php");
	session_start();
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["id"]) && isset($_POST["key"]) && isset($_POST["reonEmail"]) && isset($_POST["password"]) && isset($_POST["passwordConfirm"]) && isset($_POST["tradeRegions"])) {
			// Fetch email before completing signup (completeSignupAction deletes sys_signup).
			$email = UserUtil::getInstance()->verifySignupRequest($_POST["id"], $_POST["key"]);

			$optIn = 0;
			if (isset($_POST["pokemonNewsCustomOptIn"])) {
				$optIn = ($_POST["pokemonNewsCustomOptIn"] == "1") ? 1 : 0;
			}

			$result = UserUtil::getInstance()->completeSignupAction(
				$_POST["id"],
				$_POST["key"],
				$_POST["reonEmail"],
				$_POST["password"],
				$_POST["passwordConfirm"],
				$_POST["tradeRegions"],
				$optIn
			);
			echo TemplateUtil::render("signup_cont", [
				"result" => $result,
				"id" => $_POST["id"],
				"key" => $_POST["key"],
				"email" => $email,
				"reon_email" => $_POST["reonEmail"],
				"trade_regions" => $_POST["tradeRegions"],
				"pokemon_news_custom_opt_in" => $optIn
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
					"email" => $email,
					"trade_regions" => "efdsipuj",
					"pokemon_news_custom_opt_in" => 0
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
