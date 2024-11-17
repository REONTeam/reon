<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/monopoly.php");

	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists("today", $params) || $params["today"] === "00") {
		http_response_code(400);
		return;
	}
	query($params);
?>
