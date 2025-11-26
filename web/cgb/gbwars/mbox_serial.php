<?php
/**
 * Handler for Game Boy Wars 3 mailbox serial numbers
 * Route: mbox/mbox_serial.txt
 *
 * Generates serial numbers for each mailbox from database
 * Format: 16 lines of 4-digit numbers (one per mailbox)
 *
 * The game compares these with locally stored values and only
 * downloads mailboxes with changed serial numbers.
 */

require_once dirname(__DIR__, 2) . '/classes/DBUtil.php';
require_once dirname(__DIR__, 2) . '/classes/GameboyWars3Util.php';

// Get game region from routing (set by core.php)
$gameRegion = $GLOBALS['bww_game_region'] ?? 'j';

$db = DBUtil::getInstance()->getDB();

$stmt = $db->prepare(
    "SELECT mailbox_id, serial_number FROM bww_messages
     WHERE game_region = ? AND is_active = 1
     ORDER BY mailbox_id"
);
$stmt->bind_param('s', $gameRegion);
$stmt->execute();
$result = $stmt->get_result();

$serials = [];
while ($row = $result->fetch_assoc()) {
    $serials[$row['mailbox_id']] = $row['serial_number'];
}

header('Content-Type: text/plain');
echo GameboyWars3Util::generateSerialFile($serials);
