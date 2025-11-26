<?php
/**
 * Handler for Game Boy Wars 3 mailbox messages
 * Routes: mbox/mbox_NN.cgb
 *
 * Message format (Shift JIS):
 * - Line 1: Control header "BD=xx" (5 chars, xx = 2 uppercase hex digits) or 7 spaces (legacy)
 * - Line 2: Title (truncated to 9 chars by game)
 * - Line 3+: Message body
 * - All lines MUST be terminated with CR/LF (\r\n)
 * - Datestamp comes from HTTP Date: header
 */

require_once dirname(__DIR__, 2) . '/classes/DBUtil.php';

// Get game region from routing (set by core.php)
$gameRegion = $GLOBALS['bww_game_region'] ?? 'j';

// Extract mailbox ID from the URL parameter
// URL: /cgb/download?name=/18/CGB-BWW[JE]/mbox/mbox_NN.cgb
preg_match('#/mbox/mbox_(\d{2})\.cgb$#', $_GET['name'] ?? '', $matches);
$mailboxId = $matches[1] ?? null;

if ($mailboxId === null) {
    http_response_code(404);
    exit;
}

$id = (int)$mailboxId;
$db = DBUtil::getInstance()->getDB();

$stmt = $db->prepare(
    "SELECT message_data FROM bww_messages WHERE game_region = ? AND mailbox_id = ? AND is_active = 1"
);
$stmt->bind_param('si', $gameRegion, $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Content-Type for binary/Shift JIS encoded text
    header('Content-Type: application/octet-stream');
    // Ensure Date header is present (game uses this for datestamp)
    header('Date: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    echo $row['message_data'];
} else {
    http_response_code(404);
}
