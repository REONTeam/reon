<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/core.php");
	require_once(CORE_PATH."/database.php");

	$data = file_get_contents("php://input");
	if (!str_ends_with($data, "\r\n.\r\n")) {
		http_response_code(400);
		return;
	}
	$data = substr($data, 0, -5);

	$db = connectMySQL();
	$stmt = $db->prepare("select dion_email_local from sys_users where id = ?");
	$stmt->bind_param("i", $_SESSION['userId']);
	$stmt->execute();
	$result = fancy_get_result($stmt);
	if (sizeof($result) == 0) {
		http_response_code(400);
		return;
	}

	$config = getConfig();
	$email = $result[0]["dion_email_local"]."@".$config["email_domain_dion"];

	$stmt = $db->prepare("insert into amcj_trades (acc_id, email, message) values (?,?,?)");
	$stmt->bind_param("iss", $_SESSION['userId'], $email, $data);
	$stmt->execute();
?>
