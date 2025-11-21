<?php
/**
 * Battle eXchange Tower (BXT) global configuration.
 * Global configuration for BXT regions and helpers.
 */

$bxt_config = [
    // Global display override: at most one region code, e.g. ['e']
    'global_table_display' => ['e'],

    // Map full Game IDs to one-letter region codes.
    'game_region_map' => [
        'CGB-BXTE' => 'e', // English
        'CGB-BXTF' => 'f', // French
        'CGB-BXTD' => 'd', // German
        'CGB-BXTS' => 's', // Spanish
        'CGB-BXTI' => 'i', // Italian
        'CGB-BXTJ' => 'j', // Japanese
        'CGB-BXTP' => 'p', // Europe (multi-language)
        'CGB-BXTU' => 'u', // Australia
    ],

    // Region-linking groups per feature.
    'region_groups' => [
        'trade_corner' => [
            ['e','f','d','s','i','p','u'], // International pool
            ['j'],                         // JP-only pool
        ],
        'battle_tower' => [
            ['e','f','d','s','i','p','u'],
            ['j'],
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
    ],
];

// Convenience mirror used by older decode helpers that expect this symbol.
if (!isset($BXT_GLOBAL_TABLE_DISPLAY)) {
    $BXT_GLOBAL_TABLE_DISPLAY = $bxt_config['global_table_display'];
}

// -------- Helper functions --------

if (!function_exists('bxt_get_config_array')) {
    /**
     * Get the immutable BXT configuration array.
     *
     * @return array<string,mixed>
     */
    function bxt_get_config_array(): array {
        global $bxt_config;
        return (isset($bxt_config) && is_array($bxt_config)) ? $bxt_config : [];
    }
}

/**
 * Map a full Game ID (e.g. CGB-BXTE) to its one-letter region code (e,f,d,s,i,j,p,u).
 * Falls back to $default (or 'e') if unknown.
 */
if (!function_exists('bxt_get_region_for_gameid')) {
    function bxt_get_region_for_gameid(string $gameId, string $default = 'e'): string {
        $cfg = bxt_get_config_array();
        if (isset($cfg['game_region_map'][$gameId])) {
            return strtolower((string)$cfg['game_region_map'][$gameId]);
        }
        return strtolower($default);
    }
}

if (!function_exists('bxt_regions_linked_for_feature')) {
    function bxt_regions_linked_for_feature(string $feature, string $region): array {
        $cfg = bxt_get_config_array();
        $region = strtolower($region);

        if (!isset($cfg['region_groups'][$feature]) ||
            !is_array($cfg['region_groups'][$feature])) {
            return [$region];
        }

        foreach ($cfg['region_groups'][$feature] as $pool) {
            if (!is_array($pool)) {
                continue;
            }
            if (in_array($region, $pool, true)) {
                // Normalise to lower-case one-letter codes
                return array_values(array_map('strtolower', $pool));
            }
        }

        // Region not present in any configured pool -> isolated
        return [$region];
    }
}

?>
