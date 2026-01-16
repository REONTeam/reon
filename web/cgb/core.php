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
		// Derive per-request game id/region from the requested (virtual) path.
		$GLOBALS['CGB_GAME_ID'] = extractGameIdFromPath($filePath);
		$GLOBALS['CGB_GAME_REGION'] = $GLOBALS['CGB_GAME_ID'] ? extractGameRegionFromGameId($GLOBALS['CGB_GAME_ID']) : null;

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
	
	// Some download scripts (e.g. Pokémon News config.php) use doAuth(type 2) which relies on
	// the PHP session keyed by the Authorization challenge bytes. Destroying the session here
	// prevents the client from reusing the same Authorization across follow-up requests.
	// Close the session without deleting it.
	if (session_status() == PHP_SESSION_ACTIVE && !isset($sessionId)) {
		session_write_close();
	}
}



/**
 * Extract a normalized game id (lowercase) from a path by scanning for a segment like
 * "AGB-XXXX" or "CGB-XXXX".
 * Returns null if it cannot be determined.
 */
function extractGameIdFromPath(string $path): ?string {
    $path = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path);
    $parts = explode(DIRECTORY_SEPARATOR, $path);

    for ($i = count($parts) - 1; $i >= 0; $i--) {
        $seg = $parts[$i];
        if (preg_match('/^(?:AGB|CGB|SGB|NTR|DS|GBA)-([A-Z0-9]+)$/i', $seg, $m)) {
            return strtolower($m[1]);
        }
    }
    return null;
}

/**
 * Extract game_region from a normalized game id.
 * Convention: game_region is the last alphabetic character of the game id.
 * Example: "amgj" -> "j", "ay5j" -> "j".
 */
function extractGameRegionFromGameId(string $gameId): ?string {
    $gameId = strtolower($gameId);
    if ($gameId === '') return null;

    $last = substr($gameId, -1);
    if (ctype_alpha($last)) {
        return $last;
    }
    return null;
}

/**
 * Current request helpers (set by serveFileOrExecScript()).
 */
function getCurrentGameId(): ?string {
    return $GLOBALS['CGB_GAME_ID'] ?? null;
}

function getCurrentGameRegion(): ?string {
    return $GLOBALS['CGB_GAME_REGION'] ?? null;
}


function generate_UUID() {
	return str_replace(
		array('+','/','='),
		array('-','_',''),
		base64_encode(file_get_contents('/dev/urandom', 0, null, 0, 8))
	);
}
?>
