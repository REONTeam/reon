<?php
require_once __DIR__ . '/func.php';
require_once(CORE_PATH."/database.php");
require_once(CORE_PATH."/pokemon/func.php");
require_once(CORE_PATH."/pokemon/battle_tower_trainers.php");
require_once(CORE_PATH."/pokemon/bxt_config.php");
require_once(__DIR__ . "/../../scripts/bxt_name_conversion.php");


/**
 * Apply default species nickname for a Pokémon blob for the given download region.
 * Used for cross-region Battle Tower transfers where the source nickname encoding
 * may not be directly compatible.
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

    // Debug: which regions contribute to this room+level. (only if explicitly enabled).
    if (defined('BXT_DEBUG_BT_ROOM_REGIONS') && BXT_DEBUG_BT_ROOM_REGIONS) {
        error_log(
            'BXT_DEBUG_BT_ROOM_REGIONS: ' .
            'download_region=' . $region .
            ' room=' . $room .
            ' level=' . $level .
            ' source_regions=' . implode(',', array_map('strval', $sourceRegions))
        );
    }


    $inParts = [];
    foreach ($sourceRegions as $r) {
        $inParts[] = "'" . $db->real_escape_string(strtolower($r)) . "'";
    }
    $inExpr = implode(',', $inParts);

    // Pull all matching records for this room+level across linked regions,
    // ordered by performance:
    //  - num_trainers_defeated: higher is better (DESC)
    //  - num_turns_required: lower is better (ASC)
    //  - damage_taken: lower is better (ASC)
    //  - num_fainted_pokemon: lower is better (ASC)
    $sql = "SELECT t.game_region,
                   t.player_name AS name,
                   t.class,
                   t.pokemon1,
                   t.pokemon2,
                   t.pokemon3,
                   t.message_start,
                   t.message_win,
                   t.message_lose
            FROM bxt_battle_tower_trainers t
            WHERE t.game_region IN ($inExpr)
              AND t.room = ?
              AND t.level = ?
            ORDER BY t.no ASC
";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        $result = [];
        for ($i = 0; $i < 7; $i++) {
            $result[] = getBattleTowerPlaceholderTrainerForRegion($region, $level, $i);
        }
        $bxte = ($region !== 'j');
        return encodeBattleTowerRoomData($result, $bxte);
    }

    $stmt->bind_param('ii', $room, $level);
    $stmt->execute();
    $records = fancy_get_result($stmt);

// Debug: how many records were found for this room+level across the pool.
// Keep this log only when no records are found (num_records=0).
$numRecords = (is_array($records) ? count($records) : 0);
if ($numRecords === 0) {
    error_log(
        'BXT_DEBUG_BT_QUERY: ' .
        'download_region=' . $region .
        ' room=' . $room .
        ' level=' . $level .
        ' source_regions=' . implode(',', array_map('strval', $sourceRegions)) .
        ' num_records=' . $numRecords
    );
}


    if (!is_array($records) || count($records) === 0) {
        // No records for this exact room+level across the linked regions.
        // Fill the entire room with placeholders.
        $result = [];
        for ($i = 0; $i < 7; $i++) {
            $result[] = getBattleTowerPlaceholderTrainerForRegion($region, $level, $i);
        }
        $bxte = ($region !== 'j');
        return encodeBattleTowerRoomData($result, $bxte);
    }

    $realCount = count($records);
    $trainers = [];

    if ($realCount === 1) {
        // Only one real record: treat it as the leader in slot 6, fill the rest with placeholders.
        for ($i = 0; $i < 6; $i++) {
            $trainers[] = getBattleTowerPlaceholderTrainerForRegion($region, $level, $i);
        }
        $leader = $records[0];
        $trainers[] = $leader;
    } else {
        // At least two records.
        $leader = $records[0];
        $others = array_slice($records, 1);
        $nonLeaderCount = count($others);

        $perfPool = [];
        $firstRandom = null;

        if ($nonLeaderCount >= 6) {
            // Take the top 5 as performance-based; random comes from the remaining pool.
            $perfPool = array_slice($others, 0, 5);
            $randPool = array_slice($others, 5);
            if (count($randPool) > 0) {
                $idx = random_int(0, count($randPool) - 1);
                $firstRandom = $randPool[$idx];
            } else {
                // Fallback: random from perfPool.
                $idx = random_int(0, count($perfPool) - 1);
                $firstRandom = $perfPool[$idx];
                unset($perfPool[$idx]);
                $perfPool = array_values($perfPool);
            }
        } else {
            // Fewer than 6 non-leader records: all are performance-based,
            // and we pick the first trainer randomly from them.
            $perfPool = $others;
            $idx = random_int(0, count($perfPool) - 1);
            $firstRandom = $perfPool[$idx];
            unset($perfPool[$idx]);
            $perfPool = array_values($perfPool);
        }

        // Slot 0: random trainer (not the leader).
        $trainers[] = $firstRandom;

        // Slots 1–5: remaining performance-based trainers (best first).
        foreach ($perfPool as $row) {
            if (count($trainers) >= 6) {
                break;
            }
            $trainers[] = $row;
        }

        // If we still have fewer than 6 real trainers, pad with placeholders.
        while (count($trainers) < 6) {
            $trainers[] = getBattleTowerPlaceholderTrainerForRegion($region, $level, count($trainers));
        }

        // Slot 6: leader (best record).
        $trainers[] = $leader;
    }

    // Cross-region conversion + debug for real trainers (placeholders have no game_region).
    $count = count($trainers);
    for ($i = 0; $i < $count; $i++) {
        if (isset($trainers[$i]['game_region'])) {
            $sourceRegion = strtolower($trainers[$i]['game_region']);
            $before = $trainers[$i];

            $after = function_exists('bxt_transform_battle_tower_trainer_for_download') ? bxt_transform_battle_tower_trainer_for_download($region, $trainers[$i]) : $trainers[$i];
            // Easy Chat safety: JP -> non-JP shorten of trainer messages.
            if ($sourceRegion === 'j' && $region !== 'j' && function_exists('bxt_shorten_easy_chat_bytes_for_nonj_message')) {
                foreach (['message_start', 'message_win', 'message_lose'] as $msgKey) {
                    if (isset($after[$msgKey]) && is_string($after[$msgKey]) && $after[$msgKey] !== '') {
                        $msgBeforeHex = bin2hex($after[$msgKey]);
                        $after[$msgKey] = bxt_shorten_easy_chat_bytes_for_nonj_message(
                            $after[$msgKey],
                            $sourceRegion,
                            $region
                        );
                        $msgAfterHex = bin2hex($after[$msgKey]);
                        if (defined('BXT_DEBUG_BT_CONVERT') && BXT_DEBUG_BT_CONVERT) {
                            error_log(
                                '[BXT_NAME_CONV_DEBUG] easy_chat shorten bt ' .
                                'key=' . $msgKey .
                                ' src=' . $sourceRegion .
                                ' dest=' . $region .
                                ' before=' . $msgBeforeHex .
                                ' after=' . $msgAfterHex
                            );
                        }
                    }
                }
            }


            if (defined('BXT_DEBUG_BT_CONVERT') && BXT_DEBUG_BT_CONVERT) {
                error_log(
                    'BXT_DEBUG_BT_CONVERT: ' .
                    'download_region=' . $region .
                    ' source_region=' . $sourceRegion .
                    ' room=' . $room .
                    ' level=' . $level .
                    ' slot=' . $i .
                    ' name_before=' . bin2hex($before['name']) .
                    ' name_after=' . bin2hex($after['name']) .
                    ' msg_start_before=' . bin2hex($before['message_start']) .
                    ' msg_start_after=' . bin2hex($after['message_start']) .
                    ' msg_win_before=' . bin2hex($before['message_win']) .
                    ' msg_win_after=' . bin2hex($after['message_win']) .
                    ' msg_lose_before=' . bin2hex($before['message_lose']) .
                    ' msg_lose_after=' . bin2hex($after['message_lose'])
                );
            }


            $trainers[$i] = $after;
        }
    }

    $bxte = ($region !== 'j');
    return encodeBattleTowerRoomData($trainers, $bxte);
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

return true;
}

?>
