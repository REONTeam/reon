<?php
	require_once("ConfigUtil.php");
	
	class DBUtil {

		private static $instance;
		private $db;

		private final function  __construct() {
			$config = ConfigUtil::getInstance()->getConfig();
			$this->db = new mysqli($config["mysql_host"], $config["mysql_user"], $config["mysql_password"], $config["mysql_database"]);
		}

		public static function getInstance() {
			if(!isset(self::$instance)) {
				self::$instance = new DBUtil();
			}
			return self::$instance;
		}
		
		public function getDB() {
			return $this->db;
		}
		
		public static function fancy_get_result(&$stmt) {
			$result = array();
			$stmt->store_result();
			for($i = 0; $i < $stmt->num_rows; $i++) {
				$meta = $stmt->result_metadata();
				$params = array();
				while ( $field = $meta->fetch_field() ) {
					$params[] = &$result[ $i ][ $field->name ];
				}
				call_user_func_array(array($stmt, 'bind_result'), $params);
				$stmt->fetch();
			}
			$stmt->close();
			return $result;
		}
	}
?>
