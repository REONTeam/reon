<?php
// Core file for CGB-005 server.

global $config;

function getConfig() {
	global $config;
	if(!$config) {
		$config = json_decode(file_get_contents(dirname(__DIR__).DIRECTORY_SEPARATOR."config.json"), true);
	}
	return $config;
}

function serveFileOrExecScript($filePath, $type, $sessionId = null) {
	$dir = dirname(__DIR__).DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR.$type;
	
	header_remove();
	
	if (isset($sessionId)) {
		header("Gb-Auth-ID: ".$sessionId);
	}
    
    if (isset($filePath) && $filePath != "")
    {
		$realBaseDir = realpath($dir);
		$realFilePath = realpath($dir.$filePath);
		
		// if a .cgb file was requested but doesn't exist, try .php instead
		if ($realFilePath === false) $realFilePath = realpath(str_replace(".cgb", ".php", $dir.$filePath));
		
		if ($realFilePath === false || strpos($realFilePath, $realBaseDir) !== 0) {
			// file doesn't exist or is outiside of the base directory (directory traversal)
			http_response_code(404);
		} else {
			// file exists
			if (pathinfo($realFilePath)["extension"] === "php") {
				// If a PHP script, execute
				include($realFilePath);
			} else {
				// If not a PHP script, serve the file
				header("HTTP/1.0 200 OK");
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
?>