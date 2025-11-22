<?php
// Decode helpers for BXT tables and ranking, with optional global override.
require_once(__DIR__ . "/bxt_legality_policy.php");

function bxt_load_encoding_json() {
    static $cfg = null;
    if ($cfg !== null) return $cfg;
    $path = __DIR__ . '/bxt_encoding.json';
    if (!is_file($path)) {
        error_log('bxt_decode_helpers: missing bxt_encoding.json at ' . $path);
        $cfg = [];
        return $cfg;
    }
    $json = file_get_contents($path);
    $data = json_decode($json, true);
    if (!is_array($data)) {
        error_log('bxt_decode_helpers: failed to decode json');
        $cfg = [];
        return $cfg;
    }
    $cfg = $data;
    return $cfg;
}

function bxt_get_global_override_region() {
    $cfg_path = __DIR__ . '/../cgb/pokemon/bxt_config.php';
    if (!is_file($cfg_path)) return null;
    $bxt_config = null;
    $BXT_GLOBAL_TABLE_DISPLAY = null;
    include $cfg_path;

    if (isset($BXT_GLOBAL_TABLE_DISPLAY) && is_array($BXT_GLOBAL_TABLE_DISPLAY)) {
        foreach ($BXT_GLOBAL_TABLE_DISPLAY as $entry) {
            if (is_string($entry) && $entry !== '') {
                return strtolower($entry[0]);
            }
        }
    }

    if (isset($bxt_config['global_table_display']) && is_array($bxt_config['global_table_display'])) {
        foreach ($bxt_config['global_table_display'] as $entry) {
            if (is_string($entry) && $entry !== '') {
                return strtolower($entry[0]);
            } elseif (is_array($entry) && isset($entry[0]) && is_string($entry[0]) && $entry[0] !== '') {
                return strtolower($entry[0][0]);
            }
        }
    }
    return null;
}

function bxt_effective_region_for_display($game_region) {
    $game_region = strtolower($game_region);
    $override = bxt_get_global_override_region();
    if ($override === null || $override === '') return $game_region;
    return strtolower($override);
}

function bxt_region_to_lang($region) {
    $r = strtolower($region);
    switch ($r) {
        case 'j': return 'jp';
        case 'e': case 'p': case 'u': return 'en';
        case 'f': return 'fr';
        case 'd': return 'de';
        case 's': return 'es';
        case 'i': return 'it';
        default: return 'en';
    }
}

function bxt_decode_simple_table($table_id, $index) {
    $cfg = bxt_load_encoding_json();
    if (!isset($cfg[$table_id]) || !is_array($cfg[$table_id])) return '';
    $t = $cfg[$table_id];
    $key = (string)intval($index);
    if (isset($t[$key])) return $t[$key];
    $hex = strtoupper(dechex(intval($index)));
    if (isset($t[$hex])) return $t[$hex];
    return '';
}



function bxt_decode_item_name_for_region($game_region, $item_id) {
    if ($item_id === null || $item_id === '') {
        return '';
    }
    $region = bxt_effective_region_for_display($game_region);
    switch (strtolower((string)$region)) {
        case 'f':
            $table_id = 'btxf_item_names';
            break;
        case 'd':
            $table_id = 'btxd_item_names';
            break;
        case 's':
            $table_id = 'btxs_item_names';
            break;
        case 'i':
            $table_id = 'btxi_item_names';
            break;
        case 'j':
            $table_id = 'btxj_item_names';
            break;
        case 'e':
        case 'p':
        case 'u':
        default:
            $table_id = 'btxe_bxtp_bxtu_item_names';
            break;
    }
    $name = bxt_decode_simple_table($table_id, $item_id);
    if ($name === '') {
        return $item_id === 0 ? "" : (string)$item_id;
    }
    return $name;
}

function bxt_decode_move_name_for_region($game_region, $move_id) {
    if ($move_id === null || $move_id === '') {
        return '';
    }
    $region = bxt_effective_region_for_display($game_region);
    switch (strtolower((string)$region)) {
        case 'e':
        case 'p':
        case 'u':
            $table_id = 'btxe_bxtp_bxtu_move_names';
        case 'f':
            $table_id = 'btxf_move_names';
            break;
        case 'd':
            $table_id = 'btxd_move_names';
            break;
        case 's':
            $table_id = 'btxs_move_names';
            break;
        case 'i':
            $table_id = 'btxi_move_names';
            break;
        case 'j':
            $table_id = 'btxj_move_names';
            break;
        default:
            $table_id = 'btxe_bxtp_bxtu_move_names';
            break;
    }
    $name = bxt_decode_simple_table($table_id, $move_id);
    if ($name === '') {
        return $move_id === 0 ? "" : (string)$move_id;
    }
    return $name;
}

/**
 * Decode a player name blob into UTF-8, using JP for 'j' and EN for all others.
 * This matches the Battle Tower / Ranking spec: JP-only vs everyone-else EN,
 * independent of per-language letter tables.
 *
 * $region is the game_region column ('e','j','f','d','s','i','p','u').
 * $raw is the binary name field (including 0x50 terminator padding).
 */
function bxt_decode_player_name_for_region($region, $raw) {
    if ($raw === null) {
        return null;
    }

    // Always decode using the player's own game_region. global_table_display
    // does not affect player_name_decode.
    $eff = $region;

    // JP uses JP table, everything else collapses to EN
    $hint = ($eff === 'j') ? 'j' : 'e';

    if (!function_exists('bxt_encoding_table_id') || !function_exists('bxt_decode_text_table')) {
        // Fallback: return raw hex if helpers are unavailable
        return bin2hex($raw);
    }

    $table_id = bxt_encoding_table_id($hint);

    // Trim at first 0x50 terminator, like other trainer-name handling
    $name_bytes = $raw;
    $pos = strpos($name_bytes, "\x50");
    if ($pos !== false) {
        $name_bytes = substr($name_bytes, 0, $pos);
    }

    $txt = bxt_decode_text_table($name_bytes, $table_id);
    return $txt;
}


function bxt_decode_ranking_category($game_region, $category_id) {
    // category_id_decode honours global_table_display when set; otherwise
    // it uses the row's own game_region.
    if (function_exists('bxt_get_global_override_region')) {
        $override = bxt_get_global_override_region();
    } else {
        $override = null;
    }
    $eff = ($override !== null && $override !== '') ? $override : $game_region;
    $r = strtolower($eff);

    // Ranking category table is shared between E/P/U; others have per-region tables.
    switch ($r) {
        case 'e':
        case 'p':
        case 'u':
            $table_id = 'btxe_btxp_btxu_ranking_category';
            break;
        case 'j':
            $table_id = 'btxj_ranking_category';
            break;
        case 'f':
            $table_id = 'btxf_ranking_category';
            break;
        case 'd':
            $table_id = 'btxd_ranking_category';
            break;
        case 's':
            $table_id = 'btxs_ranking_category';
            break;
        case 'i':
            $table_id = 'btxi_ranking_category';
            break;
        default:
            $table_id = 'btxe_btxp_btxu_ranking_category';
            break;
    }

    return bxt_decode_simple_table($table_id, $category_id);
}


function bxt_decode_player_region($game_region, $player_region_id) {
    // player_region_decode always reflects the submitting game's region;
    // global_table_display does not affect this.
    $eff = $game_region;
    $r = strtolower($eff);

    switch ($r) {
        case 'e':
            $table_id = 'btxe_player_region';
            break;
        case 'p':
            $table_id = 'btxp_player_region';
            break;
        case 'u':
            $table_id = 'btxu_player_region';
            break;
        case 'j':
            $table_id = 'btxj_player_region';
            break;
        case 'f':
            $table_id = 'btxf_player_region';
            break;
        case 'd':
            $table_id = 'btxd_player_region';
            break;
        case 's':
            $table_id = 'btxs_player_region';
            break;
        case 'i':
            $table_id = 'btxi_player_region';
            break;
        default:
            $table_id = 'btxe_player_region';
            break;
    }

    return bxt_decode_simple_table($table_id, $player_region_id);
}


function bxt_decode_easy_chat_for_region($game_region, $raw_hex)
{
    $json = bxt_load_encoding_json();
    if (!$json) {
        return null;
    }

    // Normalize region to determine which Easy Chat table to use.
    // This always uses the row's own game_region; global_table_display
    // is handled at the call-site (e.g. bxt_decode_player_message_for_region).
    $eff = $game_region;
    $r = strtolower($eff);

    switch ($r) {
        case 'f':
            $table_id = 'btxf_easy_chat';
            break;
        case 'd':
            $table_id = 'btxd_easy_chat';
            break;
        case 's':
            $table_id = 'btxs_easy_chat';
            break;
        case 'i':
            $table_id = 'btxi_easy_chat';
            break;
        case 'j':
            $table_id = 'btxj_easy_chat';
            break;
        case 'e':
        case 'p':
        case 'u':
        default:
            // English-family (E/P/U) share a combined table
            $table_id = 'btxe_btxp_btxu_easy_chat';
            break;
    }

    if (!isset($json[$table_id]) || !is_array($json[$table_id])) {
        // No table => return raw payload so at least something is visible
        return $raw_hex;
    }

    $table = $json[$table_id];

    if ($raw_hex === null) {
        return '';
    }

    // If $raw_hex looks like binary (contains non-hex bytes), convert to hex.
    // Also handle MySQL-style "0x..." prefix or plain hex strings.
    $is_hex_string = preg_match('/^[0-9A-Fa-fx]+$/', $raw_hex);
    if (!$is_hex_string || strpos($raw_hex, " ") !== false) {
        // Treat as binary: convert to UPPERCASE hex (JSON keys are uppercase)
        $bytes = unpack('C*', $raw_hex);
        $raw_hex = '';
        foreach ($bytes as $b) {
            $raw_hex .= strtoupper(str_pad(dechex($b), 2, '0', STR_PAD_LEFT));
        }
    } else {
        // Strip optional "0x" prefix and normalise case (to uppercase; JSON keys are uppercase)
        if (strncasecmp($raw_hex, '0x', 2) === 0) {
            $raw_hex = substr($raw_hex, 2);
        }
        $raw_hex = strtoupper($raw_hex);
    }

    $out = [];
    $len = strlen($raw_hex);

    // Decode the entire message as 16-bit Easy Chat phrase codes.
    // Do NOT treat 0x0000 as a terminator; trailing padding is simply ignored
    // if it does not resolve to a valid entry.
    for ($i = 0; $i + 3 < $len; $i += 4) {
        $code = substr($raw_hex, $i, 4);
        if (isset($table[$code])) {
            $out[] = $table[$code];
        } elseif ($code !== '0000') {
            // Keep unknown phrase tokens visible as raw hex instead of silently
            // dropping them so the full message structure is preserved.
            $out[] = $code;
        }
    }

    if (!$out && $raw_hex !== '') {
        // Fallback: return raw hex if nothing decoded
        return $raw_hex;
    }

    // Space-separated tokens (matches in-game join behaviour closely enough
    // for summary display in tables).
    return implode(' ', $out);
}

/**
 * Decode a player's Easy Chat message (Battle Tower / Ranking phrases).
 *
 * Input is the raw binary blob from the save/record; we convert it to
 * uppercase hex and delegate to bxt_decode_easy_chat_for_region().
 */
if (!function_exists('bxt_decode_player_message_for_region')) {
    function bxt_decode_player_message_for_region($game_region, $binary_blob) {
        if ($binary_blob === null) {
            return null;
        }

        if ($binary_blob === '') {
            return '';
        }

        // Convert binary payload to uppercase hex string
        $raw_hex = strtoupper(bin2hex($binary_blob));

        $decoded = bxt_decode_easy_chat_for_region($game_region, $raw_hex);
        if ($decoded === null) {
            // Fallback: if tables are missing, return hex so something is shown
            return $raw_hex;
        }

        return $decoded;
    }
}

function bxt_decode_pokemon_gender_for_region($game_region, $gender_id) {
    $eff = bxt_effective_region_for_display($game_region);
    $r = strtolower($eff);

    switch ($r) {
        case 'e':
        case 'p':
        case 'u':
            $table_id = 'btxe_btxp_btxu_pokemon_gender';
            break;
        case 'f':
            $table_id = 'btxf_pokemon_gender';
            break;
        case 'd':
            $table_id = 'btxd_pokemon_gender';
            break;
        case 's':
            $table_id = 'btxs_pokemon_gender';
            break;
        case 'i':
            $table_id = 'btxi_pokemon_gender';
            break;		
        case 'j':
            $table_id = 'btxi_pokemon_gender';
            break;
        default:
            $table_id = 'btxe_btxp_btxu_pokemon_gender';
            break;
    }

    return bxt_decode_simple_table($table_id, $gender_id);
}

function bxt_decode_pokemon_species_for_region($game_region, $species_id) {
    $eff = bxt_effective_region_for_display($game_region);
    $r = strtolower($eff);

    switch ($r) {
        case 'e':
        case 'p':
        case 'u':
        case 's':
        case 'i':
            $table_id = 'btxe_btxp_btxu_btxs_btxi_pokemon_species';
            break;
        case 'f':
            $table_id = 'btxf_pokemon_species';
            break;
        case 'd':
            $table_id = 'btxd_pokemon_species';
            break;
        case 'j':
            $table_id = 'btxj_pokemon_species';
            break;
        default:
            $table_id = 'btxe_btxp_btxu_btxs_btxi_pokemon_species';
            break;
    }

    return bxt_decode_simple_table($table_id, $species_id);
}

/**
 * Decode a mail binary blob into displayable text using the language tables
 * (en, jp, fr_de, es_it) based on game_region and global override.
 */
function bxt_decode_mail_for_region($game_region, $raw_binary) {
    // Mail always decodes using the sender's game_region; it is not
    // affected by global_table_display.
    $eff = strtolower((string)$game_region);

    // Map per-save-region -> grouped mail table key from bxt_encoding.json
    switch ($eff) {
        case 'j':
            $lang_key = 'jp';
            break;

        case 'e':
        case 'p':
        case 'u':
            $lang_key = 'en';
            break;

        case 'f':
        case 'd':
            $lang_key = 'fr_de';
            break;

        case 's':
        case 'i':
            $lang_key = 'es_it';
            break;

        default:
            // Fallback to English tables if we see an unknown region code
            $lang_key = 'en';
            break;
    }

    $cfg = bxt_load_encoding_json();
    if (!isset($cfg[$lang_key]) || !is_array($cfg[$lang_key])) {
        return '';
    }
    $table = $cfg[$lang_key];

    if ($raw_binary === null) {
        return '';
    }

    $bytes = unpack('C*', $raw_binary);
    $out   = '';
    $i     = 0;

    foreach ($bytes as $b) {
        if ($b === 0x00) {
            // 0x00 is the mail-string terminator
            break;
        }

        $hex = strtoupper(str_pad(dechex($b), 2, '0', STR_PAD_LEFT));
        if (isset($table[$hex])) {
            $out .= $table[$hex];
        }

        // Insert a space after offset 0x20 (33 decimal) for readability,
        // for every language.
        if ($i === 0x20) {
            $out .= ' ';
        }

        $i++;
    }

    return $out;
}



/**
 * Decode trainer class for a given region using the correct trainer_class table.
 * Uses the same mapping rules as other per-region tables, plus global override.
 */
function bxt_decode_trainer_class_for_region($region, $class_id) {
    if ($class_id === null) return null;

    if (function_exists('bxt_effective_region_for_display')) {
        $eff = bxt_effective_region_for_display($region);
    } else {
        $eff = $region;
    }

    // Map region -> encoding table id for trainer classes
    // e/p/u share the btxe_btxp_btxu_trainer_class table
    $eff = strtolower($eff);
    $table_id = null;

    switch ($eff) {
        case 'e':
        case 'p':
        case 'u':
            $table_id = 'btxe_btxp_btxu_trainer_class';
            break;
        case 'f':
            $table_id = 'btxf_trainer_class';
            break;
        case 'd':
            $table_id = 'btxd_trainer_class';
            break;
        case 's':
            $table_id = 'btxs_trainer_class';
            break;
        case 'i':
            $table_id = 'btxi_trainer_class';
            break;
        case 'j':
            $table_id = 'btxj_trainer_class';
            break;
        default:
            $table_id = 'btxe_btxp_btxu_trainer_class';
            break;
    }

    return bxt_decode_simple_table($table_id, (int)$class_id);
}

/**
 * Build a compact, human-readable summary for a Gen 2 Pokemon legality result.
 *
 * The $details structure comes from legality_check_pk2_bytes_with_details()
 * and is expected to contain keys such as:
 *   ok, species, speciesId, level, nickname, trainerOT, TID, SID,
 *   version, language / languageName, shiny, isEgg, issues[]
 */
function bxt_format_legality_summary(array $details, ?string $game_region = null): string
{
    $species   = $details['species']   ?? ($details['speciesId'] ?? '???');
    $level     = $details['level']     ?? null;
    $nickname  = $details['nickname']  ?? null;
    $ot        = $details['trainerOT'] ?? null;
    $tid       = $details['TID']       ?? null;
    $sid       = $details['SID']       ?? null;
    $version   = $details['version']   ?? null;
    $langName  = $details['languageName'] ?? null;
    $langCode  = $details['language'] ?? null;
    $isShiny   = !empty($details['shiny']);
    $isEgg     = !empty($details['isEgg']);
    $ok        = $details['ok'] ?? null;

    $parts = [];

    // Name/species
    if (is_string($nickname) && $nickname !== '' && $nickname !== $species) {
        $parts[] = sprintf('%s (%s)', $nickname, $species);
    } else {
        $parts[] = (string)$species;
    }

    // Level
    if ($level !== null) {
        $parts[] = 'Lv.' . (int)$level;
    }

    // Game + language
    $sub = [];
    if ($version) {
        $sub[] = $version;
    }
    if ($langName) {
        $sub[] = $langName;
    } elseif ($langCode !== null) {
        $sub[] = 'lang ' . $langCode;
    }
    if ($sub) {
        $parts[] = implode(', ', $sub);
    }

    // OT + IDs
    $idParts = [];
    if ($ot) {
        $idParts[] = 'OT: ' . $ot;
    }
    if ($tid !== null) {
        $idParts[] = 'TID:' . (int)$tid;
    }
    if ($sid !== null) {
        $idParts[] = 'SID:' . (int)$sid;
    }
    if ($idParts) {
        $parts[] = implode(' ', $idParts);
    }
    // Added: Gender, Held Item, Friendship, EXP, Met
    if (isset($details['gender'])) {
        $parts[] = 'Gender:' . $details['gender'];
    }
    if (isset($details['heldItem'])) {
        $itemId = $details['heldItem'];
        if ($game_region !== null && function_exists('bxt_decode_item_name_for_region')) {
            $itemLabel = bxt_decode_item_name_for_region($game_region, $itemId);
            $parts[]   = 'Item:' . $itemLabel;
        } else {
            $parts[] = 'Item:' . $itemId;
        }
    }
    if (isset($details['friendship'])) {
        $parts[] = 'Friendship:' . $details['friendship'];
    }
    if (isset($details['exp'])) {
        $parts[] = 'EXP:' . $details['exp'];
    }
    if (isset($details['metLocation']) && isset($details['metLevel'])) {
        $parts[] = 'Met L:' . $details['metLocation'] . ' @Lv ' . $details['metLevel'];
    }
    if (isset($details['timeOfDay'])) {
        $parts[] = 'Time:' . $details['timeOfDay'];
    }

    // IVs
    if (isset($details['ivHP'])) {
        $parts[] = sprintf('IVs: %d/%d/%d/%d/%d/%d',
            $details['ivHP'], $details['ivATK'], $details['ivDEF'],
            $details['ivSPA'], $details['ivSPD'], $details['ivSPE']);
    }

    // Stats
    if (isset($details['statHP'])) {
        $parts[] = sprintf('Stats: %d/%d/%d/%d/%d/%d',
            $details['statHP'], $details['statATK'], $details['statDEF'],
            $details['statSPA'], $details['statSPD'], $details['statSPE']);
    }

    // Moves
    if (isset($details['move1'])) {
        $m1 = $details['move1'];
        $m2 = $details['move2'] ?? null;
        $m3 = $details['move3'] ?? null;
        $m4 = $details['move4'] ?? null;

        if ($game_region !== null && function_exists('bxt_decode_move_name_for_region')) {
            $moveIds    = [$m1, $m2, $m3, $m4];
            $moveLabels = [];
            foreach ($moveIds as $mid) {
                if ($mid === null) {
                    continue;
                }
                $moveLabels[] = bxt_decode_move_name_for_region($game_region, $mid);
            }
            if ($moveLabels) {
                $parts[] = 'Moves: ' . implode(', ', $moveLabels);
            }
        } else {
            $parts[] = sprintf(
                'Moves: %d,%d,%d,%d',
                $m1, $m2, $m3, $m4
            );
        }
    }

    // Hidden Power
    if (isset($details['hiddenPowerType'])) {
        $parts[] = 'HPower:' . $details['hiddenPowerType'];
    }


    // Flags
    $flags = [];
    if ($isShiny) {
        $flags[] = 'Shiny';
    }
    if ($isEgg) {
        $flags[] = 'Egg';
    }
    if ($flags) {
        $parts[] = implode(', ', $flags);
    }

    // Compact issue summary (at most 3 identifiers)
    if (isset($details['issues']) && is_array($details['issues'])) {
        $issueIds = [];
        foreach ($details['issues'] as $issue) {
            if (!is_array($issue)) {
                continue;
            }
            $id  = $issue['id']  ?? null;
            $sev = $issue['sev'] ?? null;
            // Only surface non-trivial severities
            if ($id && $sev !== null && $sev !== '' && strtoupper((string)$sev) !== 'OK') {
                $issueIds[] = $id;
            }
        }
        if ($issueIds) {
            $issueIds = array_values(array_unique($issueIds));
            $parts[] = 'Issues: ' . implode(', ', array_slice($issueIds, 0, 3));
        }
    }

    return implode(' | ', $parts);
}

/**
 * Summarise a raw pk2 blob into a human-readable one-line description.
 * Used for pokemon*_decode and similar mirror columns.
 */
function bxt_summarize_pk2_blob($blob) {
    if ($blob === null) {
        return null;
    }

    if (!function_exists('legality_check_pk2_bytes_with_details')) {
        return bin2hex($blob);
    }

    try {
        [$ok, $details] = legality_check_pk2_bytes_with_details(
            $blob,
            function ($msg) {
                error_log('[LegalityCheckerConsole BXT] ' . $msg);
            }
        );

        if (is_array($details) && $details) {
            // Prefer explicit summary field if the checker provided one,
            // otherwise construct our own human-readable format.
            if (isset($details['summary']) && is_string($details['summary']) && $details['summary'] !== '') {
                return $details['summary'];
            }

            return bxt_format_legality_summary($details);
        }

    } catch (Throwable $e) {
        // ignore, fallback below
        error_log('bxt_summarize_pk2_blob exception: ' . $e->getMessage());
    }

    // Last-ditch fallback: hex dump
    return bin2hex($blob);
}


/**
 * Decode player name for Exchange using the same rules as Battle Tower:
 * JP uses jp name table, everyone else uses en name table.
 */
function bxt_decode_exchange_player_name($game_region, $binary_name)
{
    // Reuse the main Battle Tower / Ranking name decoder so Exchange
    // names behave identically to other player_name_decode columns.
    return bxt_decode_player_name_for_region($game_region, $binary_name);
}

/**
 * Decode player gender using the 'player_gender' table from bxt_encoding.json.
 */
function bxt_decode_player_gender($raw_gender)
{
    if ($raw_gender === null) {
        return null;
    }

    $json = bxt_load_encoding_json();
    if (!$json || !isset($json['player_gender']) || !is_array($json['player_gender'])) {
        return null;
    }

    $table = $json['player_gender'];
    $key = (string)intval($raw_gender);
    return isset($table[$key]) ? $table[$key] : null;
}

/**
 * Decode player ZIP / postcode for Exchange.
 * Uses 'player_zip_en' for e/p/u/f/d/s/i and 'player_zip_jp' for j.
 */
function bxt_decode_exchange_player_zip($game_region, $binary_zip)
{
    if ($binary_zip === null) {
        return null;
    }

    $json = bxt_load_encoding_json();
    if (!$json) {
        return null;
    }

    $lang = bxt_region_to_lang($game_region);
    $table_id = ($lang === 'jp') ? 'jp' : 'en';

    if (!isset($json[$table_id]) || !is_array($json[$table_id])) {
        return null;
    }

    $table = $json[$table_id];

    $hex = bin2hex($binary_zip);
    $hex = preg_replace('/[^0-9A-Fa-f]/', '', $hex);
    $hex = strtoupper($hex);

    $out = [];
    for ($i = 0; $i + 1 < strlen($hex); $i += 2) {
        $code = substr($hex, $i, 2);
        if (isset($table[$code])) {
            $out[] = $table[$code];
        }
    }

    $zip = implode('', $out);
    return $zip !== '' ? $zip : null;
}