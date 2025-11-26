<?php
/**
 * Game Boy Wars 3 - Map Gallery
 */

require_once dirname(__DIR__, 2) . '/classes/TemplateUtil.php';
require_once dirname(__DIR__, 2) . '/classes/DBUtil.php';
require_once dirname(__DIR__, 2) . '/classes/GameboyWars3Util.php';

$db = DBUtil::getInstance()->getDB();

// Get all active maps
$result = $db->query("SELECT map_id, map_name, width, height, price_yen FROM bww_maps WHERE is_active = 1 ORDER BY map_id");
$maps = $result->fetch_all(MYSQLI_ASSOC);

echo TemplateUtil::render("gbwars/index", [
    'maps' => $maps
]);
