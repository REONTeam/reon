<?php
// SPDX-License-Identifier: MIT
//
// BXT international config.
//
// This file centralises how different game-region codes are allowed
// to interact with one another in the REON BXT stack.
//
// game_region is the last letter of the game ID:
//   BXTE -> 'e' (English)
//   BXTF -> 'f' (French)
//   BXTD -> 'd' (German)
//   BXTS -> 's' (Spanish)
//   BXTI -> 'i' (Italian)
//   BXTP -> 'p' (Europe)
//   BXTU -> 'u' (Australia)
//   BXTJ -> 'j' (Japanese)
//
// Rules:
//   - Every upload always writes into the unified tables:
//       bxt_exchange
//       bxt_battle_tower_records
//       bxt_battle_tower_leaders
//       bxt_battle_tower_trainers
//   - The game_region column stores the source game.
//   - By default a game only interacts with its own game_region.
//   - The groups below optionally allow cross-region interaction
//     on a per-feature basis (Trade Corner vs. Battle Tower).
//
// Configuration format
// --------------------
// $BXT_REGION_GROUPS['trade_corner'] : array of region-groups.
// $BXT_REGION_GROUPS['battle_tower'] : array of region-groups.
//
// Each group is an array of one-letter region codes.  Two regions
// are allowed to link internationally for a given feature if they
// appear together in at least one group for that feature.
//
// If a region does not appear in any group for a feature, it will
// only ever interact with itself.
//
// Default:
//   - All non-Japanese games are grouped together for both
//     Trade Corner and Battle Tower.
//   - Japanese ('j') is isolated by default but can be added
//     to any group if desired.

$BXT_REGION_GROUPS = [
    'trade_corner' => [
        // Default: everything except Japanese can trade with each other
        ['e','f','d','s','i','p','u'],
    ],
    'battle_tower' => [
        // Default: everything except Japanese can pull from each other's uploads
        ['e','f','d','s','i','p','u'],
    ],
];

/**
 * Normalise a full game ID (e.g. BXTE, BXTP, BXTJ) into the
 * one-letter game_region code used in the DB.
 */
function bxt_region_from_game_id(string $gameId): string {
    $gameId = strtoupper(trim($gameId));
    if ($gameId === '') {
        return 'e';
    }
    // BXTE -> 'E'
    $suffix = substr($gameId, -1);
    $suffix = strtolower($suffix);
    // Only allow known letters; default to 'e' as a safe fall-back.
    if (!in_array($suffix, ['e','f','d','s','i','j','p','u'], true)) {
        return 'e';
    }
    return $suffix;
}

/**
 * Return all regions that the given region is allowed to link with
 * for a given feature. This always includes the region itself.
 *
 * $feature is 'trade_corner' or 'battle_tower'.
 */
function bxt_get_allowed_regions_for_feature(string $feature, string $region): array {
    global $BXT_REGION_GROUPS;

    $region = strtolower($region);
    $allowed = [$region];

    if (!isset($BXT_REGION_GROUPS[$feature])) {
        return array_values(array_unique($allowed));
    }

    foreach ($BXT_REGION_GROUPS[$feature] as $group) {
        if (!is_array($group)) {
            continue;
        }
        // Normalise group entries
        $groupNorm = array_map('strtolower', $group);
        if (in_array($region, $groupNorm, true)) {
            $allowed = array_merge($allowed, $groupNorm);
        }
    }

    return array_values(array_unique($allowed));
}

/**
 * Generic helper: can region A link with region B for a given feature?
 */
function bxt_regions_can_link(string $feature, string $regionA, string $regionB): bool {
    $regionA = strtolower($regionA);
    $regionB = strtolower($regionB);
    if ($regionA === $regionB) {
        return true;
    }
    $allowedA = bxt_get_allowed_regions_for_feature($feature, $regionA);
    return in_array($regionB, $allowedA, true);
}

/**
 * Shorthand wrappers for the two features we currently care about.
 */

function bxt_trade_regions_can_link(string $regionA, string $regionB): bool {
    return bxt_regions_can_link('trade_corner', $regionA, $regionB);
}

function bxt_bt_regions_can_link(string $regionA, string $regionB): bool {
    return bxt_regions_can_link('battle_tower', $regionA, $regionB);
}

