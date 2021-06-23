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

function serveFileOrExecScript($filePath) {
	$dir = dirname(__DIR__).DIRECTORY_SEPARATOR."html".DIRECTORY_SEPARATOR;
	
	header_remove();
    
    if (isset($filePath) && $filePath != "")
    {
		$file = test_input($filePath);
		$file = ltrim($file, '/');
		$file = strtok($file, '?'); // strip url parameters from file path
		
		if (file_exists($dir.$file))
		{
			if (pathinfo($dir.$file)["extension"] === "php") {
				// If a PHP script, execute
				include($dir.$file);
			} else {
				// If not a PHP script, serve the file
				header("HTTP/1.0 200 OK");
				readfile($dir.$file); // This puts the file into the output buffer.
			}
		}
		else
		{
			if (pathinfo($dir.$file)["extension"] === "cgb") {
				// .cgb files were probably dynamic stuff instead of actual files on the original server
				$phpPath = str_replace(".cgb", ".php", $dir.$file);
				if (file_exists($phpPath)) {
					include($phpPath);
				} else {
					// File not found, so 404!
					http_response_code(404);
				}
			} else {
				// File not found, so 404!
				http_response_code(404);
			}
		}
	} else {
		// Invalid string, so we'll just automatically 404. Ideally, a 400 would be better here but 404 is smarter
        http_response_code(404);
	}
	
	if (session_status() == PHP_SESSION_ACTIVE) {
		session_destroy();
	}
}

function generate_UUID() {
	return str_replace(
		array('+','/','='),
		array('-','_',''),
		base64_encode(file_get_contents('/dev/urandom', 0, null, -1, 8))
	);
}

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>