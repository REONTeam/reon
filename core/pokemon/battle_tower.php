<?php
	require_once(CORE_PATH."/database.php");
	require_once(CORE_PATH."/pokemon/func.php");
	require_once(CORE_PATH."/pokemon/battle_tower_trainers.php");
	
	function battleTowerGetRoomCount() {
		$num_rooms_per_level = 1; // change number of available rooms here
		return pack("n",$num_rooms_per_level * 10);
	}
	
	function battleTowerGetRoom($roomNo, $bxte = false) {
		$room = roomNoToRoom($roomNo); // selected room (0 = room 001, 1 = room 002...)
		$level = roomNoToLevel($roomNo); // selected level (0 = L:10, 9 = L:100)
		
		$db = connectMySQL();
		$stmt = $db->prepare("select hex(name), hex(class), hex(pokemon1), hex(pokemon2), hex(pokemon3), hex(message_start), hex(message_win), hex(message_lose) from ".($bxte ? "bxte" : "bxtj")."_battle_tower_trainers where room = ? and level = ? order by no desc limit 7;");
		$stmt->bind_param("ii", $room, $level);
		$stmt->execute();
		$result = fancy_get_result($stmt);
		
		// If there are not enough user generated trainers available for this room, add some placeholder trainers
		// As the game reads the trainers in reverse, the placeholder trainers will be battled first which is welcome as the battles should become harder as you progress
		for ($i = sizeof($result); $i < 7; $i++) {
			$result[$i] = $bxte ? getBattleTowerPlaceholderTrainerEN($i) : getBattleTowerPlaceholderTrainerJP($i);
		}
		
		return encodeBattleTowerRoomData($result, $bxte);
	}
	
	function battleTowerGetLeaders($roomNo, $bxte = false) {
		$room = roomNoToRoom($roomNo); // selected room (0 = room 001, 1 = room 002...)
		$level = roomNoToLevel($roomNo); // selected level (0 = L:10, 9 = L:100)
		
		$db = connectMySQL();
		$stmt = $db->prepare("select hex(name) from ".($bxte ? "bxte" : "bxtj")."_battle_tower_leaders where room = ? and level = ? order by id desc limit 30;");
		$stmt->bind_param("ii", $room, $level);
		$stmt->execute();
		return encodeLeaderList(fancy_get_result($stmt), $bxte);
	}
	
	function battleTowerSubmitRecord($inputStream, $bxte = false) {
		$data = decodeBattleTowerRecord($inputStream, $bxte);
		$db = connectMySQL();
		$stmt = $db->prepare("insert into ".($bxte ? "bxte" : "bxtj")."_battle_tower_records (room, level, email, trainer_id, secret_id, name, class, pokemon1, pokemon2, pokemon3, message_start, message_win, message_lose, num_trainers_defeated, num_turns_required, damage_taken, num_fainted_pokemon) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt->bind_param("iisiissssssssiiii", $data["room"], $data["level"], $data["email"], $data["trainer_id"], $data["secret_id"], $data["name"], $data["class"], $data["pokemon1"], $data["pokemon2"], $data["pokemon3"], $data["message_start"], $data["message_win"], $data["message_lose"], $data["num_trainers_defeated"], $data["num_turns_required"], $data["damage_taken"], $data["num_fainted_pokemon"]);
		return $stmt->execute();
	}
	
?>