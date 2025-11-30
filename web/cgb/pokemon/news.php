<?php
// SPDX-License-Identifier: MIT
require_once(CORE_PATH . "/database.php");
require_once(CORE_PATH . "/pokemon/func.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_policy.php");
require_once(CORE_PATH . "/pokemon/bxt_config.php");
require_once(__DIR__ . "/../../scripts/bxt_decode_helpers.php");
require_once(__DIR__ . "/../../scripts/bxt_value_validation.php");
require_once(__DIR__ . "/../../scripts/bxt_name_conversion.php");


/**
 * Decode a player's Easy Chat ranking message with respect to the
 * global_table_display override (if any).
 *
 * Behaviour mirrors the Battle Tower message_start decoding:
 * - primary text is decoded using the override region if configured
 * - original region text is optionally appended in parentheses.
 */
function bxt_decode_player_message_for_ranking($game_region, $raw_binary) {
    if ($raw_binary === null || $raw_binary === '') {
        return '';
    }

    // Convert binary payload to an uppercase hex string.
    $bytes = unpack('C*', $raw_binary);
    $hex   = '';
    foreach ($bytes as $b) {
        $hex .= strtoupper(str_pad(dechex($b), 2, '0', STR_PAD_LEFT));
    }

    // By default, decode using the game's own region.
    $primary_region   = $game_region;
    $secondary_region = null;

    // If a global override is configured, prefer that as the primary language
    // and keep the original region as a secondary line.
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

    // Decode primary language text.
    $primary = bxt_decode_easy_chat_for_region($primary_region, $hex);
    if ($primary === null) {
        $primary = '';
    }

    // Optionally decode original language text.
    $secondary = '';
    if ($secondary_region !== null) {
        $secondary = bxt_decode_easy_chat_for_region($secondary_region, $hex);
        if ($secondary === null) {
            $secondary = '';
        }
    }

    // Formatting:
    // - If both decode, show "primary (secondary)".
    // - If only one decodes, show that one.
    // - If neither decodes, fall back to hex.
    if ($primary !== '' && $secondary !== '') {
        return sprintf('%s (%s)', $primary, $secondary);
    } elseif ($primary !== '') {
        return $primary;
    } elseif ($secondary !== '') {
        return $secondary;
    }

    return $hex;
}

function get_sram_structure($region) {
	return array(
		"trainer_id" => array(
			"bank" => 1,
			"address" => 0xA009,
			"size" => 2
		),
		"secret_id" => array(
			"bank" => 1,
			"address" => $region == "j" ? 0xA3BA : 0xA3D8,
			"size" => 2
		),
		"name" => array(
			"bank" => 1,
			"address" => 0xA00B,
			"size" => $region == "j" ? 5 : 7
		),
		"gender" => array(
			"bank" => $region == "j" ? 4 : 1,
			"address" => $region == "j" ? 0xA000 : 0xBE3D,
			"size" => 1
		),
		"age" => array(
			"bank" => $region == "j" ? 4 : 1,
			"address" => $region == "j" ? 0xA001 : 0xBE3E,
			"size" => 1
		),
		"region" => array(
			"bank" => $region == "j" ? 4 : 1,
			"address" => $region == "j" ? 0xA002 : 0xBE3F,
			"size" => 1
		),
		"zip" => array(
			"bank" => $region == "j" ? 4 : 1,
			"address" => $region == "j" ? 0xA003 : 0xBE40,
			"size" => $region == "j" ? 2 : 3 // we only need to know the first few digits
		),
		"message" => array(
			"bank" => 4,
			"address" => 0xA007,
			"size" => $region == "j" ? 12 : 8
		),
	);
}

function get_news_parameters($region) {
    $db = connectMySQL();
    $stmt = $db->prepare(
        "select id,
                ranking_category_1,
                ranking_category_2,
                ranking_category_3,
                message,
                md5(news_binary) as md5_news_binary,
                octet_length(news_binary) as octet_length_news_binary
           from bxt_news
          where game_region = ?
          order by id desc
          limit 1"
    );
    $stmt->bind_param("s", $region);
    $stmt->execute();
    $rows = fancy_get_result($stmt);
    if (!$rows || count($rows) === 0) {
        return null;
    }
    $row = $rows[0];

    // Backwards compatibility: other code still expects region-suffixed keys.
    $row["message_" . $region] = $row["message"];
    $row["md5(news_binary_" . $region . ")"] = $row["md5_news_binary"];
    $row["octet_length(news_binary_" . $region . ")"] = $row["octet_length_news_binary"];

    return $row;
}



/**
 * Resolve which game_region codes are allowed in rankings for a given
 * download region based on bxt_config['region_groups']['news'].
 * If no config is present, only the download region itself is allowed.
 */
function bxt_news_allowed_regions($region) {
    $region = strtolower(trim((string)$region));
    $allowed = [$region];

    // Read pools directly from global $bxt_config to avoid any wrapper
    // function inconsistencies. This is the same array you edit in
    // CORE_PATH . "/pokemon/bxt_config.php".
    if (isset($GLOBALS['bxt_config']) &&
        is_array($GLOBALS['bxt_config']) &&
        isset($GLOBALS['bxt_config']['region_groups']) &&
        isset($GLOBALS['bxt_config']['region_groups']['news']) &&
        is_array($GLOBALS['bxt_config']['region_groups']['news'])) {

        foreach ($GLOBALS['bxt_config']['region_groups']['news'] as $pool) {
            if (!is_array($pool)) {
                continue;
            }
            $normalized = array_map('strtolower', $pool);
            if (in_array($region, $normalized, true)) {
                $allowed = $normalized;
                break;
            }
        }

        // Debug what config we actually saw.
        error_log(
            'BXT_DEBUG_NEWS_ALLOWED: download_region=' . $region .
            ' news_groups=' . json_encode($GLOBALS['bxt_config']['region_groups']['news']) .
            ' allowed=' . implode(',', $allowed)
        );
    }

    if (!in_array($region, $allowed, true)) {
        $allowed[] = $region;
    }

    $allowed = array_values(array_unique(array_map('strtolower', $allowed)));
    return $allowed;
}

/**
 * Final defensive filter for ranking rows based on bxt_config pools.
 * Only rows whose game_region is allowed for the download region survive.
 */
function bxt_filter_ranking_rows_by_news_config($rows, $download_region) {
    if (!is_array($rows) || empty($rows)) {
        return [];
    }

    $download_region = strtolower(trim((string)$download_region));

    // Default: only the download region is allowed.
    $allowed = [$download_region];
    $newsGroups = null;

    // Prefer the local CGB bxt_config.php (the one you edit under web/cgb/pokemon).
    // This ensures we are using the same region_groups['news'] you actually maintain.
    $local_cfg = null;
    if (file_exists(__DIR__ . '/bxt_config.php')) {
        include __DIR__ . '/bxt_config.php';
        if (isset($bxt_config) && is_array($bxt_config)) {
            $local_cfg = $bxt_config;
        }
    }

    if (is_array($local_cfg) &&
        isset($local_cfg['region_groups']) &&
        isset($local_cfg['region_groups']['news']) &&
        is_array($local_cfg['region_groups']['news'])) {

        $newsGroups = $local_cfg['region_groups']['news'];

        foreach ($newsGroups as $pool) {
            if (!is_array($pool)) {
                continue;
            }
            $normalized = array_map('strtolower', $pool);
            if (in_array($download_region, $normalized, true)) {
                $allowed = $normalized;
                break;
            }
        }
    }

    if (!in_array($download_region, $allowed, true)) {
        $allowed[] = $download_region;
    }

    $allowed = array_values(array_unique(array_map('strtolower', $allowed)));

    // Apply filter.
    $out = [];
    foreach ($rows as $r) {
        if (!is_array($r)) {
            continue;
        }
        $gr = '';
        if (isset($r['game_region'])) {
            $gr = strtolower((string)$r['game_region']);
        }
        if (in_array($gr, $allowed, true)) {
            $out[] = $r;
        }
    }

    // Debug: log exactly what config we saw and what we allowed.
    $newsGroupsJson = $newsGroups !== null ? json_encode($newsGroups) : 'null';
    error_log(
        'BXT_DEBUG_NEWS_CONFIG_FILTER: download_region=' . $download_region .
        ' news_groups=' . $newsGroupsJson .
        ' allowed=' . implode(',', $allowed) .
        ' before=' . count($rows) .
        ' after=' . count($out)
    );

    return $out;
}


function get_news_parameters_bin($region) {
    $news_param = get_news_parameters($region);
    if ($news_param === null) {
        return null;
    }

    $category_info = get_ranking_category_info($news_param);

    // news id, for now a hash of the news binary (first 12 bytes of MD5)
    $out = hex2bin(substr($news_param["md5_news_binary"], 0, 24));

    // message displayed in the lower text box before actually downloading the news
    if ($news_param["message_" . $region] != "") {
        $out .= $news_param["message_" . $region];
    } else {
        // minimal placeholder to avoid glitches when no message is set
        $out .= "\x50\x50";
    }
    // terminator
    $out .= "\x50";

    // address to store rankings data at: SRAM base + actual news length
    $news_length = $news_param["octet_length_news_binary"];
    $out .= pack("v", 0xA000 + $news_length);

    // size of records for each rankings table
    $out .= pack("v", sizeof($category_info) * 2 * 3);
    foreach ($category_info as $category) {
        $base = ($region == "j" ? 24 : 20) + $category["size"];
        $out .= pack("v", $base);
        $out .= pack("v", $base);
        $out .= pack("v", $base);
    }

    // rankings submit configuration
    $sram = get_sram_structure($region);
    foreach (["trainer_id", "secret_id", "name", "gender", "age", "region", "zip", "message"] as $param) {
        $out .= pack("C", $sram[$param]["bank"])
              . pack("v", $sram[$param]["address"])
              . pack("C", $sram[$param]["size"]);
    }
    foreach ($category_info as $category) {
        $out .= pack("C", 5)
              . $category["ram_address"]
              . pack("C", $category["size"]);
    }
    $out .= "\xFF";

    // rankings query configuration
    foreach (["region", "zip"] as $param) {
        $out .= $param
              . "\x50"
              . pack("C", $sram[$param]["bank"])
              . pack("v", $sram[$param]["address"])
              . pack("C", $sram[$param]["size"]);
    }
    foreach (["trainer_id", "secret_id"] as $param) {
        $out .= "my_" . $param
              . "\x50"
              . pack("C", $sram[$param]["bank"])
              . pack("v", $sram[$param]["address"])
              . pack("C", $sram[$param]["size"]);
    }
    $out .= "my_account_id"
          . "\x50"
          . pack("C", 0)
          . pack("v", 0xDE02)
          . pack("C", 4);
    $out .= "\x50";

    return $out;
}


function get_news_file($region) {
    $db = connectMySQL();
    $stmt = $db->prepare(
        "select news_binary
           from bxt_news
          where game_region = ?
          order by id desc
          limit 1"
    );
    $stmt->bind_param("s", $region);
    $stmt->execute();
    $rows = fancy_get_result($stmt);
    if (!$rows || count($rows) === 0) {
        return null;
    }
    return $rows[0]["news_binary"];
}


function get_ranking_category_info($news_param) {
	$category_ids = [$news_param["ranking_category_1"], $news_param["ranking_category_2"], $news_param["ranking_category_3"]];
	$arr = join(",", array_fill(0, count($category_ids), "?"));
	$db = connectMySQL();
	$stmt = $db->prepare("select id, name, ram_address, size from bxt_ranking_categories where id in (".$arr.") order by field (id, ".$arr.")");
	$stmt->bind_param(str_repeat("i", count($category_ids) * 2), ...array_merge($category_ids, $category_ids));
	$stmt->execute();
	return fancy_get_result($stmt);
}


function set_ranking($region, $content, $length) {
    // Normalise region code.
    $region = strtolower(trim($region));

    $is_allowed   = true;
    $allowed_news = [$region];
    $cfg          = null;

    // Global feature toggle.
    if (function_exists('bxt_get_config_array')) {
        $cfg = bxt_get_config_array();
        if (isset($cfg['news_ranking_enabled']) && !$cfg['news_ranking_enabled']) {
            $is_allowed = false;
        }
    }

    if (!$is_allowed) {
        http_response_code(403);
        exit('News ranking is not available for this region.');
    }

    // Expand news pooling based on region_groups['news'].
    if (function_exists('bxt_regions_linked_for_feature')) {
        $pooled = bxt_regions_linked_for_feature('news', $region);
        if (is_array($pooled) && !empty($pooled)) {
            $allowed_news = $pooled;
        }
    }

    // Canonical news region for this pool.
    // All members of a pooled group attach their scores to the same bxt_news.id.
    $news_region = strtolower($allowed_news[0]);

    // SRAM layout depends on the actual game region (J vs non-J),
    // while news parameters are keyed by the canonical news region.
    $sram          = get_sram_structure($region);
    $news_param    = get_news_parameters($news_region);
    if ($news_param === null) {
        // No news configured for this pool; nothing to do.
        return;
    }
    $category_info = get_ranking_category_info($news_param);

    error_log(
        'account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') .
        ' region=' . $region .
        ' news_region=' . $news_region .
        ' length=' . $length .
        ' news_id=' . (isset($news_param['id']) ? $news_param['id'] : 'null') .
        ' categories=' . count($category_info)
    );

    // sanity check
    $expected_data_size = 0;
    foreach (["trainer_id", "secret_id", "name", "gender", "age", "region", "zip", "message"] as $param) {
        $expected_data_size += $sram[$param]["size"];
    }
    foreach ($category_info as $category) {
        $expected_data_size += $category["size"];
    }
    if ($length != $expected_data_size) {
        error_log(
            'BXT_DEBUG_NEWS_SET_RANKING_LENGTH_MISMATCH: ' .
            'account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') .
            ' region=' . $region .
            ' news_region=' . $news_region .
            ' length=' . $length .
            ' expected=' . $expected_data_size .
            ' news_id=' . (isset($news_param['id']) ? $news_param['id'] : 'null')
        );
        return;
    }

    $post_data = fopen($content, "rb");
    $trainer_id = unpack("n", fread($post_data, $sram["trainer_id"]["size"]))[1];
    $secret_id  = unpack("n", fread($post_data, $sram["secret_id"]["size"]))[1];
    $name       = fread($post_data, $sram["name"]["size"]);
    $gender     = unpack("C", fread($post_data, $sram["gender"]["size"]))[1];
    $player_gender_decode = bxt_decode_player_gender($gender);
    $age        = unpack("C", fread($post_data, $sram["age"]["size"]))[1];
    $pregion    = unpack("C", fread($post_data, $sram["region"]["size"]))[1];

    // --- banned-player-name check with allowed-word whitelist ---
    $bannedFile = realpath(__DIR__ . "/../../../maint/banned_words.txt")
        ?: (__DIR__ . "/../../../maint/banned_words.txt");
    $banned = bxt_load_banned_words($bannedFile);

    $allowedFile = dirname($bannedFile) . "/allowed_words.txt";
    $allowed = bxt_load_allowed_words($allowedFile);

    $enc_id       = bxt_encoding_table_id($region);
    $decoded_name = bxt_decode_text_table($name, $enc_id);

    if ($decoded_name === '') {
        // ASCII salvage path for safety: turn bytes into a plain ASCII word
        $bytes = unpack('C*', $name);
        $ascii = '';
        foreach ($bytes as $b) {
            if ($b >= 0x20 && $b <= 0x7E) {
                $ascii .= chr($b);
            } else {
                $ascii .= ' ';
            }
        }
        $ascii = preg_replace('/\s+/u', ' ', $ascii);
        $ascii = trim($ascii);
        if ($ascii !== '') {
            $decoded_name = $ascii;
        }
    }

    if ($decoded_name !== '' && bxt_contains_banned($decoded_name, $banned, $allowed)) {
        error_log(
            'BXT_DEBUG_NEWS_SET_RANKING_BANNED_NAME: ' .
            'account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') .
            ' region=' . $region .
            ' trainer_id=' . $trainer_id .
            ' secret_id=' . $secret_id .
            ' decoded_name=' . $decoded_name
        );
        // Reject ranking by banned player name but return success sentinel
        return pack("N", $_SESSION["userId"] ?? 0);
    }
    // --- end banned-player-name check ---

if ($region == "j") {
        // J: zip is numeric; convert to packed bytes (will be stored as BINARY)
        $zip_num = unpack("n", fread($post_data, $sram["zip"]["size"]))[1];
        $zip     = pack("n", $zip_num);
        // Primary: decode via encoding tables
        $player_zip_decode = bxt_decode_exchange_player_zip($region, $zip);
        // Fallback: zero-padded decimal string (first few digits)
        if ($player_zip_decode === null) {
            $player_zip_decode = sprintf("%03d", $zip_num);
        }
    } else {
        // non-J: already binary bytes (we only need first few digits)
        $zip = fread($post_data, $sram["zip"]["size"]);
        // Primary: decode via encoding tables
        $player_zip_decode = bxt_decode_exchange_player_zip($region, $zip);
        // Fallback: filter ASCII digits
        if ($player_zip_decode === null) {
            $player_zip_decode = '';
            for ($i = 0; $i < strlen($zip); $i++) {
                $ch = $zip[$i];
                if ($ch >= '0' && $ch <= '9') {
                    $player_zip_decode .= $ch;
                }
            }
        }
    }

    $message = fread($post_data, $sram["message"]["size"]);

    error_log(
        'region=' . $region .
        ' news_region=' . $news_region .
        ' news_id=' . (isset($news_param['id']) ? $news_param['id'] : 'null') .
        ' trainer_id=' . $trainer_id .
        ' secret_id=' . $secret_id .
        ' gender=' . $gender .
        ' gender_decode=' . $player_gender_decode .
        ' age=' . $age .
        ' pregion=' . $pregion .
        ' name_hex=' . bin2hex($name) .
        ' zip_raw_hex=' . bin2hex($zip) .
        ' zip_decode=' . (isset($player_zip_decode) ? $player_zip_decode : '') .
        ' message_hex=' . bin2hex($message)
    );

    if (!isset($_SESSION["userId"])) {
        return;
    }

    $db = connectMySQL();
    foreach ($category_info as $category) {
        $score_raw = fread($post_data, $category["size"]);
        if ($category["size"] == 1) {
            $score = unpack("C", $score_raw)[1];
        } else if ($category["size"] == 2) {
            $score = unpack("n", $score_raw)[1];
        } else if ($category["size"] == 3) {
            $score = unpack("N", "\x00" . $score_raw)[1];
        } else {
            $score = unpack("N", $score_raw)[1];
        }

        
        // Server-side sanity validation of ranking payload
        $validation_errors = [];
        if (!bxt_validate_ranking_row(
            $region,
            $category["id"],
            $trainer_id,
            $secret_id,
            $gender,
            $age,
            $pregion,
            $zip,
            $message,
            $score,
            $validation_errors
        )) {
            error_log(
                'BXT_DEBUG_NEWS_SET_RANKING_CATEGORY_INVALID: ' .
                'account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') .
                ' region=' . $region .
                ' news_region=' . $news_region .
                ' news_id=' . $news_param["id"] .
                ' category_id=' . $category["id"] .
                ' trainer_id=' . $trainer_id .
                ' secret_id=' . $secret_id .
                ' score=' . $score .
                ' errors=' . json_encode($validation_errors)
            );
            // Reject this category entry but continue processing others
            continue;
        }

        error_log(
            'account_id=' . (isset($_SESSION['userId']) ? $_SESSION['userId'] : 'none') .
            ' region=' . $region .
            ' news_region=' . $news_region .
            ' news_id=' . $news_param["id"] .
            ' category_id=' . $category["id"] .
            ' trainer_id=' . $trainer_id .
            ' secret_id=' . $secret_id .
            ' score=' . $score
        );

// Look up any existing score for this account/trainer/secret in this category and region
// Look up any existing score for this account/trainer/secret in this category and region
        $stmt = $db->prepare(
            "select score
               from bxt_ranking
              where game_region = ?
                and news_id      = ?
                and category_id  = ?
                and account_id   = ?
                and trainer_id   = ?
                and secret_id    = ?"
        );
        // 1 string + 5 ints  => "siiiii"
        $stmt->bind_param(
            "siiiii",
            $region,
            $news_param["id"],
            $category["id"],
            $_SESSION["userId"],
            $trainer_id,
            $secret_id
        );
        $stmt->execute();
        $result = fancy_get_result($stmt);

        if (count($result) > 0 && $result[0]["score"] >= $score) {
            // Existing score is better or equal; do not replace
            continue;
        }

        // compute decoded mirrors for this category and player
        $category_id_decode     = bxt_decode_ranking_category($region, $category["id"]);
        $player_name_decode     = bxt_decode_player_name_for_region($region, $name);
        $player_region_decode   = bxt_decode_player_region($region, $pregion);
        $player_message_decode  = bxt_decode_player_message_for_ranking($region, $message);

        if (count($result) > 0) {
            // Update existing row with new better score and metadata
            $stmt = $db->prepare(
                "update bxt_ranking
                    set player_name           = ?,
                        player_gender         = ?,
                        player_gender_decode  = ?,
                        player_age            = ?,
                        player_region         = ?,
                        player_region_decode  = ?,
                        player_zip            = ?,
                        player_zip_decode     = ?,
                        player_message        = ?,
                        player_message_decode = ?,
                        score                 = ?,
                        category_id_decode    = ?
                  where game_region = ?
                    and news_id      = ?
                    and category_id  = ?
                    and account_id   = ?
                    and trainer_id   = ?
                    and secret_id    = ?"
            );
            // params:
            //  name(s), gender(i), gender_decode(s), age(i), pregion(i),
            //  pregion_decode(s), zip(s), zip_decode(s), message(s), message_decode(s), score(i),
            //  category_id_decode(s),
            //  region(s), news_id(i), category_id(i), account_id(i), trainer_id(i), secret_id(i)
            //  => 18 args, types "sisiisssssissiiiii"
            $stmt->bind_param(
                "sisiisssssissiiiii",
                $name,
                $gender,
                $player_gender_decode,
                $age,
                $pregion,
                $player_region_decode,
                $zip,
                $player_zip_decode,
                $message,
                $player_message_decode,
                $score,
                $category_id_decode,
                $region,
                $news_param["id"],
                $category["id"],
                $_SESSION["userId"],
                $trainer_id,
                $secret_id
            );
            $stmt->execute();
        } else {
            // Insert new row
            $stmt = $db->prepare(
                "insert into bxt_ranking
                 (game_region,
                  news_id, category_id, account_id, trainer_id, secret_id,
                  player_name, player_gender, player_gender_decode, player_age, player_region, player_zip,
                  player_message, score,
                  category_id_decode, player_name_decode, player_region_decode, player_zip_decode, player_message_decode)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"
            );
            // params:
            //  region(s),
            //  news_id(i), category_id(i), account_id(i), trainer_id(i), secret_id(i),
            //  name(s), gender(i), gender_decode(s), age(i), pregion(i), zip(s), message(s), score(i),
            //  category_id_decode(s), player_name_decode(s), player_region_decode(s), player_zip_decode(s), player_message_decode(s)
            //  => 19 args, types "siiiiisisiississsss"
            $stmt->bind_param(
                "siiiiisisiississsss",
                $region,
                $news_param["id"],
                $category["id"],
                $_SESSION["userId"],
                $trainer_id,
                $secret_id,
                $name,
                $gender,
                $player_gender_decode,
                $age,
                $pregion,
                $zip,
                $message,
                $score,
                $category_id_decode,
                $player_name_decode,
                $player_region_decode,
                $player_zip_decode,
                $player_message_decode
            );
            $stmt->execute();
        }
    }

    return pack("N", $_SESSION["userId"]);
}


function get_ranking($region, $post_string) {
    $region = strtolower($region);

    $is_allowed   = true;
    $allowed_news = [$region];
    $cfg          = null;

    // Global feature toggle.
    if (function_exists('bxt_get_config_array')) {
        $cfg = bxt_get_config_array();
        if (isset($cfg['news_ranking_enabled']) && !$cfg['news_ranking_enabled']) {
            $is_allowed = false;
        }
    }

    if (!$is_allowed) {
        http_response_code(403);
        exit('News ranking is not available for this region.');
    }

    // Determine pooled game_region set for this feature (news ranking).
    // Uses region_groups['news'].
    if (function_exists('bxt_regions_linked_for_feature')) {
        $pooled = bxt_regions_linked_for_feature('news', $region);
        if (is_array($pooled) && !empty($pooled)) {
            $allowed_news = $pooled;
        }
    }

    // Canonical news region for this pool; must match what set_ranking() uses.
    $news_region = strtolower($allowed_news[0]);

    // Pooled region list used for ranking queries (national + regional views).
    $gameRegions = $allowed_news;

    // Always ignore news_id in ranking queries so pooled regions share history
    $ignoreNewsIdForPool = true;

    parse_str($post_string, $post_data);
    $news_param    = get_news_parameters($news_region);
    if ($news_param === null) {
        return "";
    }
    $category_info = get_ranking_category_info($news_param);

    $out = "";
    $db  = connectMySQL();

    foreach ($category_info as $key => $category) {
        // national ranking (all records in the pooled gameRegions for this news+category)
        $total_ranked = 0;
        foreach ($gameRegions as $gr) {
            if ($ignoreNewsIdForPool) {
                $stmt = $db->prepare(
                    "select count(*)
                       from bxt_ranking
                      where game_region = ?
                        and category_id = ?"
                );
                if (!$stmt) {
                    continue;
                }
                // region(s), category_id(i) => "si"
                $stmt->bind_param("si", $gr, $category["id"]);
            } else {
                $stmt = $db->prepare(
                    "select count(*)
                       from bxt_ranking
                      where game_region = ?
                        and news_id     = ?
                        and category_id = ?"
                );
                if (!$stmt) {
                    continue;
                }
                // region(s), news_id(i), category_id(i) => "sii"
                $stmt->bind_param("sii", $gr, $news_param["id"], $category["id"]);
            }
            $stmt->execute();
            $rows = fancy_get_result($stmt);
            if ($rows && count($rows) > 0) {
                $total_ranked += (int)$rows[0]["count(*)"];
            }
        }

        $my_score = 0xFFFFFFFF;
        $my_rank  = 0xFFFFFFFF;

        if (isset($post_data["my_trainer_id"]) &&
            isset($post_data["my_secret_id"])  &&
            isset($post_data["my_account_id"])) {

            $my_trainer_id = hexdec($post_data["my_trainer_id"]);
            $my_secret_id  = hexdec($post_data["my_secret_id"]);
            $my_account_id = hexdec($post_data["my_account_id"]);

            // 1) fetch my own score/timestamp in any game_region in the pool
            $my_timestamp = null;
            foreach ($gameRegions as $gr) {
                $stmt = $db->prepare(
                    "select score, timestamp
                       from bxt_ranking
                      where game_region = ?
                        and news_id     = ?
                        and category_id = ?
                        and account_id  = ?
                        and trainer_id  = ?
                        and secret_id   = ?"
                );
                if (!$stmt) {
                    continue;
                }
                // region(s), news_id(i), category_id(i), account_id(i), trainer_id(i), secret_id(i)
                // => "siiiii"
                $stmt->bind_param(
                    "siiiii",
                    $gr,
                    $news_param["id"],
                    $category["id"],
                    $my_account_id,
                    $my_trainer_id,
                    $my_secret_id
                );
                $stmt->execute();
                $result = fancy_get_result($stmt);
                if ($result && count($result) > 0) {
                    $my_score     = $result[0]["score"];
                    $my_timestamp = $result[0]["timestamp"];
                    break;
                }
            }

            // 2) pooled rank across all gameRegions in the set
            if ($my_timestamp !== null) {
                $higher_count = 0;
                foreach ($gameRegions as $gr) {
                    $stmt = $db->prepare(
                        "select count(*)
                           from bxt_ranking
                          where game_region = ?
                            and news_id     = ?
                            and category_id = ?
                            and (score > ? or (score = ? and timestamp < ?))"
                    );
                    if (!$stmt) {
                        continue;
                    }
                    // region(s), news_id(i), category_id(i), score(i), score(i), timestamp(s)
                    // => "siiiis"
                    $stmt->bind_param(
                        "siiiis",
                        $gr,
                        $news_param["id"],
                        $category["id"],
                        $my_score,
                        $my_score,
                        $my_timestamp
                    );
                    $stmt->execute();
                    $rows = fancy_get_result($stmt);
                    if ($rows && count($rows) > 0) {
                        $higher_count += (int)$rows[0]["count(*)"];
                    }
                }
                $my_rank = $higher_count + 1;
            }
        }

                // national top 10 across pooled gameRegions
        $top_all = [];

        if ($ignoreNewsIdForPool) {
            // Pooled regions: ignore news_id and take top 10 globally for this category
            $stmt = $db->prepare(
                "select game_region, player_name,
                        player_region,
                        player_gender,
                        player_age,
                        player_zip,
                        player_message,
                        score,
                        timestamp
                   from bxt_ranking
                  where category_id = ?
                  order by score desc, timestamp
                  limit 10"
            );
            if ($stmt) {
                // category_id(i) => "i"
                $stmt->bind_param("i", $category["id"]);
                $stmt->execute();
                $rows = fancy_get_result($stmt);
                if ($rows) {
                    $top_all = $rows;
                }
            }
        } else {
            // Isolated region: keep using per-region + news_id
            foreach ($gameRegions as $gr) {
                $stmt = $db->prepare(
                    "select game_region, player_name,
                            player_region,
                            player_gender,
                            player_age,
                            player_zip,
                            player_message,
                            score,
                            timestamp
                       from bxt_ranking
                      where game_region = ?
                        and news_id     = ?
                        and category_id = ?
                      order by score desc, timestamp
                      limit 10"
                );
                if (!$stmt) {
                    continue;
                }
                // region(s), news_id(i), category_id(i) => "sii"
                $stmt->bind_param("sii", $gr, $news_param["id"], $category["id"]);
                $stmt->execute();
                $rows = fancy_get_result($stmt);
                if ($rows) {
                    $top_all = array_merge($top_all, $rows);
                }
            }
        }

if (function_exists('bxt_transform_ranking_row_for_download')) {
            foreach ($top_all as &$row) {
                $row = bxt_transform_ranking_row_for_download($region, $row);
            }
            unset($row);
        }

                $top_all = bxt_filter_ranking_rows_by_news_config($top_all, $region);

        if (!empty($top_all)) {
            usort($top_all, function ($a, $b) {
                if ($a["score"] == $b["score"]) {
                    if ($a["timestamp"] == $b["timestamp"]) {
                        return 0;
                    }
                    return ($a["timestamp"] < $b["timestamp"]) ? -1 : 1;
                }
                return ($a["score"] > $b["score"]) ? -1 : 1;
            });
            $top10 = array_slice($top_all, 0, 10);
        } else {
            $top10 = [];
        }

        // Debug: log final Top 10 rows after config-based filtering.
        if (!empty($top10)) {
            foreach ($top10 as $idxTop => $p) {
                $dbgRegion = isset($p["game_region"]) ? strtolower((string)$p["game_region"]) : '?';
                $dbgName   = isset($p["player_name_decode"])
                    ? $p["player_name_decode"]
                    : (isset($p["player_name"]) ? bin2hex($p["player_name"]) : '');
                $dbgScore  = isset($p["score"]) ? $p["score"] : -1;
                error_log(
                    ' rank=' . ($idxTop + 1) .
                    ' game_region=' . $dbgRegion .
                    ' name=' . $dbgName .
                    ' score=' . $dbgScore
                );
            }
        }

        $out .= make_ranking_table($region, $category, $top10, $total_ranked, $my_rank);

        // regional ranking: pooled across gameRegions, but filtered by player_region
        if (isset($post_data["region"])) {
            $pregion = hexdec($post_data["region"]);

            // 1) pooled count
            $total_ranked = 0;
            foreach ($gameRegions as $gr) {
                if ($ignoreNewsIdForPool) {
                    $stmt = $db->prepare(
                        "select count(*)
                           from bxt_ranking
                          where game_region  = ?
                            and category_id  = ?
                            and player_region = ?"
                    );
                    if (!$stmt) {
                        continue;
                    }
                    // region(s), category_id(i), pregion(i) => "sii"
                    $stmt->bind_param("sii", $gr, $category["id"], $pregion);
                } else {
                    $stmt = $db->prepare(
                        "select count(*)
                           from bxt_ranking
                          where game_region  = ?
                            and news_id      = ?
                            and category_id  = ?
                            and player_region = ?"
                    );
                    if (!$stmt) {
                        continue;
                    }
                    // region(s), news_id(i), category_id(i), pregion(i) => "siii"
                    $stmt->bind_param("siii", $gr, $news_param["id"], $category["id"], $pregion);
                }
                $stmt->execute();
                $rows = fancy_get_result($stmt);
                if ($rows && count($rows) > 0) {
                    $total_ranked += (int)$rows[0]["count(*)"];
                }
            }

            // 2) pooled top 10
            $top_all = [];
            foreach ($gameRegions as $gr) {
                if ($ignoreNewsIdForPool) {
                    $stmt = $db->prepare(
                        "select game_region,
                                player_name,
                                player_region,
                                player_gender,
                                player_age,
                                player_zip,
                                player_message,
                                score,
                                timestamp
                           from bxt_ranking
                          where game_region  = ?
                            and category_id  = ?
                            and player_region = ?
                          order by score desc, timestamp
                          limit 10"
                    );
                    if (!$stmt) {
                        continue;
                    }
                    // region(s), category_id(i), pregion(i) => "sii"
                    $stmt->bind_param("sii", $gr, $category["id"], $pregion);
                } else {
                    $stmt = $db->prepare(
                        "select game_region,
                                player_name,
                                player_region,
                                player_gender,
                                player_age,
                                player_zip,
                                player_message,
                                score,
                                timestamp
                           from bxt_ranking
                          where game_region  = ?
                            and news_id      = ?
                            and category_id  = ?
                            and player_region = ?
                          order by score desc, timestamp
                          limit 10"
                    );
                    if (!$stmt) {
                        continue;
                    }
                    // region(s), news_id(i), category_id(i), pregion(i) => "siii"
                    $stmt->bind_param("siii", $gr, $news_param["id"], $category["id"], $pregion);
                }
                $stmt->execute();
                $rows = fancy_get_result($stmt);
                if ($rows) {
                    foreach ($rows as &$r) {
                        $r = bxt_transform_ranking_row_for_download($region, $r);
                    }
                    unset($r);
                    $top_all = array_merge($top_all, $rows);
                }
            }

    if (function_exists('bxt_transform_ranking_row_for_download')) {
            foreach ($top_all as &$row) {
                $row = bxt_transform_ranking_row_for_download($region, $row);
            }
            unset($row);
        }

                usort($top_all, function ($a, $b) {
                if ($a["score"] == $b["score"]) {
                    if ($a["timestamp"] == $b["timestamp"]) {
                        return 0;
                    }
                    return ($a["timestamp"] < $b["timestamp"]) ? -1 : 1;
                }
                return ($a["score"] > $b["score"]) ? -1 : 1;
            });
            $top10 = array_slice($top_all, 0, 10);

            $out .= make_ranking_table($region, $category, $top10, $total_ranked, $my_rank);
        }

        // area ranking: pooled across gameRegions, filtered by player_region and player_zip
        if (isset($post_data["region"]) && isset($post_data["zip"])) {
            $pregion = hexdec($post_data["region"]);
            $zip_hex = preg_replace('/[^0-9A-Fa-f]/', '', $post_data["zip"]);
            $pzip    = "";
            if ($zip_hex !== "" && (strlen($zip_hex) % 2) === 0) {
                $pzip = hex2bin($zip_hex);
            }
            if ($pzip !== "") {
                if (strlen($pzip) > 3) {
                    $pzip = substr($pzip, 0, 3);
                }

                // 1) pooled count
                $total_ranked = 0;
                foreach ($gameRegions as $gr) {
                    if ($ignoreNewsIdForPool) {
                        $stmt = $db->prepare(
                            "select count(*)
                               from bxt_ranking
                              where game_region   = ?
                                and category_id   = ?
                                and player_region = ?
                                and player_zip    = ?"
                        );
                        if (!$stmt) {
                            continue;
                        }
                        // region(s), category_id(i), pregion(i), pzip(s) => "siis"
                        $stmt->bind_param("siis", $gr, $category["id"], $pregion, $pzip);
                    } else {
                        $stmt = $db->prepare(
                            "select count(*)
                               from bxt_ranking
                              where game_region   = ?
                                and news_id       = ?
                                and category_id   = ?
                                and player_region = ?
                                and player_zip    = ?"
                        );
                        if (!$stmt) {
                            continue;
                        }
                        // region(s), news_id(i), category_id(i), pregion(i), pzip(s) => "siiis"
                        $stmt->bind_param("siiis", $gr, $news_param["id"], $category["id"], $pregion, $pzip);
                    }
                    $stmt->execute();
                    $rows = fancy_get_result($stmt);
                    if ($rows && count($rows) > 0) {
                        $total_ranked += (int)$rows[0]["count(*)"];
                    }
                }

                // 2) pooled top 10
                $top_all = [];
                foreach ($gameRegions as $gr) {
                    if ($ignoreNewsIdForPool) {
                        $stmt = $db->prepare(
                            "select game_region,
                                    player_name,
                                    player_region,
                                    player_gender,
                                    player_age,
                                    player_zip,
                                    player_message,
                                    score,
                                    timestamp
                               from bxt_ranking
                              where game_region   = ?
                                and category_id   = ?
                                and player_region = ?
                                and player_zip    = ?
                              order by score desc, timestamp
                              limit 10"
                        );
                        if (!$stmt) {
                            continue;
                        }
                        // region(s), category_id(i), pregion(i), pzip(s) => "siis"
                        $stmt->bind_param("siis", $gr, $category["id"], $pregion, $pzip);
                    } else {
                        $stmt = $db->prepare(
                            "select game_region,
                                    player_name,
                                    player_region,
                                    player_gender,
                                    player_age,
                                    player_zip,
                                    player_message,
                                    score,
                                    timestamp
                               from bxt_ranking
                              where game_region   = ?
                                and news_id       = ?
                                and category_id   = ?
                                and player_region = ?
                                and player_zip    = ?
                              order by score desc, timestamp
                              limit 10"
                        );
                        if (!$stmt) {
                            continue;
                        }
                        // region(s), news_id(i), category_id(i), pregion(i), pzip(s) => "siiis"
                        $stmt->bind_param("siiis", $gr, $news_param["id"], $category["id"], $pregion, $pzip);
                    }
                    $stmt->execute();
                    $rows = fancy_get_result($stmt);
                    if ($rows) {
                        foreach ($rows as &$r) {
                            $r = bxt_transform_ranking_row_for_download($region, $r);
                        }
                        unset($r);
                        $top_all = array_merge($top_all, $rows);
                    }
                }

        if (function_exists('bxt_transform_ranking_row_for_download')) {
            foreach ($top_all as &$row) {
                $row = bxt_transform_ranking_row_for_download($region, $row);
            }
            unset($row);
        }

                usort($top_all, function ($a, $b) {
                    if ($a["score"] == $b["score"]) {
                        if ($a["timestamp"] == $b["timestamp"]) {
                            return 0;
                        }
                        return ($a["timestamp"] < $b["timestamp"]) ? -1 : 1;
                    }
                    return ($a["score"] > $b["score"]) ? -1 : 1;
                });
                $top10 = array_slice($top_all, 0, 10);

                $out .= make_ranking_table($region, $category, $top10, $total_ranked, $my_rank);
            }
        }

    }

    return $out;
}


function make_ranking_table($region, $category, $top10, $total_ranked, $my_rank) {
	$out = pack("N", $total_ranked);
	$out .= pack("n", 0); // currently unknown
	$out .= pack("N", $my_rank);
	$out .= pack("n", sizeof($top10));
	foreach ($top10 as $player) {
		// Player name in ranking records:
		// - Saved as BINARY(7) in the database (padded with 0x00).
		// - Protocol uses 7 bytes for non-J, 5 bytes for J plus 2 extra bytes we append below.
		$name = $player["player_name"];
		if ($region === "j") {
			$name = substr($name, 0, 5);
		} else {
			$name = substr($name, 0, 7);
		}
		$out .= $name;
		if ($region == "j") $out .= hex2bin("5000"); // japanese version has a 6th byte for the name plus a completely unused byte
		$out .= pack("C", $player["player_region"]);
		$out .= pack("n", 0); // unused 2 bytes
		$out .= pack("C", $player["player_age"]);
		$out .= pack("C", $player["player_gender"]);

		// Ranking protocol message width:
		// - Japanese (j): 12 bytes
		// - Non-Japanese: 8 bytes (DB column may be wider, we trim here)
		$message = $player["player_message"];
		if ($region !== "j") {
			$message = substr($message, 0, 8);
		}
		$out .= $message;
		if ($category["size"] == 1) {
			$score = pack("C", $player["score"]);
		} else if ($category["size"] == 2) {
			$score = pack("n", $player["score"]);
		} else if ($category["size"] == 3) {
			$score = pack("N", $player["score"]);
			$score = substr($score, 1);
			//$score = unpack("ca/ab/cc", hex2bin($post_data["category_".$key]));
			//$score = $score["a"] + ($score["b"] << 8) + ($score["c"] << 16);
		} else {
			$score = pack("N", $player["score"]);
		}
		$out .= $score;
	}
	return $out;
}
