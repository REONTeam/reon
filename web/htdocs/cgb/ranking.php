<?php
	// SPDX-License-Identifier: MIT
	// Authentication seems weird here...
	// Instead of sending POST data and getting a response on the third request, it happens on the second request
	// After this, there will be (useless?) third request that shouldn't cause the requested script to run again
	
	define('CORE_PATH', dirname(dirname(__DIR__)) . '/cgb');
	require_once(CORE_PATH.'/core.php');
	require_once(CORE_PATH.'/auth.php');
    
	$sessionId = doAuth(1);
	if (isset($sessionId)) {
		if (is_int($sessionId) && $sessionId == 0) {
			serveFileOrExecScript($_GET["name"], "ranking");
		} else {
			serveFileOrExecScript($_GET["name"], "ranking", $sessionId);
		}
	}
?>
