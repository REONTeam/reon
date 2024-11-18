<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/core.php");
	require_once(CORE_PATH."/database.php");

	$config = getConfig();

	$data = file_get_contents("php://input");
	if (strlen($data) != 0x49) {
		http_response_code(400);
		return;
	}

	$xor = array(
		0x28, 0x19, 0x8A, 0x5A, 0x6B, 0xDD, 0xE3, 0x59,
		0xF1, 0x38, 0x33, 0x8C, 0x9D, 0x42, 0x85, 0x5B,
		0xBA, 0x38, 0x92, 0x69, 0x13, 0x8A, 0x9B, 0x01,
		0x93, 0xBC, 0xC9, 0x3A, 0x5A, 0x2B, 0x92, 0x38,
		0x66, 0x69, 0x58, 0xCD, 0xC1, 0x1C, 0x9A, 0x55,
		0x8F, 0x77, 0x71, 0xE3, 0x16, 0x69, 0xE1, 0x3E,
		0xFF, 0xAF, 0x11, 0x23, 0x31, 0xE2, 0xD1, 0x5D,
		0x5A, 0xEE, 0x29, 0x88, 0x18, 0x91, 0x4F, 0x5A,
		0x60, 0xFE, 0xE5, 0xCD, 0x10, 0xFA, 0x07, 0x9A, 0x9D
	);

	for ($i = 0; $i < 0x49; $i++) {
		$data[$i] = chr(ord($data[$i]) ^ $xor[$i]);
	}

	$check = array(ord($data[3]), ord($data), ord($data[2]), ord($data[1]));
	$correct = array(0x83, 0xED, 0x76, 0x45);
	for ($i = 0; $i < 4; $i++) {
		for ($j = $i+4; $j < 0x49; $j += 4) {
			$check[$i] = ($check[$i] + ord($data[$j])) & 0xFF;
		}
		if ($check[$i] != $correct[$i]) {
			http_response_code(400);
			return;
		}
	}

	$ident = rtrim(substr($data, 4, 46), "\0");
	if ($ident[8] !== "@" || substr($ident, 9) !== $config["email_domain_dion"]) {
		http_response_code(400);
		return;
	}

	$db = connectMySQL();
	$stmt = $db->prepare("select dion_email_local from sys_users where id = ?");
	$stmt->bind_param("i", $_SESSION['userId']);
	$stmt->execute();
	$result = fancy_get_result($stmt);
	if ($result[0]["dion_email_local"] !== substr($ident, 0, 8)) {
		http_response_code(400);
		return;
	}

	$name = substr($data, 50, 16);
	$blood = unpack("C", $data[66])[1];
	$gender = unpack("C", $data[67])[1];
	$age = unpack("C", $data[68])[1];
	$weight = unpack("N", substr($data, 69))[1];

	if ($blood > 3 || $gender > 1) {
		http_response_code(400);
		return;
	}

	$db->begin_transaction();
	try {
		$stmt = $db->prepare("delete ignore from amgj_rankings where ident = ?");
		$stmt->bind_param("s", $ident);
		$stmt->execute();

		$stmt = $db->prepare("insert into amgj_rankings (ident, name, blood, gender, age, weight) values (?,?,?,?,?,?)");
		$stmt->bind_param("ssiiii", $ident, $name, $blood, $gender, $age, $weight);
		$stmt->execute();

		$db->commit();
	} catch (mysqli_sql_exception $e) {
		$db->rollback();
		http_response_code(500);
		return;
	}
?>
