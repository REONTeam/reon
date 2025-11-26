<?php
/**
 * Game Boy Wars 3 - Map Preview Image Generator
 *
 * Renders a map preview as PNG image
 *
 * Parameters:
 *   id   - Map ID (4 digits)
 *   size - Tile size: 8 (small) or 16 (large)
 */

require_once dirname(__DIR__, 2) . '/classes/DBUtil.php';
require_once dirname(__DIR__, 2) . '/classes/MapRenderer.php';

// Validate parameters
$mapId = $_GET['id'] ?? '';
$tileSize = (int)($_GET['size'] ?? 8);

if (!preg_match('/^\d{4}$/', $mapId)) {
    http_response_code(400);
    die('Invalid map ID');
}

if ($tileSize !== 8 && $tileSize !== 16) {
    $tileSize = 8;
}

// Get map data from database
$db = DBUtil::getInstance()->getDB();
$stmt = $db->prepare("SELECT map_data FROM bww_maps WHERE map_id = ? AND is_active = 1");
$stmt->bind_param('s', $mapId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    die('Map not found');
}

$row = $result->fetch_assoc();
$mapData = $row['map_data'];

// Generate and output the preview
header('Content-Type: image/png');
header('Cache-Control: public, max-age=86400'); // Cache for 1 day

echo MapRenderer::renderFromDatabase($mapData, $tileSize);
