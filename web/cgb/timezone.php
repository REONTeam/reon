<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");

	function get_user_local_time($user_id = null) {
		$tz = "Asia/Tokyo";
		if (array_key_exists('userId', $_SESSION)) {
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
		return date_create_immutable("now", timezone_open($tz));
	}
?>
