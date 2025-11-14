<?php
// SPDX-License-Identifier: MIT

require_once(CORE_PATH . "/database.php");
require_once(CORE_PATH . "/pokemon/func.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_check.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_policy.php");
require_once(CORE_PATH . "/pokemon/battle_tower.php");

/**
 * Battle Tower legality wrapper for submitted records.
 * Decodes the record, runs legality on pokemon1–3, then delegates to battleTowerSubmitRecord.
 * Also logs raw blob length + hex for JP / international format analysis.
 */
function battleTowerSubmitRecord_legality($inputStream, $bxte = false) {
    // Decode full Battle Tower record into associative array
    $data = decodeBattleTowerRecord($inputStream, $bxte);

    // Ensure we have the three pokemon blobs
    $slots = ['pokemon1', 'pokemon2', 'pokemon3'];

    foreach ($slots as $slot) {
        if (!isset($data[$slot]) || $data[$slot] === null) {
            http_response_code(400);
            exit("Missing $slot");
        }

        $blob = $data[$slot];
        if (!is_string($blob)) {
            http_response_code(400);
            exit("Invalid blob for $slot");
        }

        $len = strlen($blob);
        $hex = bin2hex($blob);

        // DEBUG: log raw blob so JP / intl size differences can be mapped
        error_log(sprintf(
            "battle_tower_debug: slot=%s length=%d bytes",
            $slot,
            $len
        ));
        error_log("battle_tower_debug: hex=" . $hex);

        // Legality check via external EXE wrapper
        [$ok, $details] = legality_check_pk2_bytes_with_details(
            $blob,
            function ($msg) { error_log($msg); }
        );

        if (!$ok) {
            http_response_code(400);
            exit("Illegal Pokémon in $slot");
        }
    }

    // All three Pokémon passed legality; delegate to original submit implementation
    return battleTowerSubmitRecord($inputStream, $bxte);
}
