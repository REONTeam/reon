<?php
/**
 * Game Boy Wars 3 - Map Data API
 *
 * Returns map data as JSON for client-side rendering
 *
 * Parameters:
 *   id - Map ID (4 digits)
 *
 * Response:
 *   {
 *     "id": "1001",
 *     "name": "Map Name",
 *     "width": 32,
 *     "height": 32,
 *     "category": "Official Map",
 *     "mapNumber": 1001,
 *     "resources": {
 *       "playerGold": 10000,
 *       "enemyGold": 10000,
 *       "playerMaterials": 100,
 *       "enemyMaterials": 100
 *     },
 *     "tiles": [32, 32, 33, ...],
 *     "units": [{"x": 5, "y": 3, "type": 2}, ...]
 *   }
 */

header('Content-Type: application/json');

require_once dirname(__DIR__, 3) . '/classes/DBUtil.php';
require_once dirname(__DIR__, 3) . '/classes/GameboyWars3Util.php';

// Validate map ID
$mapId = $_GET['id'] ?? '';
if (!preg_match('/^\d{4}$/', $mapId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid map ID']);
    exit;
}

// Get map from database
$db = DBUtil::getInstance()->getDB();
$stmt = $db->prepare("
    SELECT map_id, map_name, map_name_j, map_name_e,
           category_j, category_e, width, height, map_data
    FROM bww_maps
    WHERE map_id = ? AND is_active = 1
");
$stmt->bind_param('s', $mapId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Map not found']);
    exit;
}

$row = $result->fetch_assoc();

// Parse the map data (stored without header)
$parsed = GameboyWars3Util::parseMapFile($row['map_data'], false);

// Build response
$response = [
    'id' => $row['map_id'],
    'name' => $row['map_name'],
    'nameJ' => $row['map_name_j'],
    'nameE' => $row['map_name_e'],
    'categoryJ' => $row['category_j'],
    'categoryE' => $row['category_e'],
    'width' => (int)$row['width'],
    'height' => (int)$row['height'],
    'mapNumber' => $parsed['map_number'],
    'resources' => [
        'playerGold' => $parsed['player_gold'],
        'enemyGold' => $parsed['enemy_gold'],
        'playerMaterials' => $parsed['player_materials'],
        'enemyMaterials' => $parsed['enemy_materials'],
    ],
    'tiles' => $parsed['tiles'],
    'units' => array_map(function($unit) {
        return [
            'x' => $unit['x'],
            'y' => $unit['y'],
            'type' => $unit['unit_id'],
        ];
    }, $parsed['units']),
];

// Cache for 1 hour
header('Cache-Control: public, max-age=3600');

echo json_encode($response);
