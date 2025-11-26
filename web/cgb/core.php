<?php
// SPDX-License-Identifier: MIT
// Core file for CGB-005 server.

global $config;

function getConfig() {
	global $config;
	if(!$config) {
		$config = json_decode(file_get_contents(dirname(__DIR__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."config.json"), true);
	}
	return $config;
}

function serveFileOrExecScript($filePath, $type, $sessionId = null) {
	$dir = dirname(__DIR__).DIRECTORY_SEPARATOR."cgb".DIRECTORY_SEPARATOR.$type;
	
	header_remove();
	
	if (isset($sessionId)) {
		header("Gb-Auth-ID: ".$sessionId);
	}
    
    if (isset($filePath) && $filePath != "")
    {
		$realBaseDir = realpath($dir);
		$realFilePath = realpath($dir.$filePath);
		
		// if a .cgb/.agb/.txt file was requested but doesn't exist, try .php instead
		if ($realFilePath === false) $realFilePath = realpath(str_replace(".cgb", ".php", $dir.$filePath));
		if ($realFilePath === false) $realFilePath = realpath(str_replace(".agb", ".php", $dir.$filePath));
		if ($realFilePath === false) $realFilePath = realpath(str_replace(".txt", ".php", $dir.$filePath));

		if ($realFilePath === false || strpos($realFilePath, $realBaseDir) !== 0) {
			// file doesn't exist or is outiside of the base directory (directory traversal)
			if ($type === "download" && str_starts_with($filePath, "/01/AGB-AMKJ/")) { // pay for mobile GP
				return;
			} else if ($type === "download" && str_starts_with($filePath, "/18/AGB-AMSJ/")) { // pay for new puzzles
				return;
			} else if ($type === "download" && str_starts_with($filePath, "/01/AGB-ANPJ/") && str_ends_with($filePath, ".money.cgb")) { // pay for formation data
				return;
			}

			// Game Boy Wars 3
			if ($type === "download" && preg_match('#^/18/CGB-BWW([JE])/(.+)$#', $filePath, $matches)) {
				require_once dirname($realBaseDir) . "/gbwars/routes.php";
				if (handleGbwarsRoute(strtolower($matches[1]), $matches[2])) {
					return;
				}
			}

			http_response_code(404);
		} else {
			// file exists
			if (pathinfo($realFilePath)["extension"] === "php") {
				// If a PHP script, execute
				include($realFilePath);
			} else {
				// If not a PHP script, serve the file
				readfile($realFilePath); // This puts the file into the output buffer.
			}
		}
	} else {
		// Invalid string, so we'll just automatically 404. Ideally, a 400 would be better here but 404 is smarter
        http_response_code(404);
	}
	
	if (session_status() == PHP_SESSION_ACTIVE && !isset($sessionId)) {
		session_destroy();
	}
}

function generate_UUID() {
	return str_replace(
		array('+','/','='),
		array('-','_',''),
		base64_encode(file_get_contents('/dev/urandom', 0, null, 0, 8))
	);
}
?>
