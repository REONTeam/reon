<?php
/**
 * Handler for Game Boy Wars 3 map menu
 * Route: 0.map_menu.txt
 *
 * Generates map pricing from database
 * Format: Multiple lines of "SSSS    EEEE    PPPP" where:
 *   SSSS = start map number (4 digits)
 *   EEEE = end map number (4 digits)
 *   PPPP = price in yen (4 digits)
 * Values separated by tabs (per Dan Docs: "whitespace may be tabs or spaces")
 */

require_once dirname(__DIR__, 2) . '/classes/DBUtil.php';

$db = DBUtil::getInstance()->getDB();

// Get all active maps ordered by map_id
$stmt = $db->prepare(
    "SELECT CAST(map_id AS UNSIGNED) as map_num, price_yen
     FROM bww_maps
     WHERE is_active = 1
     ORDER BY map_num"
);
$stmt->execute();
$result = $stmt->get_result();

// Build contiguous ranges with same price
$ranges = [];
$currentRange = null;

while ($row = $result->fetch_assoc()) {
    $mapNum = (int)$row['map_num'];
    $price = (int)$row['price_yen'];

    if ($currentRange === null) {
        // Start first range
        $currentRange = ['min' => $mapNum, 'max' => $mapNum, 'price' => $price];
    } elseif ($price === $currentRange['price'] && $mapNum === $currentRange['max'] + 1) {
        // Extend current range (same price AND contiguous)
        $currentRange['max'] = $mapNum;
    } else {
        // Different price or gap - save current range and start new one
        $ranges[] = $currentRange;
        $currentRange = ['min' => $mapNum, 'max' => $mapNum, 'price' => $price];
    }
}

// Don't forget the last range
if ($currentRange !== null) {
    $ranges[] = $currentRange;
}

header('Content-Type: text/plain');
foreach ($ranges as $range) {
    // Format: "SSSS\tEEEE\tPPPP" - tab-separated 4-digit values
    echo sprintf("%04d\t%04d\t%04d\n", $range['min'], $range['max'], $range['price']);
}
