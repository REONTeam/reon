<?php
	require_once(CORE_PATH."/monopoly.php");

	parse_str(file_get_contents("php://input"), $params);
	if (!array_key_exists($params, "myscore") || strlen($params["myscore"]) != 8) {
		http_response_code(400);
		return;
	}

	$myscore = hex2bin($params["myscore"]);
	if ($myscore === false) {
		http_response_code(400);
		return;
	}

	$points = unpack("N", substr($myscore, 0, 4))[1];
	$money = unpack("N", substr($myscore, 4))[1];

	$db = connectMySQL();
	$stmt = $db->prepare("select dion_email_local from sys_users where dion_ppp_id = ?");
	$stmt->bind_param("s", $_SESSION["userId"]);
	$stmt->execute();
	$result = fancy_get_result($stmt);
	if (sizeof($result) == 0) {
		http_response_code(400);
		return;
	}
	$email = $result[0]["dion_email_local"]."@reon.dion.ne.jp";

	$stmt = $db->prepare("select * from amoj_ranking where email = ? and points = ? and money = ?");
	$stmt->bind_param("sii", $email, $points, $money);
	$stmt->execute();
	$result = fancy_get_result($stmt);
	if (sizeof($result) == 0 || $result[0]["today2"] == 0) {
		//http_response_code(400); // the game explicitly says that bad data will be accepted, but not saved
		return;
	}

	$stmt = $db->prepare("update amoj_ranking set today = ? where id = ?");
	$stmt->bind_param("ii", $result[0]["today2"], $result[0]["id"]);
	$stmt->execute();

	$stmt = $db->prepare("select count(*) from amoj_ranking where money > ? or (money = ? and (points > ? or (points = ? and id <= ?)))");
	$stmt->bind_param("iiiii", $result["money"], $result["money"], $result["points"], $result["points"], $result["id"]);
	$stmt->execute();
	$result = fancy_get_result($stmt);

	echo pack("n", 1);
	echo pack("N", $result[0]["count(*)"]);
?>
