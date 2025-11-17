<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/database.php");
	require_once(CORE_PATH."/pokemon/func.php");
	require_once(CORE_PATH."/pokemon/battle_tower_trainers.php");
	require_once(CORE_PATH."/pokemon/bxt_config.php");
	
	function battleTowerGetRoomCount() {
		$num_rooms_per_level = 20; // change number of available rooms here
		return pack("n",$num_rooms_per_level * 10);
	}
	
	/**
	 * Fetch a Battle Tower room for a given region + room number.
	 *
	 * Behaviour:
	 *  - All uploaded trainers live in bxt_battle_tower_trainers with a
	 *    game_region column.
	 *  - We compute the set of allowed source regions for the requesting
	 *    region using bxt_get_allowed_regions_for_feature('battle_tower', $region).
	 *  - We randomly pick up to 7 trainers across those regions for the
	 *    requested room/level.
	 *  - If there are fewer than 7, we pad with placeholder trainers for
	 *    the requesting region.
	 */
	function battleTowerGetRoom($region, $roomNo) {
		$region = strtolower($region);
		$room = roomNoToRoom($roomNo);   // selected room (0 = room 001, 1 = room 002...)
		$level = roomNoToLevel($roomNo); // selected level (0 = L:10, 9 = L:100)
		
		$db = connectMySQL();

		$sourceRegions = bxt_get_allowed_regions_for_feature('battle_tower', $region);
		if (empty($sourceRegions)) {
			$sourceRegions = [$region];
		}

		// Build a safe IN (...) list for game_region.
		$inParts = [];
		foreach ($sourceRegions as $r) {
			$inParts[] = "'" . $db->real_escape_string(strtolower($r)) . "'";
		}
		$inExpr = implode(',', $inParts);

		$sql = "SELECT name, class, pokemon1, pokemon2, pokemon3, "
			 . "message_start, message_win, message_lose "
			 . "FROM bxt_battle_tower_trainers "
			 . "WHERE game_region IN ($inExpr) AND room = ? AND level = ? "
			 . "ORDER BY RAND() LIMIT 7";

		$stmt = $db->prepare($sql);
		if (!$stmt) {
			// Fall back to pure placeholders if the table is missing
			$result = [];
			for ($i = 0; $i < 7; $i++) {
				$result[] = getBattleTowerPlaceholderTrainerForRegion($region, $level, $i);
			}
			$bxte = ($region !== 'j');
			return encodeBattleTowerRoomData($result, $bxte);
		}

		$stmt->bind_param("ii", $room, $level);
		$stmt->execute();
		$result = fancy_get_result($stmt);

		// Pad to 7 entries with placeholders if needed
		$have = is_array($result) ? count($result) : 0;
		if (!is_array($result)) {
			$result = [];
		}
		for ($i = $have; $i < 7; $i++) {
			$result[] = getBattleTowerPlaceholderTrainerForRegion($region, $level, $i);
		}

		$bxte = ($region !== 'j');
		return encodeBattleTowerRoomData($result, $bxte);
	}
	
	/**
	 * Fetch Battle Tower leaders list for a given room/level.
	 *
	 * For consistency with the original interface, $bxte decides the
	 * *requesting* region: false => Japanese ('j'), true => English
	 * ('e' and friends).  From that region we compute the allowed set
	 * of source regions and then read from the unified leaders table.
	 */
	function battleTowerGetLeaders($roomNo, $bxte = false) {
		$room = roomNoToRoom($roomNo);
		$level = roomNoToLevel($roomNo);
		
		// Requesting region: 'j' for BXTJ, 'e' for all non-J.
		$region = $bxte ? 'e' : 'j';

		$db = connectMySQL();
		$sourceRegions = bxt_get_allowed_regions_for_feature('battle_tower', $region);
		if (empty($sourceRegions)) {
			$sourceRegions = [$region];
		}

		$inParts = [];
		foreach ($sourceRegions as $r) {
			$inParts[] = "'" . $db->real_escape_string(strtolower($r)) . "'";
		}
		$inExpr = implode(',', $inParts);

		$sql = "SELECT HEX(name) AS `hex(name)` "
			 . "FROM bxt_battle_tower_leaders "
			 . "WHERE game_region IN ($inExpr) AND room = ? AND level = ? "
			 . "ORDER BY id DESC LIMIT 30";

		$stmt = $db->prepare($sql);
		if (!$stmt) {
			// No table: return an empty list, the game will fall back to its local data.
			return encodeLeaderList([], $bxte);
		}
		$stmt->bind_param("ii", $room, $level);
		$stmt->execute();
		return encodeLeaderList(fancy_get_result($stmt), $bxte);
	}
	
	/**
	 * Raw Battle Tower record insert (no legality / policy checks).
	 * In normal operation battleTowerSubmitRecord_legality() in
	 * battle_tower_legality.php should be used instead.
	 */
	function battleTowerSubmitRecord($inputStream, $bxte = false) {
		$data = decodeBattleTowerRecord($inputStream, $bxte);
		$db = connectMySQL();

		$region = $bxte ? 'e' : 'j';

		$sql = "INSERT INTO bxt_battle_tower_records (
				game_region,
				room,
				level,
				trainer_id,
				secret_id,
				player_name,
				class,
				pokemon1,
				pokemon2,
				pokemon3,
				message_start,
				message_win,
				message_lose,
				num_trainers_defeated,
				num_turns_required,
				damage_taken,
				num_fainted_pokemon,
				account_id,
				email
			) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		$stmt = $db->prepare($sql);
		if (!$stmt) {
			return false;
		}

		// Types: s + iisiissssssssiiiii
		$stmt->bind_param(
			"siisiissssssssiiiii",
			$region,
			$data['room'],
			$data['level'],
			$data['email'],
			$data['trainer_id'],
			$data['secret_id'],
			$data['name'],
			$data['class'],
			$data['pokemon1'],
			$data['pokemon2'],
			$data['pokemon3'],
			$data['message_start'],
			$data['message_win'],
			$data['message_lose'],
			$data['num_trainers_defeated'],
			$data['num_turns_required'],
			$data['damage_taken'],
			$data['num_fainted_pokemon'],
			isset($_SESSION['userId']) ? $_SESSION['userId'] : 0
		);

		return $stmt->execute();
	}
	
?>