<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");

	function get_user_timezone($user_id = null) {
		$tz = "+0900";
		if (session_status() == PHP_SESSION_ACTIVE) {
			$user_id = $_SESSION['userId'];
		}
		if (!is_null($user_id)) {
			$db = connectMySQL();
			$stmt = $db->prepare("select timezone from sys_users where id = ?");
			$stmt->bind_param("i", $user_id);
			$stmt->execute();
			$result = fancy_get_result($stmt);
			if (sizeof($result) != 0) {
				$tz = $result[0]["timezone"];
			}
		}
		return timezone_open($tz);
	}

	function get_user_local_time($user_id = null, $datetime = "now") {
		return date_create_immutable($datetime)->setTimezone(get_user_timezone($user_id));
	}
?>
