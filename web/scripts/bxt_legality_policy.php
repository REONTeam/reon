<?php
/**
 * bxt_legality_policy.php
 * Helpers for banned words, encoding, and shared legality policies.
 */

function bxt_load_banned_words(string $path): array {
    static $cache = null;
    if ($cache !== null) return $cache;
    $cache = [];
    if (!is_file($path)) return $cache;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $ln) {
        $ln = trim($ln);
        if ($ln === '' || $ln[0] === '#') continue;
        $cache[] = mb_strtolower($ln, 'UTF-8');
    }
    return $cache;
}


function bxt_load_allowed_words(string $path): array {
    static $cache = null;
    if ($cache !== null) return $cache;
    $cache = [];
    if (!is_file($path)) return $cache;
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $ln) {
        $ln = trim($ln);
        if ($ln === '' || $ln[0] === '#') continue;
        $cache[] = mb_strtolower($ln, 'UTF-8');
    }
    return $cache;
}


function bxt_contains_banned(string $text, array $banned, array $allowed = []): bool {
    $t = mb_strtolower($text, 'UTF-8');

    // Pre-compute allowed word spans (start/end offsets in characters)
    $allowedSpans = [];
    if (!empty($allowed)) {
        foreach ($allowed as $aw) {
            if ($aw === '') continue;
            $awLower = mb_strtolower($aw, 'UTF-8');
            $pos = 0;
            $lenAw = mb_strlen($awLower, 'UTF-8');
            if ($lenAw === 0) continue;
            while (($idx = mb_stripos($t, $awLower, $pos, 'UTF-8')) !== false) {
                $allowedSpans[] = [$idx, $idx + $lenAw];
                $pos = $idx + 1;
            }
        }
    }

    // Scan for banned substrings, ignoring those fully inside any allowed span
    foreach ($banned as $w) {
        if ($w === '') continue;
        $wLower = mb_strtolower($w, 'UTF-8');
        $lenBw  = mb_strlen($wLower, 'UTF-8');
        if ($lenBw === 0) continue;

        $pos = 0;
        while (($idx = mb_stripos($t, $wLower, $pos, 'UTF-8')) !== false) {
            $start = $idx;
            $end   = $idx + $lenBw;

            $whitelisted = false;
            foreach ($allowedSpans as $span) {
                if ($start >= $span[0] && $end <= $span[1]) {
                    $whitelisted = true;
                    break;
                }
            }

            if (!$whitelisted) {
                return true;
            }

            $pos = $idx + 1;
        }
    }

    return false;
}

function bxt_encoding_table_id(string $hint): string {
    $json_path = __DIR__ . '/bxt_encoding.json';
    static $cfg = null;
    if ($cfg === null) {
        $raw = @file_get_contents($json_path);
        $cfg = $raw ? json_decode($raw, true) : null;
        if (!is_array($cfg)) $cfg = [];
    }
    $aliases = $cfg['aliases'] ?? [];
    $hint_up = strtoupper($hint);
    if (isset($aliases[$hint_up])) return (string)$aliases[$hint_up];
    if (isset($cfg[$hint])) return $hint;
    if ($hint === 'j') return 'jp';
    if ($hint === 'e') return 'en';
    return 'en';
}

function bxt_decode_text_table(string $raw, string $table_id): string {
    $json_path = __DIR__ . '/bxt_encoding.json';
    static $cfg = null;
    if ($cfg === null) {
        $raw_json = @file_get_contents($json_path);
        $cfg = $raw_json ? json_decode($raw_json, true) : null;
        if (!is_array($cfg)) $cfg = [];
    }
    $table = $cfg[$table_id] ?? [];
    $bytes = unpack('C*', $raw);
    $out = '';
    foreach ($bytes as $b) {
        $hex = strtoupper(str_pad(dechex($b), 2, '0', STR_PAD_LEFT));
        if (isset($table[$hex]) && $table[$hex] !== null) {
            $out .= $table[$hex];
        } elseif ($b >= 0x20 && $b <= 0x7E) {
            $out .= chr($b);
        } else {
            $out .= ' ';
        }
    }
    $out = preg_replace('/\p{C}+/u', '', $out);
    $out = preg_replace('/\s+/u', ' ', $out);
    return trim($out);
}

/** 0..9 -> 10..100 */
function battle_tower_cap(int $idx): int {
    if ($idx < 0) $idx = 0;
    if ($idx > 9) $idx = 9;
    return ($idx + 1) * 10;
}

function bxt_policy_allow_nickname(array $details, array $banned, array $allowed = []): bool {
    $nick = $details['nickname'] ?? '';
    if ($nick === '') return true;
    return !bxt_contains_banned($nick, $banned, $allowed);
}

function bxt_policy_allow_ot(array $details, array $banned, array $allowed = []): bool {
    $ot = $details['trainerOT'] ?? '';
    if ($ot === '') return true;
    return !bxt_contains_banned($ot, $banned, $allowed);
}

function bxt_policy_allow_mail_table(string $mail_raw, string $table_id, array $banned, array $allowed = []): bool {
    if ($mail_raw === '') return true;

    // Decode using the configured table
    $txt = bxt_decode_text_table($mail_raw, $table_id);

    // If decoding produced nothing meaningful, allow it
    if ($txt === '') return true;

    return !bxt_contains_banned($txt, $banned, $allowed);
}


