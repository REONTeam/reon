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
		
		public function setLocale($locale) {
			$supported = array("en", "ja", "de", "es", "it", "fr");
			$normalized = strtolower(trim((string)$locale));
			if (!in_array($normalized, $supported, true)) {
				$normalized = "en";
			}
			$_SESSION["locale"] = $normalized;
			// TODO: Persist to user prefs if signed in
			return;
		}

		public function getLocale() {
			$supported = array("en", "ja", "de", "es", "it", "fr");
			// TODO: Initial value from user prefs if signed in
			if(isset($_GET["lang"])) {
				$this->setLocale($_GET["lang"]);
			}
			elseif (!isset($_SESSION["locale"])) {
				if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strlen($_SERVER['HTTP_ACCEPT_LANGUAGE'])>0) {
					$this->setLocale(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
				} else {
					$_SESSION["locale"] = "en";
				}
			}
			elseif (!in_array(strtolower((string)$_SESSION["locale"]), $supported, true)) {
				$_SESSION["locale"] = "en";
			}
			return $_SESSION["locale"];
		}
		
		public function destroySession() {
			session_unset();
			session_destroy();
			return;
		}
	}
?>
