<?php

// Base BXT configuration for REON / Battle Tower / Trade Corner.

$bxt_config = [
    // Which region's tables are shown on the public web UI by default.
    'global_table_display' => ['e'],

    // Global feature toggles. These are intentionally plain booleans so
    // app/auto-schedule can flip them on timed windows without executing PHP.
    'trade_corner_enabled' => true,
    'battle_tower_enabled' => true,
    'news_distribution_enabled' => true,
    'news_ranking_enabled' => true,

    // Map of cartridge IDs to our internal single-letter region codes.
    'game_region_map' => [
        'CGB-BXTE' => 'e',
        'CGB-BXTF' => 'f',
        'CGB-BXTD' => 'd',
        'CGB-BXTS' => 's',
        'CGB-BXTI' => 'i',
        'CGB-BXTP' => 'p',
        'CGB-BXTU' => 'u',
        'CGB-BXTJ' => 'j',
    ],
];

/**
 * REGION POOLS CONFIG
 *
 * This function is the single source of truth for region groups.
 * Edit the arrays below to change pooling behaviour.
 */
if (!function_exists('reon_bxt_region_groups')) {
    /**
     * @return array<string, array<int, string[]>>
     */
    function reon_bxt_region_groups(): array {
        return [
            'trade_corner' => [
                ['e','f','d','s','i','p','u','j'],
            ],
            'battle_tower' => [
                ['e','f','d','s','i','p','u','j'],
            ],
            'news' => [
                ['e'],
                ['f'],
                ['d'],
                ['s'],
                ['i'],
                ['p'],
                ['u'],
                ['j'],
            ],
        ];
    }
}

// -------- Helper functions --------

if (!function_exists('bxt_runtime_feature_flags_path')) {
    function bxt_runtime_feature_flags_path(): string {
        return __DIR__ . DIRECTORY_SEPARATOR . 'bxt_runtime_state.json';
    }
}

if (!function_exists('bxt_parse_runtime_bool')) {
    function bxt_parse_runtime_bool($value): ?bool {
        if (is_bool($value)) {
            return $value;
        }
        if (is_int($value)) {
            return $value !== 0;
        }
        if (is_string($value)) {
            $normalized = strtolower(trim($value));
            if ($normalized === 'true' || $normalized === '1' || $normalized === 'yes' || $normalized === 'on') {
                return true;
            }
            if ($normalized === 'false' || $normalized === '0' || $normalized === 'no' || $normalized === 'off') {
                return false;
            }
        }
        return null;
    }
}

if (!function_exists('bxt_get_runtime_feature_flags')) {
    function bxt_get_runtime_feature_flags(): array {
        $path = bxt_runtime_feature_flags_path();
        if (!is_file($path) || !is_readable($path)) {
            return [];
        }

        $raw = @file_get_contents($path);
        if ($raw === false || $raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return [];
        }

        $flags = [];
        $source = isset($decoded['flags']) && is_array($decoded['flags']) ? $decoded['flags'] : $decoded;
        foreach (['trade_corner_enabled', 'battle_tower_enabled', 'news_distribution_enabled', 'news_ranking_enabled'] as $key) {
            if (!array_key_exists($key, $source)) {
                continue;
            }
            $parsed = bxt_parse_runtime_bool($source[$key]);
            if ($parsed !== null) {
                $flags[$key] = $parsed;
            }
        }

        return $flags;
    }
}

// Generic accessor, in case other code expects it.
if (!function_exists('bxt_get_config_array')) {
    function bxt_get_config_array(): array {
        global $bxt_config;
        $cfg = (isset($bxt_config) && is_array($bxt_config)) ? $bxt_config : [];
        $runtimeFlags = bxt_get_runtime_feature_flags();
        if (!empty($runtimeFlags)) {
            foreach ($runtimeFlags as $key => $value) {
                $cfg[$key] = $value;
            }
        }
        return $cfg;
    }
}

// Generic linkage helper, built on reon_bxt_region_groups()
if (!function_exists('bxt_regions_linked_for_feature')) {
    function bxt_regions_linked_for_feature(string $feature, string $region): array {
        $allGroups = reon_bxt_region_groups();
        $region = strtolower($region);

        if (!isset($allGroups[$feature]) || !is_array($allGroups[$feature])) {
            error_log(
                'BXT_DEBUG_BT_HELPER: feature=' . $feature .
                ' region=' . $region .
                ' no_feature_config'
            );
            return [$region];
        }

        foreach ($allGroups[$feature] as $pool) {
            if (!is_array($pool)) {
                continue;
            }
            if (in_array($region, $pool, true)) {
                $resolved = array_values(array_map('strtolower', $pool));
                error_log(
                    'BXT_DEBUG_BT_HELPER: feature=' . $feature .
                    ' region=' . $region .
                    ' pool_resolved=' . implode(',', $resolved)
                );
                return $resolved;
            }
        }

        error_log(
            'BXT_DEBUG_BT_HELPER: feature=' . $feature .
            ' region=' . $region .
            ' no_pool_match'
        );
        return [$region];
    }
}

/**
 * Battle Tower–specific helper. battle_tower.php calls this first.
 * It uses reon_bxt_region_groups() as its config source.
 */
if (!function_exists('bxt_bt_region_groups')) {
    function bxt_bt_region_groups(string $feature, string $region): array {
        // We still accept $feature for consistency/logging, but the config
        // is always read from reon_bxt_region_groups().
        $allGroups = reon_bxt_region_groups();
        $region = strtolower($region);

        if (!isset($allGroups['battle_tower']) || !is_array($allGroups['battle_tower'])) {
            error_log(
                'BXT_DEBUG_BT_HELPER_BT: feature=' . $feature .
                ' region=' . $region .
                ' no_feature_config'
            );
            return [$region];
        }

        foreach ($allGroups['battle_tower'] as $pool) {
            if (!is_array($pool)) {
                continue;
            }
            if (in_array($region, $pool, true)) {
                $resolved = array_values(array_map('strtolower', $pool));
                error_log(
                    'BXT_DEBUG_BT_HELPER_BT: feature=' . $feature .
                    ' region=' . $region .
                    ' pool_resolved=' . implode(',', $resolved)
                );
                return $resolved;
            }
        }

        error_log(
            'BXT_DEBUG_BT_HELPER_BT: feature=' . $feature .
            ' region=' . $region .
            ' no_pool_match'
        );
        return [$region];
    }
}

?>
