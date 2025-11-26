<?php
/**
 * Game Boy Wars 3 - Localized Strings API
 *
 * Returns terrain and unit names for client-side display
 *
 * Parameters:
 *   lang - Language code (default: en)
 *
 * Response:
 *   {
 *     "terrain": {"0": "Null", "1": "RS Base", ...},
 *     "units": {"0": "None", "2": "RS Infantry", ...},
 *     "factions": {"rs": "Red Star", "wm": "White Moon", ...}
 *   }
 */

header('Content-Type: application/json');

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

// Get requested language (default to English)
$lang = $_GET['lang'] ?? 'en';

// Validate and sanitize language code
if (!preg_match('/^[a-z]{2}$/', $lang)) {
    $lang = 'en';
}

// Supported locales
$supportedLocales = ['en', 'es', 'de', 'ja', 'it', 'fr'];
if (!in_array($lang, $supportedLocales)) {
    $lang = 'en';
}

// Load locale file
$localeFile = dirname(__DIR__, 3) . '/locales/' . $lang . '.yml';
if (!file_exists($localeFile)) {
    $localeFile = dirname(__DIR__, 3) . '/locales/en.yml';
    $lang = 'en';
}

try {
    $locale = Yaml::parseFile($localeFile);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to load locale file']);
    exit;
}

// Extract gbwars section
$gbwars = $locale['gbwars'] ?? [];

// Convert hex string keys to integers for JavaScript
$terrain = [];
if (isset($gbwars['terrain'])) {
    foreach ($gbwars['terrain'] as $key => $value) {
        // Key might be "0x00" or just "0"
        $intKey = is_string($key) && str_starts_with($key, '0x')
            ? hexdec($key)
            : (int)$key;
        $terrain[$intKey] = $value;
    }
}

$units = [];
if (isset($gbwars['units'])) {
    foreach ($gbwars['units'] as $key => $value) {
        $intKey = is_string($key) && str_starts_with($key, '0x')
            ? hexdec($key)
            : (int)$key;
        $units[$intKey] = $value;
    }
}

$factions = $gbwars['factions'] ?? [
    'rs' => 'Red Star',
    'wm' => 'White Moon',
    'neutral' => 'Neutral',
];

$response = [
    'lang' => $lang,
    'terrain' => $terrain,
    'units' => $units,
    'factions' => $factions,
];

// Cache for 1 day (strings don't change often)
header('Cache-Control: public, max-age=86400');

echo json_encode($response);
