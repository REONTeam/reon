<?php
/**
 * Handler for Game Boy Wars 3 mercenary menu
 * Route: 0.youhei_menu.txt
 *
 * Generates mercenary unit pricing from database
 * Format: 5 lines, one price per mercenary unit (4-digit ASCII numbers)
 *
 * Units:
 * 0 = Infantry
 * 1 = AA Tank
 * 2 = Tank
 * 3 = Bomber
 * 4 = Frigate
 */

// Default prices: Infantry=30, others=50
$prices = [30, 50, 50, 50, 50];

header('Content-Type: text/plain');
foreach ($prices as $price) {
    echo sprintf("%04d\n", $price);
}
