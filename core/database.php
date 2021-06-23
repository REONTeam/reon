<?php
require_once(CORE_PATH."/core.php");

global $db;

function connectMySQL(){
	global $db;

	if(!$db) {
		$config = getConfig();
		$db = new mysqli($config["mysql_host"], $config["mysql_user"], $config["mysql_password"], $config["mysql_database"]);
	}
	return $db;
}

function fancy_get_result(&$stmt) {
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
