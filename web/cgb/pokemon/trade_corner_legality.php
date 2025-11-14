<?php
require_once(CORE_PATH . "/database.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_check.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_policy.php");
require_once(CORE_PATH . "/pokemon/trade_corner.php");

function process_trade_request_legality($region, $request_data) {
    $decoded = decode_exchange($region, $request_data, true);
    if (!isset($decoded['pokemon']) || $decoded['pokemon'] === NULL) {
        http_response_code(400); exit("Missing Pokémon blob");
    }

    $banned = bxt_load_banned_words(
    realpath(__DIR__ . "/../../../maint/banned_words.txt")
        ?: (__DIR__ . "/../../../maint/banned_words.txt")
);


    try {
        [$ok, $det] = legality_check_pk2_bytes_with_details($decoded['pokemon'], function($m){ error_log($m); });
    } catch (Throwable $e) {
        http_response_code(500); exit("Legality checker error");
    }

    if (!$ok) {
        http_response_code(400); exit("Illegal Pokémon");
    }
    if ((int)($det['speciesId'] ?? 0) > 251) {
        http_response_code(400); exit("Invalid species");
    }
    if (!empty($det['isEgg'])) {
        http_response_code(400); exit("Egg not allowed");
    }

    if (!bxt_policy_allow_nickname($det, $banned)) {
        http_response_code(400); exit("Banned nickname");
    }
    if (!bxt_policy_allow_ot($det, $banned)) {
        http_response_code(400); exit("Banned OT");
    }

    $table_id = bxt_encoding_table_id($region);
    if (isset($decoded['mail']) && $decoded['mail'] !== NULL) {
        if (!bxt_policy_allow_mail_table($decoded['mail'], $table_id, $banned)) {
            http_response_code(400); exit("Banned mail content");
        }
    }

    return process_trade_request($region, $request_data);
}
