<?php
ini_set('log_errors', 1);
error_log('BXT_DEBUG_BT_LEG_FILE_LOADED account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
// SPDX-License-Identifier: MIT

require_once(CORE_PATH . "/database.php");
require_once(CORE_PATH . "/pokemon/func.php");
require_once(__DIR__ . "/../../scripts/bxt_decode_helpers.php");
require_once(__DIR__ . "/../../scripts/bxt_value_validation.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_check.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_policy.php");
require_once(CORE_PATH . "/pokemon/battle_tower.php");

/**
 * Decode a player's Easy Chat message blob for a specific game_region.
 * Input from the DB is a binary(12) field containing six 16-bit Easy Chat
 * phrase codes in game order. We convert to hex and delegate to the shared
 * per-region Easy Chat decoder.
 */
function bxt_decode_player_message_for_region($game_region, $raw_binary) {
    if ($raw_binary === null || $raw_binary === '') {
        return '';
    }

    // Convert binary to uppercase hex string
    $bytes = unpack('C*', $raw_binary);
    $hex   = '';
    foreach ($bytes as $b) {
        $hex .= strtoupper(str_pad(dechex($b), 2, '0', STR_PAD_LEFT));
    }

    // Determine whether a global display override is active.
    // Easy Chat messages are special:
    // - primary text is decoded using the override language (if set)
    // - original language text is appended in parentheses.
    $primary_region   = $game_region;
    $secondary_region = null;

    if (function_exists('bxt_get_global_override_region')) {
        $override = bxt_get_global_override_region();
        if (is_string($override) && $override !== '') {
            $ov = strtolower($override[0]);
            if ($ov !== '' && $ov !== strtolower((string)$game_region)) {
                $primary_region   = $ov;
                $secondary_region = $game_region;
            }
        }
    }

    // Decode primary language text
    $primary = bxt_decode_easy_chat_for_region($primary_region, $hex);
    if ($primary === null) {
        $primary = '';
    }

    // Optionally decode original language text
    $secondary = '';
    if ($secondary_region !== null) {
        $secondary = bxt_decode_easy_chat_for_region($secondary_region, $hex);
        if ($secondary === null) {
            $secondary = '';
        }
    }

    // Format result according to availability:
    // - override set and both decodes: "override (original)"
    // - only one decode: just that text
    // - nothing decoded: fall back to raw hex
    if ($primary !== '' && $secondary !== '' && $primary !== $secondary) {
        return $primary . ' (' . $secondary . ')';
    } elseif ($primary !== '') {
        return $primary;
    } elseif ($secondary !== '') {
        return $secondary;
    }

    return $hex;
}


/**
 * Battle Tower legality wrapper for submitted records.
 * - Decodes record once
 * - Runs legality + banned-word checks
 * - Inserts directly into bxtj/bxte tables
 * If anything fails, returns HTTP 400 and logs a detailed reason.
 */
function battleTowerSubmitRecord_legality($inputStream, $game_region) {
    // Normalise region + determine decode variant (BXTE vs BXTJ layout)
    $game_region = strtolower($game_region);
    $bxte = ($game_region !== 'j');

    // Decode
    $data = decodeBattleTowerRecord($inputStream, $bxte);

    foreach (['pokemon1', 'pokemon2', 'pokemon3'] as $slot) {
        if (!isset($data[$slot]) || $data[$slot] === null) {
            error_log("bt_legality_error: missing slot $slot");
            http_response_code(403);
            exit("Missing data for $slot");
        }
    }

    // Extra safety: ensure exactly 3 Pokémon slots and no unexpected extras
    $extraSlots = [];
    foreach (array_keys($data) as $key) {
        if (preg_match('/^pokemon[0-9]+$/', (string)$key) && !in_array($key, ['pokemon1', 'pokemon2', 'pokemon3'], true)) {
            $extraSlots[] = $key;
        }
    }
    if (!empty($extraSlots)) {
        error_log('bt_legality_error: unexpected Pokémon slots: ' . implode(',', $extraSlots));
        http_response_code(403);
        exit('Exactly 3 Pokémon are required');
    }


    // Load banned-word list
    $bannedFile = realpath(__DIR__ . "/../../../maint/banned_words.txt");
    if ($bannedFile === false) {
        $bannedFile = __DIR__ . "/../../../maint/banned_words.txt";
    }
    $banned = bxt_load_banned_words($bannedFile);

    // Load allowed-word whitelist
    $allowedFile = dirname($bannedFile) . "/allowed_words.txt";
    $allowed = bxt_load_allowed_words($allowedFile);

    // Encoding table for name/messages
    $enc_hint = $bxte ? 'BXTE' : 'BXTJ';
    $table_id = bxt_encoding_table_id($enc_hint);

    $check_msg = function (string $raw, string $label) use ($banned, $allowed, $table_id) {
        if ($raw === '') return;
        $txt = bxt_decode_text_table($raw, $table_id);
        if ($txt === '') return;
        if (bxt_contains_banned($txt, $banned, $allowed)) {
            error_log("bt_legality_error: banned text in {$label}: '{$txt}'");
            http_response_code(403);
            exit("Banned text in {$label}");
        }
    };

	// Trainer name check
	if (isset($data['name']) && is_string($data['name'])) {
		$raw = $data['name'];

	/*
	 * Treat 0x50 as a terminator for trainer names.
	 * Stop decoding at first 0x50.
	 */
	$pos = strpos($raw, "\x50");
	if ($pos !== false) {
		$raw = substr($raw, 0, $pos);
	}

	$txt = bxt_decode_text_table($raw, $table_id);

		if ($txt !== '' && bxt_contains_banned($txt, $banned)) {
			error_log("bt_legality_error: banned text in trainer name: '{$txt}'");
			http_response_code(403);
			exit("Banned text in trainer name");
		}
	}

    // Pokémon legality + nickname/OT policies
    $slotNames = [
        'pokemon1' => 'slot 1',
        'pokemon2' => 'slot 2',
        'pokemon3' => 'slot 3',
    ];

    // Enforce unique species across all three slots
    $speciesSeen = [];

    foreach ($slotNames as $key => $label) {
        $blob = $data[$key];

        // Extra debug
        $len = strlen($blob);
        error_log(sprintf("bt_legality_debug: %s length=%d", $label, $len));

        [$ok, $details] = legality_check_pk2_bytes_with_details(
            $blob,
            function ($msg) { error_log("bt_legality_debug: $msg"); }
        );

        if (!$ok) {
            error_log("bt_legality_error: illegal Pokémon in {$label}");
            http_response_code(403);
            exit("Illegal Pokémon in {$label}");
        }

        if (!bxt_policy_allow_nickname($details, $banned, $allowed)) {
            error_log("bt_legality_error: banned nickname in {$label}");
            http_response_code(403);
            exit("Banned nickname in {$label}");
        }

        if (!bxt_policy_allow_ot($details, $banned, $allowed)) {
            error_log("bt_legality_error: banned OT in {$label}");
            http_response_code(403);
            exit("Banned OT in {$label}");
        }

        // Enforce unique species: no duplicate Pokémon species across the team
        $speciesKey = null;
        if (isset($details['speciesId']) && $details['speciesId'] !== null) {
            $speciesKey = 'id:' . (int)$details['speciesId'];
        } elseif (isset($details['species']) && $details['species'] !== null && $details['species'] !== '') {
            $speciesKey = 'name:' . strtolower((string)$details['species']);
        }

        if ($speciesKey !== null) {
            if (isset($speciesSeen[$speciesKey])) {
                error_log("bt_legality_error: duplicate species in {$label} (matches {$speciesSeen[$speciesKey]})");
                http_response_code(403);
                exit('Duplicate Pokémon species not allowed');
            }
            $speciesSeen[$speciesKey] = $label;
        }
    }

    // DB insert into unified international table
    
    // Server-side sanity validation of Battle Tower record payload
    $validation_errors = [];
    if (!bxt_validate_battle_tower_record_row(
        $game_region,
        $data['message_start'],
        $data['message_win'],
        $data['message_lose'],
        $data['num_trainers_defeated'],
        $data['num_turns_required'],
        $data['damage_taken'],
        $data['num_fainted_pokemon'],
        $data['level'],
        $validation_errors
    )) {
        error_log('bt_legality_error: value validation failed: ' . json_encode($validation_errors));
        http_response_code(403);
        exit('Invalid Battle Tower record payload');
    }

$db = connectMySQL();

    // Build decoded mirror columns for Battle Tower records
    // level_decode uses the shared 'tower_level' table
    $level_decode = bxt_decode_simple_table('tower_level', $data['level']);

    // player_name_decode: JP vs EN collapse as per spec
    $player_name_decode = bxt_decode_player_name_for_region($game_region, $data['name']);

    // class_decode: decode via per-game trainer_class table
    $trainer_class_decode = bxt_decode_trainer_class_for_region($game_region, $data['class']);

    // Pokémon decode: summarized legality view per slot
    $pokemon1_decode = bxt_summarize_pk2_blob($data['pokemon1']);
    $pokemon2_decode = bxt_summarize_pk2_blob($data['pokemon2']);
    $pokemon3_decode = bxt_summarize_pk2_blob($data['pokemon3']);

    // Easy Chat messages -> decode via language-specific easy_chat table
    $message_start_decode = bxt_decode_player_message_for_region($game_region, $data['message_start']);
    $message_win_decode   = bxt_decode_player_message_for_region($game_region, $data['message_win']);
    $message_lose_decode  = bxt_decode_player_message_for_region($game_region, $data['message_lose']);

    $db = connectMySQL();

    $sql = "INSERT INTO bxt_battle_tower_records (
            game_region,
            room,
            level,
            level_decode,
            trainer_id,
            secret_id,
            player_name,
            player_name_decode,
            class,
            class_decode,
            pokemon1,
            pokemon1_decode,
            pokemon2,
            pokemon2_decode,
            pokemon3,
            pokemon3_decode,
            message_start,
            message_start_decode,
            message_win,
            message_win_decode,
            message_lose,
            message_lose_decode,
            num_trainers_defeated,
            num_turns_required,
            damage_taken,
            num_fainted_pokemon,
            account_id
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    error_log('BXT_DEBUG battle_tower_legality: before_prepare account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        error_log("bt_legality_error: failed to prepare insert: " . $db->error);
        http_response_code(500);
        exit("Failed to prepare Battle Tower insert");
    }

    // Types: s i i s i i s s i s s s s s s s s s s s s i i i i i
    $stmt->bind_param(
        "siisiississsssssssssssiiiii",
        $game_region,
        $data['room'],
        $data['level'],
        $level_decode,
        $data['trainer_id'],
        $data['secret_id'],
        $data['name'],
        $player_name_decode,
        $data['class'],
        $trainer_class_decode,
        $data['pokemon1'],
        $pokemon1_decode,
        $data['pokemon2'],
        $pokemon2_decode,
        $data['pokemon3'],
        $pokemon3_decode,
        $data['message_start'],
        $message_start_decode,
        $data['message_win'],
        $message_win_decode,
        $data['message_lose'],
		$message_lose_decode,
		$data['num_trainers_defeated'],
		$data['num_turns_required'],
		$data['damage_taken'],
		$data['num_fainted_pokemon'],
		$_SESSION["userId"]
    );


    if (!$stmt->execute()) {
        error_log("bt_legality_error: failed to execute insert: " . $stmt->error);
        http_response_code(500);
        exit("Failed to execute Battle Tower insert");
    }

    error_log('bxt_debug_bt_execute_ok rows=' . $stmt->affected_rows);
    return true;
}