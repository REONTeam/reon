<?php
/**
 * Routing handler for Game Boy Wars 3 requests
 * Supports both Japanese (CGB-BWWJ) and English (CGB-BWWE) versions
 *
 * Called from core.php when file doesn't exist on disk
 *
 * @param string $gameRegion 'j' or 'e'
 * @param string $path Path after /18/CGB-BWW[JE]/
 * @return bool True if route was handled, false otherwise
 */
function handleGbwarsRoute(string $gameRegion, string $path): bool
{
    $gbwarsDir = __DIR__;
    $GLOBALS['bww_game_region'] = $gameRegion;

    // Map menu: 0.map_menu.txt
    if ($path === '0.map_menu.txt') {
        include($gbwarsDir . "/map_menu.php");
        return true;
    }

    // Mercenary menu: 0.youhei_menu.txt
    if ($path === '0.youhei_menu.txt') {
        include($gbwarsDir . "/youhei_menu.php");
        return true;
    }

    // Map downloads: map/*/map_NNNN.cgb
    if (preg_match('#^map/\d/map_\d{4}\.cgb$#', $path)) {
        include($gbwarsDir . "/map.php");
        return true;
    }

    // Mailbox serial list: mbox/mbox_serial.txt
    if ($path === 'mbox/mbox_serial.txt') {
        include($gbwarsDir . "/mbox_serial.php");
        return true;
    }

    // Mailbox messages: mbox/mbox_NN.cgb
    if (preg_match('#^mbox/mbox_\d{2}\.cgb$#', $path)) {
        include($gbwarsDir . "/mbox.php");
        return true;
    }

    // Charge confirmations: charge/NNNN.charge.cgb
    if (preg_match('#^charge/\d+\.charge\.cgb$#', $path)) {
        return true;
    }

    return false;
}
