<?php
	require_once(CORE_PATH."/monopoly.php");

	$input = fopen("php://input", "rb");
	$name = fread($input, 4);
	$email = rtrim(fread($input, 32), "\0");
	$today = unpack("C", fread($input, 1))[1];
	fseek($input, 40);
	$properties = unpack("N", fread($input, 4))[1];
	$money = unpack("N", fread($input, 4))[1];
	$gender = unpack("C", fread($input, 1))[1];
	$age = unpack("C", fread($input, 1))[1];
	$state = unpack("C", fread($input, 1))[1];
	$today2 = unpack("C", fread($input, 1))[1];
	fclose($input);

	if ($today != $today2) {
		http_response_code(400);
		return;
	}
	if (ord($name) == 0xFF || str_contains(rtrim($name, "\xFF"), "\xFF")) {
		http_response_code(400);
		return;
	}
	if (substr($email, 8) !== "@reon.dion.ne.jp") {
		http_response_code(400);
		return;
	}
	if ($properties > 84 || $gender > 1 || $age > 99 || $state > 46) {
		http_response_code(400);
		return;
	}

	$db = connectMySQL();
	$stmt = $db->prepare("select dion_email_local from sys_users where dion_ppp_id = ?");
	$stmt->bind_param("s", $_SESSION["userId"]);
	$stmt->execute();
	$result = fancy_get_result($stmt);

	if (sizeof($result) == 0 || $result[0]["dion_email_local"] !== substr($email, 0, 8)) {
		http_response_code(400);
		return;
	}

	if ($today == 0) {
		$stmt = $db->prepare("delete ignore from amoj_ranking where today = 0 and name = ? and email = ? and gender = ? and age = ? and state = ?");
		$stmt->bind_param("ssiii", $name, $email, $gender, $age, $state);
		$stmt->execute();

		$stmt = $db->prepare("insert into amoj_ranking (name, email, properties, money, gender, age, state) values (?,?,?,?,?,?,?)");
		$stmt->bind_param("ssiiiii", $name, $email, $properties, $money, $gender, $age, $state);
	} else {
		$year = date("Y", time() + 32400) % 16;
		$month = date("m", time() + 32400);
		if (($today >> 4) != $year || ($today & 0xF) != $month) {
			http_response_code(400);
			return;
		}
		$stmt = $db->prepare("select id from amoj_ranking where today2 = 0 and name = ? and email = ? and properties = ? and money = ? and gender = ? and age = ? and state = ?");
		$stmt->bind_param("ssiiiii", $name, $email, $properties, $money, $gender, $age, $state);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		if (sizeof($result) == 0) {
			http_response_code(400);
			return;
		}
		$stmt = $db->prepare("update amoj_ranking set today2 = ? where id = ?");
		$stmt->bind_param("ii", $today, $result[0]["id"]);
	}
	$stmt->execute();
?>