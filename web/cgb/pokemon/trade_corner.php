<?php
ini_set('log_errors', 1);
error_log('BXT_DEBUG_TRADE_CORNER_FILE_LOADED account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
// SPDX-License-Identifier: MIT

require_once(CORE_PATH . "/database.php");
require_once(__DIR__ . '/../../scripts/bxt_decode_helpers.php');
require_once(__DIR__ . '/../../scripts/bxt_value_validation.php');
require_once(CORE_PATH . "/pokemon/bxt_config.php");
require_once(__DIR__ . '/../../scripts/bxt_legality_check.php');
require_once(__DIR__ . "/../../scripts/bxt_legality_policy.php");

/**
 * Handle Trade Corner entry upload.
 * - Decodes the binary blob from the CGB client.
 * - Populates bxt_exchange including *_decode mirror columns.
 * - Runs legality checker and stores a summary in pokemon_decode.
 */
function process_trade_request($region, $request_data) {
    $region = strtolower($region);

    error_log('BXT_DEBUG process_trade_request: entry account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') . ' region=' . $region . ' raw_len=' . strlen($request_data));

    $decoded_data = decode_exchange($region, $request_data, true);
    if (!is_array($decoded_data)) {
        error_log('BXT_DEBUG process_trade_request: decode_exchange returned non-array account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
        return;
    } else {
        error_log('BXT_DEBUG process_trade_request: decoded keys=' . implode(',', array_keys($decoded_data)) . ' account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
    }

        // Load banned / allowed word lists
    $bannedFile = realpath(__DIR__ . "/../../../maint/banned_words.txt")
        ?: (__DIR__ . "/../../../maint/banned_words.txt");
    $banned = bxt_load_banned_words($bannedFile);

    $allowedFile = dirname($bannedFile) . "/allowed_words.txt";
    $allowed = bxt_load_allowed_words($allowedFile);


    // Decode player name using exchange-specific helper, then enforce banned/allowed policy.
    $decoded_name_for_policy = bxt_decode_exchange_player_name($region, $decoded_data["player_name"]);


    if ($decoded_name_for_policy !== '' && bxt_contains_banned($decoded_name_for_policy, $banned, $allowed)) {
        error_log('trade_corner_gateway: banned player name: ' . $decoded_name_for_policy);
        http_response_code(400);
        exit("Banned player name");
    }

    // Decode mail message using per-region Easy Chat and enforce banned/allowed policy.
    $decoded_mail_for_policy = bxt_decode_mail_for_region($region, $decoded_data["mail"]);


    if ($decoded_mail_for_policy !== '' && bxt_contains_banned($decoded_mail_for_policy, $banned, $allowed)) {
        error_log('trade_corner_gateway: banned mail message: ' . $decoded_mail_for_policy);
        http_response_code(400);
        exit("Banned mail message");
    }

// Compute decoded mirror columns for human-readable display
    $offer_gender_decode    = bxt_decode_pokemon_gender_for_region($region, $decoded_data["offer_gender"]);
    $offer_species_decode   = bxt_decode_pokemon_species_for_region($region, $decoded_data["offer_species"]);
    $request_gender_decode  = bxt_decode_pokemon_gender_for_region($region, $decoded_data["req_gender"]);
    $request_species_decode = bxt_decode_pokemon_species_for_region($region, $decoded_data["req_species"]);
    $player_name_decode     = bxt_decode_exchange_player_name($region, $decoded_data["player_name"]);
    $mail_decode            = bxt_decode_mail_for_region($region, $decoded_data["mail"]);

    // Build legality summary text for the Pokémon blob
    $pokemon_decode = null;
    try {
        list($ok_leg, $details) = legality_check_pk2_bytes_with_details(
            $decoded_data["pokemon"],
            function ($msg) { error_log('BXT_DEBUG trade_corner_pkm_legality_summary: account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') . ' ' . $msg); }
        );
        if (!$ok_leg) {
            error_log('trade_corner_gateway: illegal Pokémon blob');
            http_response_code(400);
            exit("Illegal Pokémon");
        }
        if (is_array($details) && $details) {
            // Enforce banned / allowed word policy on Pokémon nickname as well,
            // using the same helper as sweep_all / Battle Tower so allowed_words.txt
            // behaves identically across features.
            if (!bxt_policy_allow_nickname($details, $banned, $allowed)) {
                $nick_dbg = isset($details['nickname']) && is_string($details['nickname']) ? $details['nickname'] : '';
                error_log('trade_corner_gateway: banned pokemon nickname: ' . $nick_dbg);
                http_response_code(400);
                exit("Banned Pokémon nickname");
            }

            // Use the shared human-readable formatter so this matches Battle Tower summaries.
            if (function_exists('bxt_format_legality_summary')) {
                $pokemon_decode = bxt_format_legality_summary($details, $region);
            } elseif (isset($details['summary']) && is_string($details['summary']) && $details['summary'] !== '') {
                $pokemon_decode = $details['summary'];
            } else {
                $pokemon_decode = json_encode($details);
            }
        }
    } catch (\Throwable $e) {
        error_log('BXT_DEBUG trade_corner_pkm_legality_summary_exception: account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') . ' ' . $e->getMessage());
        $pokemon_decode = null;
    }

    
    // Server-side sanity validation of exchange payload
    $validation_errors = [];
    if (!bxt_validate_exchange_row(
        $region,
        $decoded_data["trainer_id"],
        $decoded_data["secret_id"],
        $decoded_data["offer_gender"],
        $decoded_data["req_gender"],
        $decoded_data["offer_species"],
        $decoded_data["pokemon"],
        $decoded_data["mail"],
        $validation_errors
    )) {
        error_log('trade_corner_gateway: value validation failed: ' . json_encode($validation_errors));
        http_response_code(400);
        exit("Invalid exchange payload");
    }

$db = connectMySQL(); // Connect to DION Database

    // All regions now write into the unified `bxt_exchange` table.
    // Region differences are tracked via the `game_region` column.
    error_log('bxt_debug_trade_before_prepare region=' . $region);
    error_log('BXT_DEBUG process_trade_request: before_prepare account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));

    $stmt = $db->prepare(
        "REPLACE INTO `bxt_exchange` (" .
        "game_region, trainer_id, secret_id, " .
        "offer_gender, offer_gender_decode, offer_species, offer_species_decode, " .
        "request_gender, request_gender_decode, request_species, request_species_decode, " .
        "player_name, player_name_decode, " .
        "pokemon, pokemon_decode, " .
        "mail, mail_decode, " .
        "account_id, email" .
        ") VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
    );
    if (!$stmt) {
        error_log('BXT_DEBUG process_trade_request: stmt_prepare_failed account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') . ' ' . $db->error);
        http_response_code(500);
        exit('Failed to prepare statement');
    }

    // email (s), account_id (i), game_region (s), trainer_id (i), secret_id (i),
    // offer_gender (i), offer_gender_decode (s),
    // offer_species (i), offer_species_decode (s),
    // request_gender (i), request_gender_decode (s),
    // request_species (i), request_species_decode (s),
    // player_name (s), pokemon (s), pokemon_decode (s), mail (s), mail_decode (s)
    $stmt->bind_param(
        "siiisisisisssssssis",
        $region,                       // game_region
        $decoded_data["trainer_id"],   // trainer_id
        $decoded_data["secret_id"],    // secret_id
        $decoded_data["offer_gender"], // offer_gender
        $offer_gender_decode,          // offer_gender_decode
        $decoded_data["offer_species"],// offer_species
        $offer_species_decode,         // offer_species_decode
        $decoded_data["req_gender"],   // request_gender
        $request_gender_decode,        // request_gender_decode
        $decoded_data["req_species"],  // request_species
        $request_species_decode,       // request_species_decode
        $decoded_data["player_name"],  // player_name (raw encoded bytes)
        $player_name_decode,           // player_name_decode
        $decoded_data["pokemon"],      // pokemon blob
        $pokemon_decode,               // pokemon_decode
        $decoded_data["mail"],         // mail blob
        $mail_decode,                  // mail_decode
        $_SESSION["userId"],           // account_id
        $decoded_data["email"]         // email
    );

    if (!$stmt->execute()) {
        error_log('BXT_DEBUG process_trade_request: execute_failed account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') . ' ' . $stmt->error);
        http_response_code(500);
        exit('Failed to insert into bxt_exchange');
    }
    error_log('BXT_DEBUG process_trade_request: execute_ok account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
}

/**
 * Handle Trade Corner cancellation upload.
 * - Decodes only the header / IDs (no blobs) and deletes the matching row.
 */
function process_cancel_request($region, $request_data) {
    $region = strtolower($region);

    if (function_exists('bxt_get_config_array')) {
        $cfg = bxt_get_config_array();
        if (isset($cfg['trade_corner_enabled']) && !$cfg['trade_corner_enabled']) {
            http_response_code(503);
            exit('Trade Corner is currently disabled.');
        }
    }

    error_log('BXT_DEBUG process_cancel_request: entry account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') . ' region=' . $region . ' raw_len=' . strlen($request_data));

    // Decode only the header; no Pokémon/mail blobs required for cancellation.
    $decoded_data = decode_exchange($region, $request_data, false);
    if (!is_array($decoded_data)) {
        error_log('BXT_DEBUG process_cancel_request: decode_exchange returned non-array account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
        http_response_code(400);
        exit('Invalid cancel payload');
    }

    $trainerId = $decoded_data["trainer_id"] ?? null;
    $secretId  = $decoded_data["secret_id"] ?? null;

    if ($trainerId === null || $secretId === null) {
        error_log('BXT_DEBUG process_cancel_request: missing trainer_id or secret_id account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
        http_response_code(400);
        exit('Missing identifiers for cancellation');
    }

    if (!isset($_SESSION["userId"])) {
        http_response_code(401);
        exit('Not authenticated');
    }

    $accountId = $_SESSION["userId"];

    $db = connectMySQL();

    $stmt = $db->prepare(
        "DELETE FROM bxt_exchange
         WHERE game_region = ?
           AND trainer_id = ?
           AND secret_id = ?
           AND account_id = ?"
    );
    if (!$stmt) {
        error_log('BXT_DEBUG process_cancel_request: stmt_prepare_failed account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') . ' ' . $db->error);
        http_response_code(500);
        exit('Failed to prepare cancel statement');
    }

    $stmt->bind_param(
        "siii",
        $region,
        $trainerId,
        $secretId,
        $accountId
    );

    if (!$stmt->execute()) {
        error_log('BXT_DEBUG process_cancel_request: execute_failed account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') . ' ' . $stmt->error);
        http_response_code(500);
        exit('Failed to cancel Trade Corner offer');
    }

    error_log('BXT_DEBUG process_cancel_request: execute_ok affected_rows=' . $stmt->affected_rows . ' account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none'));
}

/**
 * Resolve the set of source regions to query for Trade Corner offers.
 *
 * Uses:
 *   - region_groups['trade_corner'] for grouping
 *   - game_region_map-based allowed regions
 */
function bxt_get_trade_corner_source_regions(string $region): array
{
    $region = strtolower($region);

    // Start from the logical linking group for Trade Corner.
    if (function_exists('bxt_regions_linked_for_feature')) {
        $pooled = bxt_regions_linked_for_feature('trade_corner', $region);
        if (is_array($pooled) && !empty($pooled)) {
            return $pooled;
        }
    }

    return [$region];
}

/**
 * List Trade Corner offers for a given region, applying cross-region
 * pooling via region_groups['trade_corner'] and game_region_map-based allowed regions.
 *
 * This returns a PHP array of rows from bxt_exchange; outer scripts are free
 * to format this as HTML, JSON, or CGB binary.
 */
function tradeCornerListOffers(string $region, int $limit = 100): array
{
    $region = strtolower($region);

    // Respect the global feature toggle and allowed regions for this feature.
    if (function_exists('bxt_get_config_array')) {
        $cfg = bxt_get_config_array();
        if (isset($cfg['trade_corner_enabled']) && !$cfg['trade_corner_enabled']) {
            return [];
        }
    }
    $db = connectMySQL();

    $sourceRegions = bxt_get_trade_corner_source_regions($region);
    if (empty($sourceRegions)) {
        // Feature disabled globally or for this region: no offers.
        return [];
    }

    // Build a safe IN (...) list for game_region using prepared placeholders.
    $placeholders = implode(',', array_fill(0, count($sourceRegions), '?'));

    $sql = "SELECT
                game_region,
                trainer_id,
                secret_id,
                offer_gender,
                offer_gender_decode,
                offer_species,
                offer_species_decode,
                request_gender,
                request_gender_decode,
                request_species,
                request_species_decode,
                player_name,
                player_name_decode,
                pokemon,
                pokemon_decode,
                mail,
                mail_decode,
                account_id,
                email,
                timestamp
            FROM bxt_exchange
            WHERE game_region IN ($placeholders)
            ORDER BY timestamp DESC
            LIMIT ?";

    $stmt = $db->prepare($sql);
    if (!$stmt) {
        error_log('BXT_DEBUG tradeCornerListOffers: stmt_prepare_failed account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') . ' ' . $db->error);
        return [];
    }

    // Bind regions (all strings) followed by the integer limit.
    $types = str_repeat('s', count($sourceRegions)) . 'i';
    $params = $sourceRegions;
    $params[] = $limit;

    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $rows = fancy_get_result($stmt);
    if (!is_array($rows)) {
        return [];
    }
    return $rows;
}

function decode_exchange($region, $stream, $full = true) {
    $postdata = fopen($stream, "rb");
    $decData = array();

    // $00..$1D: DION e-mail address (null-terminated ASCII, 30 characters max.)
    $decData["email"] = str_replace(chr(0), '', fread($postdata, 0x1E));

    // $1E..$1F: Trainer ID (2 bytes)
    $decData["trainer_id"] = unpack("n", fread($postdata, 0x2))[1];

    // $20..$21: Secret ID (2 bytes)
    $decData["secret_id"] = unpack("n", fread($postdata, 0x2))[1];

    // $22: Offered Pokémon’s gender
    $decData["offer_gender"] = unpack("C", fread($postdata, 0x1))[1];

    // $23: Offered Pokémon’s species
    $decData["offer_species"] = unpack("C", fread($postdata, 0x1))[1];

    // $24: Requested Pokémon’s gender
    $decData["req_gender"] = unpack("C", fread($postdata, 0x1))[1];

    // $25: Requested Pokémon’s species
    $decData["req_species"] = unpack("C", fread($postdata, 0x1))[1];

    // $26..: Name of trainer who requests the trade
    if ($full) {
        $name_len = ($region === "j") ? 0x5 : 0x7;
        $decData["player_name"] = fread($postdata, $name_len);
    } else {
        $decData["player_name"] = null;
        // Skip over name bytes for alignment
        $name_len = ($region === "j") ? 0x5 : 0x7;
        fread($postdata, $name_len);
    }

    if ($full) {
        // $2B..: Pokémon data
        $pkm_len = ($region === "j") ? 0x3A : 0x41;
        $decData["pokemon"] = fread($postdata, $pkm_len);

        // $65..: Held mail data
        $mail_len = ($region === "j") ? 0x2A : 0x2F;
        $decData["mail"] = fread($postdata, $mail_len);
    } else {
        $decData["pokemon"] = null;
        $decData["mail"] = null;
    }

    return $decData;
}
