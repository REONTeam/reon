<?php
// SPDX-License-Identifier: MIT
define('CORE_PATH', dirname(dirname(__DIR__)) . '/cgb');
require_once(CORE_PATH.'/core.php');
require_once(CORE_PATH.'/auth.php');

	$name = isset($_GET["name"]) ? (string)$_GET["name"] : "";

	// Pokemon News *.news.php scripts implement their own utility authentication
	// (doAuth(2)) so they can resolve the requesting user for custom-news gating.
	// If we let the download front-controller enforce cost-based auth for
	// "NN.news.php" (because of the numeric prefix), the request will 401 forever:
	// the client responds using the download-auth challenge, but the script expects
	// utility-auth.
	$skipCostAuth = preg_match('#/news/\d+\.news\.php$#i', $name) === 1;

	if ($skipCostAuth) {
		serveFileOrExecScript($name, "download");
		return;
	}

	$sessionId = doAuth(1);
	if (isset($sessionId)) {
		if (is_int($sessionId) && $sessionId == 0) {
			serveFileOrExecScript($name, "download");
		} else {
			serveFileOrExecScript($name, "download", $sessionId);
		}
	}
?>