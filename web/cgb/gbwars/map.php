<?php
/**
 * Handler for Game Boy Wars 3 map downloads
 * Routes: map/?/map_NNNN.cgb
 *
 * Maps are shared across all regions - header bytes are prepended based on requesting game version
 */

require_once dirname(__DIR__, 2) . '/classes/DBUtil.php';
require_once dirname(__DIR__, 2) . '/classes/GameboyWars3Util.php';

// Get game region from routing (set by core.php) - used only for header bytes
$gameRegion = $GLOBALS['bww_game_region'] ?? 'j';

// Extract map ID from the URL parameter
// URL: /cgb/download?name=/18/CGB-BWW[JE]/map/N/map_NNNN.cgb
preg_match('#/map/\d/map_(\d{4})\.cgb$#', $_GET['name'] ?? '', $matches);
$mapId = $matches[1] ?? null;

if (!$mapId) {
    http_response_code(404);
    exit;
}

$db = DBUtil::getInstance()->getDB();

// Maps are shared - no game_region filter
$stmt = $db->prepare(
    "SELECT map_data, price_yen FROM bww_maps WHERE map_id = ? AND is_active = 1"
);
$stmt->bind_param('s', $mapId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Increment download counter
    $updateStmt = $db->prepare("UPDATE bww_maps SET download_count = download_count + 1 WHERE map_id = ?");
    $updateStmt->bind_param('s', $mapId);
    $updateStmt->execute();

    // Prepend version-specific header bytes based on requesting game
    $header = GameboyWars3Util::getMapHeader($gameRegion);

    header('Content-Type: application/octet-stream');
    echo $header . $row['map_data'];
} else {
    http_response_code(404);
}
