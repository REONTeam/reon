<?php
	class ConfigUtil {

		private static $instance;
		private $config;

		private final function  __construct() {
			$this->config = json_decode(file_get_contents(dirname(__DIR__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.json"), true);
		}

		public static function getInstance() {
			if(!isset(self::$instance)) {
				self::$instance = new ConfigUtil();
			}
			return self::$instance;
		}
		
		public function getConfig() {
			return $this->config;
		}
	}
?>
