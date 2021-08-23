<?php
	require_once(CORE_PATH."/database.php");
	
	// Auth procedure
	// Will only be performed for files prefixed with a cost (e.g. "10.")
	// The download and upload functions give the user a session that is to be used for the next request
	// The utility function however seems to immediately return the requested data (this is what $immediate is for)
	// type 0 = normal, type 1 = ranking, type 2 = utility
	function doAuth($type = 0) {
		$cost = isset($_GET["name"]) ? getCost($_GET["name"]) : null;
		$isAuthRequired = $type == 2 ? true : !is_null($cost);
		if($isAuthRequired && !isset($_SERVER["HTTP_GB_AUTH_ID"])) { // is the Auth ID set?
			if(!isset($_SERVER["HTTP_AUTHORIZATION"]) || $_SERVER["HTTP_AUTHORIZATION"] === "") { // If Auth ID isnt set but there's an Auth header, that means they've sent us something to check.
				// Generate a challenge
				$randomBytes = random_bytes(36);
				$challenge = base64_encode($randomBytes);
				
				// Temporarily store the challenge using the part that the game sends back as key
				session_id(substr(bin2hex($randomBytes), 0, 32 * 2));
				session_start();
				$_SESSION['challenge'] = $challenge;
				
				header_remove();
				http_response_code(401);
				header('WWW-Authenticate: GB00 name="'.$challenge.'"');
				//print hex2bin(substr(bin2hex($randomBytes), 0, 32 * 2));
				exit();
			} else {
				$authString = substr($_SERVER["HTTP_AUTHORIZATION"], 11);
				
				// Get the full challenge back
				session_id(bin2hex(base64_decode(substr($authString, 0 , 44))));
				session_start();
				if (!isset($_SESSION['challenge'])) {
					// If the challenge is not set here, something is wrong with the auth string
					header_remove();
					http_response_code(401);
					exit();
				}
				$challenge = $_SESSION['challenge'];
				session_destroy();
				
				// Validate what they sent
				$data = decodeAuthorization($challenge, $authString);
				$result = validateAuthData($data["dionId"], $data["passwordHash"], $challenge);
				if ($result["isValid"]) {
					// Auth successful
					// The download and upload functions give the user a session that is to be used for the next request
					// The utility function however seems to immediately return the requested data
					if ($type == 2) {
						return $result["userId"];
					} else {
						// 
						addCostToAccount($result["userId"], $cost);
						
						// Open a session
						$sessionId = bin2hex(random_bytes(16));
						session_id($sessionId);
						session_start();
						// Set some information about the user into the session so it is known later
						$_SESSION['userId'] = $result["userId"];
						$_SESSION['dionId'] = $result["dionId"];
						
						if ($type == 1) {
							return $sessionId;
						}
						
						header_remove();
						http_response_code(200);
						// Tell the GB the session id
						header("Gb-Auth-ID: ".$sessionId);
						exit();
					}
				} else {
					// If unsuccessful, return error code 33-201
					header_remove();
					http_response_code(401);
					header("Gb-Status: 201");
					exit();
				}
			}
		}
		
		if ($isAuthRequired) {
			// If a session id was sent, validate it
			session_id($_SERVER["HTTP_GB_AUTH_ID"]);
			session_start();
			// If there is no DION ID associated with the session, it's not valid
			if (!isset($_SESSION['dionId'])) {
				if (session_status() == PHP_SESSION_ACTIVE) {
					session_destroy();
				}
				header_remove();
				http_response_code(401);
				exit();
			}
		}
		header_remove();
		// When getting here, everything should be OK
		
		if ($type == 1 && !$isAuthRequired) return 0;
	}
	
	function getBit($byte, $bit) {
		return ((($byte) >> ($bit)) & 1);
	}
	
	// challenge = challenge that was sent by the server
	// authString = the authorization string sent by the game
	function decodeAuthorization($challenge, $authString) { // Original reverse engineered implementation courtesy of SimonTime
		$challengeDec = base64_decode($challenge);
		$authStringDec = base64_decode(substr($authString, 44));
		
		// Decode the encoded authentication data
		
		// Split the server generated 36 byte value into two 18 byte values
		// The first should contain all even-numbered bits
		$bitsSorted = "";
		for ($i = 0; $i < 18; $i++) {	
			$byte1 = ord($challengeDec[$i * 2 + 0]);
			$byte2 = ord($challengeDec[$i * 2 + 1]);
			
			$bitsSorted .= chr(getBit($byte1, 6) << 7 | getBit($byte1, 4) << 6 | getBit($byte1, 2) << 5 | getBit($byte1, 0) << 4);
			$bitsSorted[$i] = chr(ord($bitsSorted[$i]) | (getBit($byte2, 6) << 3 | getBit($byte2, 4) << 2 | getBit($byte2, 2) << 1 | getBit($byte2, 0) << 0));
		}
		
		// The second should contain all odd-numbered bits
		for (; $i < 36; $i++) {
			$byte1 = ord($challengeDec[($i - 18) * 2 + 0]);
			$byte2 = ord($challengeDec[($i - 18) * 2 + 1]);
			
			$bitsSorted .= chr(getBit($byte1, 7) << 7 | getBit($byte1, 5) << 6 | getBit($byte1, 3) << 5 | getBit($byte1, 1) << 4);
			$bitsSorted[$i] = chr(ord($bitsSorted[$i]) | (getBit($byte2, 7) << 3 | getBit($byte2, 5) << 2 | getBit($byte2, 3) << 1 | getBit($byte2, 1) << 0));
		}
		
		
		// Undo the bit rotation in the encoded auth data and xor it with the 36 bytes from above
		$authDataDec = "";
		for ($i = 0; $i < 36; $i++) {
			$authDataDec .= chr((ord($authStringDec[$i]) & 0b10110110) | getBit(ord($authStringDec[$i]), 3) << 0 | getBit(ord($authStringDec[$i]), 6) << 3 | getBit(ord($authStringDec[$i]), 0) << 6);
			// Now xor the two strings to get the decoded auth data
			$authDataDec[$i] = $authDataDec[$i] ^ $bitsSorted[$i];
		}
		
		// We got what we needed now
		$dionId = trim(substr($authDataDec, 16), hex2bin("FF"));
		$passwordHash = bin2hex(substr($authDataDec, 0, 16));
		
		return array(
			"dionId" => $dionId,
			"passwordHash" => $passwordHash
		);
	}
	
	function validateAuthData($dionId, $passwordHash, $challenge) {
		$db = connectMySQL();
		$stmt = $db->prepare("select id, password from users where dion_id = ?;");
		$stmt->bind_param("s", $dionId);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		// If the user doesn't exist it's a fail
		if (sizeof($result) == 0) {
			return array(
				"isValid" => false
			);
		}
		// Check if the hashes match
		return array(
			"dionId" => $dionId,
			"isValid" => $passwordHash === md5($challenge.$result[0]["password"]),
			"userId" => $result[0]["id"]
		);
	}
	
	// Returns the cost of the accessed file (auth required) or null if the file has no cost specified (no auth)
	function getCost($uri) {
		$parts = explode("/", $uri);
		$fileName = $parts[sizeof($parts) - 1];
		$cost = explode(".", $fileName)[0];
		if (is_numeric($cost)) {
			return intval($cost);
		}
	}
	
	function addCostToAccount($userId, $cost) {
		if ($cost == 0) return;
		$db = connectMySQL();
		$stmt = $db->prepare("update users set money_spent = money_spent + ? where id = ?");
		$stmt->bind_param("ii", $cost, $userId);
		$stmt->execute();
	}
	
	function getMoneySpent($userId) {
		$db = connectMySQL();
		$stmt = $db->prepare("select money_spent from users where id = ?");
		$stmt->bind_param("i", $userId);
		$stmt->execute();
		return fancy_get_result($stmt)[0]["money_spent"];
	}
?>