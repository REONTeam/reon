<?php

// Base BXT configuration for REON / Battle Tower / Trade Corner.

$bxt_config = [
    // Which region's tables are shown on the public web UI by default.
    'global_table_display' => ['e'],

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

// Generic accessor, in case other code expects it.
if (!function_exists('bxt_get_config_array')) {
    function bxt_get_config_array(): array {
        global $bxt_config;
        return (isset($bxt_config) && is_array($bxt_config)) ? $bxt_config : [];
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
