<?php
	class SessionUtil {

		private static $instance;

		private final function  __construct() {
		}

		public static function getInstance() {
			if(!isset(self::$instance)) {
				self::$instance = new SessionUtil();
			}
			return self::$instance;
		}
		
		public function isSessionActive() {
			return isset($_SESSION["user_id"]) && isset($_SESSION["type"]) && $_SESSION["type"] == "web";
		}
		
		public function initSession($userId) {
			$_SESSION["type"] = "web";
			$_SESSION["user_id"] = $userId;
			return;
		}
		
		public function setLang($lang) {
			$_SESSION["lang"] = $lang;
			// TODO: Persist to user prefs if signed in
			return;
		}

		public function getLang() {
			// TODO: Initial value from user prefs if signed in
			$locale = 'en';
			if(isset($_GET["lang"])) {
				$locale = $_GET["lang"];
			}
			elseif (!isset($_SESSION["lang"])) {
				if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strlen($_SERVER['HTTP_ACCEPT_LANGUAGE'])>0) {
					$locale = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
				}
			}
			$_SESSION["lang"] = $locale;
			return $_SESSION["lang"];
		}
		
		public function destroySession() {
			session_unset();
			session_destroy();
			return;
		}
	}
?>
