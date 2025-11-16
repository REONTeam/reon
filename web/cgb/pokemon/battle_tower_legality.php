<?php
// SPDX-License-Identifier: MIT

require_once(CORE_PATH . "/database.php");
require_once(CORE_PATH . "/pokemon/func.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_check.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_policy.php");
require_once(CORE_PATH . "/pokemon/battle_tower.php");

/**
 * Battle Tower legality wrapper for submitted records.
 * - Decodes record once
 * - Runs legality + banned-word checks
 * - Inserts directly into bxtj/bxte tables
 * If anything fails, returns HTTP 400 and logs a detailed reason.
 */
function battleTowerSubmitRecord_legality($inputStream, $bxte = false) {
    // Decode
    $data = decodeBattleTowerRecord($inputStream, $bxte);

    foreach (['pokemon1', 'pokemon2', 'pokemon3'] as $slot) {
        if (!isset($data[$slot]) || $data[$slot] === null) {
            error_log("bt_legality_error: missing slot $slot");
            http_response_code(400);
            exit("Missing data for $slot");
        }
    }

    // Load banned-word list
    $bannedFile = realpath(__DIR__ . "/../../../maint/banned_words.txt");
    if ($bannedFile === false) {
        $bannedFile = __DIR__ . "/../../../maint/banned_words.txt";
    }
    $banned = bxt_load_banned_words($bannedFile);

    // Encoding table for name/messages
    $enc_hint = $bxte ? 'BXTE' : 'BXTJ';
    $table_id = bxt_encoding_table_id($enc_hint);

    $check_msg = function (string $raw, string $label) use ($banned, $table_id) {
        if ($raw === '') return;
        $txt = bxt_decode_text_table($raw, $table_id);
        if ($txt === '') return;
        if (bxt_contains_banned($txt, $banned)) {
            error_log("bt_legality_error: banned text in {$label}: '{$txt}'");
            http_response_code(400);
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
			http_response_code(400);
			exit("Banned text in trainer name");
		}
	}

    // Pokémon legality + nickname/OT policies
    $slotNames = [
        'pokemon1' => 'slot 1',
        'pokemon2' => 'slot 2',
        'pokemon3' => 'slot 3',
    ];

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
            http_response_code(400);
            exit("Illegal Pokémon in {$label}");
        }

        if (!bxt_policy_allow_nickname($details, $banned)) {
            error_log("bt_legality_error: banned nickname in {$label}");
            http_response_code(400);
            exit("Banned nickname in {$label}");
        }

        if (!bxt_policy_allow_ot($details, $banned)) {
            error_log("bt_legality_error: banned OT in {$label}");
            http_response_code(400);
            exit("Banned OT in {$label}");
        }
    }

    // DB insert
    $db = connectMySQL();
    $table = $bxte ? "bxte_battle_tower_records" : "bxtj_battle_tower_records";

    $sql = "INSERT INTO {$table} (
                room,
                level,
                email,
                trainer_id,
                secret_id,
                name,
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
                account_id
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        error_log("bt_legality_error: failed to prepare insert: " . $db->error);
        http_response_code(500);
        exit("Failed to prepare Battle Tower insert");
    }

    $stmt->bind_param(
        "iisiissssssssiiiii",
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
        $_SESSION["userId"]
    );

    if (!$stmt->execute()) {
        error_log("bt_legality_error: failed to insert record: " . $stmt->error);
        http_response_code(500);
        exit("Failed to insert Battle Tower record");
    }

    return true;
}
