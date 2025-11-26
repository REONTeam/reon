<?php
/**
 * Game Boy Wars 3 - Interactive Map Detail Page
 */

require_once dirname(__DIR__, 2) . '/classes/TemplateUtil.php';
require_once dirname(__DIR__, 2) . '/classes/DBUtil.php';
require_once dirname(__DIR__, 2) . '/classes/GameboyWars3Util.php';

// Validate map ID
$mapId = $_GET['id'] ?? '';
if (!preg_match('/^\d{4}$/', $mapId)) {
    http_response_code(400);
    die('Invalid map ID');
}

// Get map from database
$db = DBUtil::getInstance()->getDB();
$stmt = $db->prepare("
    SELECT map_id, map_name, map_name_j, map_name_e,
           category_j, category_e, width, height, price_yen, download_count
    FROM bww_maps
    WHERE map_id = ? AND is_active = 1
");
$stmt->bind_param('s', $mapId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    die('Map not found');
}

$map = $result->fetch_assoc();

// Check if this is an official or REON map
$mapIdNum = (int)$mapId;
$isOfficial = GameboyWars3Util::isOfficialMap($mapIdNum);

echo TemplateUtil::render("gbwars/map", [
    'map' => $map,
    'isOfficial' => $isOfficial,
]);
