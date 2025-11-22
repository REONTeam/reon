<?php
require_once __DIR__ . '/func.php';
require_once(CORE_PATH."/database.php");
require_once(CORE_PATH."/pokemon/func.php");
require_once(CORE_PATH."/pokemon/battle_tower_trainers.php");
require_once(CORE_PATH."/pokemon/bxt_config.php");

/**
 * Return the number of available Battle Tower rooms (as a 16-bit big-endian integer).
 *
 * Protocol: rooms.cgb is just:
 *   u16 room_count
 * There is NO list of room IDs in this file.
 */
function battleTowerGetRoomCount($region = null) {
    // Number of rooms per level (1–20).
    $num_rooms_per_level = 20;

    // 10 levels (10,20,...,100)
    $levels = 10;

    $region = $region ? strtolower($region) : 'e';

    $total_rooms = $num_rooms_per_level * $levels;

    // Crystal expects just a 16-bit big-endian count in rooms.cgb
    return pack("n", $total_rooms);
}


/**
 * Build one room: 7 trainers → encoded binary room blob.
 */
function battleTowerGetRoom($region, $roomNo) {
    $region = strtolower($region);

    $room  = roomNoToRoom($roomNo);
    $level = roomNoToLevel($roomNo);

    $db = connectMySQL();

        if (function_exists('bxt_regions_linked_for_feature')) {
        $sourceRegions = bxt_regions_linked_for_feature('battle_tower', $region);
    } else {
        $sourceRegions = [$region];
    }


    $inParts = [];
    foreach ($sourceRegions as $r) {
        $inParts[] = "'" . $db->real_escape_string(strtolower($r)) . "'";
    }
    $inExpr = implode(',', $inParts);

    $sql = "SELECT player_name AS name, class, pokemon1, pokemon2, pokemon3,
                   message_start, message_win, message_lose
            FROM bxt_battle_tower_trainers
            WHERE game_region IN ($inExpr)
            ORDER BY RAND() LIMIT 7";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        $result = [];
        for ($i = 0; $i < 7; $i++) {
            $result[] = getBattleTowerPlaceholderTrainerForRegion($region, $level, $i);
        }
        $bxte = ($region !== 'j');
        return encodeBattleTowerRoomData($result, $bxte);
    }

    $stmt->execute();
    $result = fancy_get_result($stmt);

    if (!is_array($result)) {
        $result = [];
    }
    $count = count($result);

    for ($i = $count; $i < 7; $i++) {
        $result[] = getBattleTowerPlaceholderTrainerForRegion($region, $level, $i);
    }

    $bxte = ($region !== 'j');
    return encodeBattleTowerRoomData($result, $bxte);
}

/**
 * Leaders list: 30 max leader names per room/level.
 */
function battleTowerGetLeaders($region, $roomNo, $bxte = false) {
    $room  = roomNoToRoom($roomNo);
    $level = roomNoToLevel($roomNo);

    $region = strtolower($region);

    $db = connectMySQL();

        if (function_exists('bxt_regions_linked_for_feature')) {
        $sourceRegions = bxt_regions_linked_for_feature('battle_tower', $region);
    } else {
        $sourceRegions = [$region];
    }


    $inParts = [];
    foreach ($sourceRegions as $r) {
        $inParts[] = "'" . $db->real_escape_string(strtolower($r)) . "'";
    }
    $inExpr = implode(',', $inParts);

    $sql = "SELECT HEX(name) AS `hex(name)`
            FROM bxt_battle_tower_leaders
            WHERE game_region IN ($inExpr)
              AND room = ?
              AND level = ?
            ORDER BY id DESC
            LIMIT 30";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        return encodeLeaderList([], $bxte);
    }

    $stmt->bind_param("ii", $room, $level);
    $stmt->execute();
    $result = fancy_get_result($stmt);

    if ($bxte === null) {
        $bxte = ($region !== 'j');
    }
    return encodeLeaderList($result, $bxte);
}

/**
 * Raw record insertion. Uses decodeBattleTowerRecord() directly.
 */
function battleTowerSubmitRecord($inputStream, $bxte = false) {
error_log(
    'BXT_DEBUG_BT_SUBMIT: entry ' .
    'account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') .
    ' stream=' . $inputStream .
    ' bxte=' . ($bxte ? '1' : '0')
);

$data = decodeBattleTowerRecord($inputStream, $bxte);
if (!is_array($data)) {
    error_log('BXT_DEBUG_BT_SUBMIT: decodeBattleTowerRecord returned non-array account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
    return false;
}

$db = connectMySQL();

$region = $bxte ? 'e' : 'j';

error_log(
    'BXT_DEBUG_BT_SUBMIT: decoded ' .
    ' account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') .
    ' region=' . $region .
    ' room=' . (isset($data['room']) ? $data['room'] : 'null') .
    ' level=' . (isset($data['level']) ? $data['level'] : 'null') .
    ' trainer_id=' . (isset($data['trainer_id']) ? $data['trainer_id'] : 'null') .
    ' secret_id=' . (isset($data['secret_id']) ? $data['secret_id'] : 'null')
);

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
            account_id
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $db->prepare($sql);
if (!$stmt) {
    error_log('BXT_DEBUG_BT_SUBMIT: stmt_prepare_failed account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') . ' ' . $db->error);
    return false;
}

$stmt->bind_param(
    "siisiissssssi",
    $region,
    $data['room'],
    $data['level'],
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
    isset($_SESSION['userId']) ? $_SESSION['userId'] : 0
);

if (!$stmt->execute()) {
    error_log('BXT_DEBUG_BT_SUBMIT: execute_failed account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') . ' ' . $stmt->error);
    return false;
}

error_log('BXT_DEBUG_BT_SUBMIT: execute_ok account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
return true;
}

?>
