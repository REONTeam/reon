<?php
// SPDX-License-Identifier: MIT
define('CORE_PATH', dirname(dirname(__DIR__)) . '/core');
require_once(CORE_PATH.'/core.php');
require_once(CORE_PATH.'/auth.php');

	$sessionId = doAuth(1);
	if (isset($sessionId)) {
		if (is_int($sessionId) && $sessionId == 0) {
			serveFileOrExecScript($_GET["name"], "download");
		} else {
			serveFileOrExecScript($_GET["name"], "download", $sessionId);
		}
	}
?>