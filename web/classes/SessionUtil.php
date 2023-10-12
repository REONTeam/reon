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
		
		public function destroySession() {
			session_unset();
			session_destroy();
			return;
		}
	}
?>
