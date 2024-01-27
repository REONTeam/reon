<?php
	require_once(CORE_PATH."/database.php");

	function makeRankingEntry($rank, $result) {
		$output = pack("N", $rank);
		$output = $output.$result["name"];
		$output = $output.$result["email"];
		$output = $output.pack("C", $result["today"]);
		$output = $output.$result["pad"];
		$output = $output.pack("N", $result["properties"]);
		$output = $output.pack("N", $result["money"]);
		$output = $output.pack("C", $result["gender"]);
		$output = $output.pack("C", $result["age"]);
		$output = $output.pack("C", $result["state"]);
		$output = $output.pack("C", $result["today2"]);
		return $output;
	}

	function query($params) {
		if (!array_key_exists("myname", $params) || strlen($params["myname"]) != 80) {
			http_response_code(400);
			return;
		}
		if (strlen($params["today"]) != 2) {
			http_response_code(400);
			return;
		}
		$myname = hex2bin($params["myname"]);
		if ($myname === false) {
			http_response_code(400);
			return;
		}
		$today = hex2bin($params["today"]);
		if ($today !== substr($myname, 36, 1)) {
			http_response_code(400);
			return;
		}
		$today = unpack("C", $today)[1];

		$name = substr($myname, 0, 4);
		$email = substr($myname, 4, 32);

		$db = connectMySQL();
		if ($today == 0) {
			$stmt = $db->prepare("select * from amoj_ranking where today != 0 group by id, name, email, gender, age, state order by money, properties desc limit 10");
		} else {
			$year = date("Y", time() + 32400) % 16;
			$month = date("m", time() + 32400);
			if (($today >> 4) != $year || ($today & 0xF) != $month) {
				if ($month <= 1) {
					$year = ($year == 0) ? 15 : $year - 1;
					$month = 12;
				} else {
					$month--;
				}
			}
			if (($today >> 4) != $year || ($today & 0xF) != $month) {
				http_response_code(400);
				return;
			}
			$stmt = $db->prepare("select * from amoj_ranking where today = ? order by money, properties desc limit 10");
			$stmt->bind_param("i", $today);
		}
		$stmt->execute();
		$result = fancy_get_result($stmt);

		echo pack("n", sizeof($result));
		for ($i = 0; $i < sizeof($result); $i++) {
			echo makeRankingEntry($i + 1, $result[$i]);
		}

		$stmt = $db->prepare("delete ignore from amoj_ranking where today = 0 and name = ? and email = ?");
		$stmt->bind_param("ss", $name, $email);
		$stmt->execute();

		if ($today == 0) {
			$stmt = $db->prepare("select * from amoj_ranking where name = ? and email = ? order by money, properties desc limit 1");
			$stmt->bind_param("ss", $name, $email);
		} else {
			$stmt = $db->prepare("select * from amoj_ranking where name = ? and email = ? and today = ?");
			$stmt->bind_param("ssi", $name, $email, $today);
		}
		$stmt->execute();
		$result = fancy_get_result($stmt);

		echo pack("n", sizeof($result));
		if (sizeof($result) != 0) {
			$stmt = $db->prepare("select count(*) from amoj_ranking where money > ? or (money = ? and (properties > ? or (properties = ? and id <= ?)))");
			$stmt->bind_param("iiiii", $result["money"], $result["money"], $result["properties"], $result["properties"], $result["id"]);
			$stmt->execute();
			$rank = fancy_get_result($stmt)[0]["count(*)"];
			echo makeRankingEntry($rank, $result[0]);
		}
	}
?>
