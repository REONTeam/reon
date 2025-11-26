<?php
/**
 * Extract maps from a Game Boy Wars 3 save file (.sav)
 *
 * Save file structure (131072 bytes / 0x20000):
 * - 0x00000-0x0DFFF: Game state, settings, etc.
 * - 0x0E000-0x11FFF: Downloaded map slots (4 slots × 0x1000 bytes each)
 * - 0x12000-0x1FFFF: Other data
 *
 * Each map slot is 0x1000 (4096) bytes and uses the same format as
 * downloadable .cgb map files (with 2-byte header).
 *
 * Usage: php extract_maps_from_save.php <save_file> [output_dir]
 *
 * Options:
 *   --list        List maps without extracting
 *   --all         Extract all slots (including empty ones)
 *   --slot=N      Extract only slot N (0-3)
 */

require_once dirname(__DIR__) . '/classes/GameboyWars3Util.php';

// Configuration
const SAVE_FILE_SIZE = 0x20000;  // 131072 bytes
const MAP_SLOT_START = 0xE000;   // First map slot offset
const MAP_SLOT_SIZE = 0x1000;    // 4096 bytes per slot
const MAP_SLOT_COUNT = 4;        // Number of map slots

/**
 * Parse command line arguments
 */
function parseArgs(array $argv): array {
    $options = [
        'save_file' => null,
        'output_dir' => null,
        'list_only' => false,
        'include_empty' => false,
        'slot' => null,
    ];

    $positional = [];
    foreach (array_slice($argv, 1) as $arg) {
        if ($arg === '--list') {
            $options['list_only'] = true;
        } elseif ($arg === '--all') {
            $options['include_empty'] = true;
        } elseif (str_starts_with($arg, '--slot=')) {
            $options['slot'] = (int)substr($arg, 7);
        } elseif (!str_starts_with($arg, '-')) {
            $positional[] = $arg;
        }
    }

    $options['save_file'] = $positional[0] ?? null;
    $options['output_dir'] = $positional[1] ?? '.';

    return $options;
}

/**
 * Read and validate save file
 */
function readSaveFile(string $path): string {
    if (!file_exists($path)) {
        throw new RuntimeException("Save file not found: $path");
    }

    $data = file_get_contents($path);
    if ($data === false) {
        throw new RuntimeException("Could not read save file: $path");
    }

    if (strlen($data) !== SAVE_FILE_SIZE) {
        throw new RuntimeException(sprintf(
            "Invalid save file size: expected %d bytes, got %d bytes",
            SAVE_FILE_SIZE,
            strlen($data)
        ));
    }

    // Check magic bytes
    if (substr($data, 0, 4) !== 'GBW3') {
        throw new RuntimeException("Invalid save file: missing GBW3 magic bytes");
    }

    return $data;
}

/**
 * Extract map from a slot
 *
 * @return array|null Map info or null if slot is empty
 */
function extractMapSlot(string $saveData, int $slotIndex): ?array {
    $offset = MAP_SLOT_START + ($slotIndex * MAP_SLOT_SIZE);
    $mapData = substr($saveData, $offset, MAP_SLOT_SIZE);

    // Check if slot has valid header
    $header = substr($mapData, 0, 2);
    if ($header !== "\x20\x00" && $header !== "\x21\x00") {
        return null;
    }

    // Check if slot has category signature (indicates populated map)
    $categorySig = "\xb5\xcc\xf8\xbc\xfc\xd9\xcf\xff\xf4";
    $hasMap = substr($mapData, 0x10, 9) === $categorySig;

    if (!$hasMap) {
        // Slot exists but is empty
        return [
            'slot' => $slotIndex,
            'offset' => $offset,
            'empty' => true,
            'data' => $mapData,
        ];
    }

    // Parse the map
    try {
        $parsed = GameboyWars3Util::parseMapFile($mapData, true);
        $valid = GameboyWars3Util::validateMap($mapData, true);

        // Find actual data end (0xFF terminator)
        $dataEnd = strpos($mapData, "\xFF", 0x2E);
        $actualSize = $dataEnd !== false ? $dataEnd + 1 : MAP_SLOT_SIZE;

        return [
            'slot' => $slotIndex,
            'offset' => $offset,
            'empty' => false,
            'name' => GameboyWars3Util::decodeMapName($parsed['name']),
            'name_raw' => $parsed['name'],
            'width' => $parsed['width'],
            'height' => $parsed['height'],
            'map_number' => $parsed['map_number'],
            'category' => $parsed['category'],
            'units' => count($parsed['units']),
            'valid' => $valid,
            'size' => $actualSize,
            'data' => substr($mapData, 0, $actualSize),
        ];
    } catch (Exception $e) {
        return [
            'slot' => $slotIndex,
            'offset' => $offset,
            'empty' => false,
            'error' => $e->getMessage(),
            'data' => $mapData,
        ];
    }
}

/**
 * Generate output filename for a map
 */
function generateFilename(array $mapInfo): string {
    if ($mapInfo['empty']) {
        return sprintf('slot_%d_empty.cgb', $mapInfo['slot']);
    }

    if (isset($mapInfo['error'])) {
        return sprintf('slot_%d_error.cgb', $mapInfo['slot']);
    }

    $mapNum = $mapInfo['map_number'] ?? 0;
    return sprintf('map_%04d.cgb', $mapNum);
}

/**
 * Print map info
 */
function printMapInfo(array $mapInfo): void {
    $slot = $mapInfo['slot'];
    $offset = $mapInfo['offset'];

    if ($mapInfo['empty']) {
        printf("  Slot %d (0x%05X): [EMPTY]\n", $slot, $offset);
        return;
    }

    if (isset($mapInfo['error'])) {
        printf("  Slot %d (0x%05X): [ERROR] %s\n", $slot, $offset, $mapInfo['error']);
        return;
    }

    $status = $mapInfo['valid'] ? 'VALID' : 'INVALID';
    printf(
        "  Slot %d (0x%05X): #%04d \"%s\" (%dx%d, %d units) [%s]\n",
        $slot,
        $offset,
        $mapInfo['map_number'] ?? 0,
        $mapInfo['name'],
        $mapInfo['width'],
        $mapInfo['height'],
        $mapInfo['units'],
        $status
    );
}

/**
 * Main
 */
function main(array $argv): int {
    $options = parseArgs($argv);

    if ($options['save_file'] === null) {
        echo "Usage: php " . basename($argv[0]) . " <save_file> [output_dir]\n";
        echo "\n";
        echo "Options:\n";
        echo "  --list        List maps without extracting\n";
        echo "  --all         Extract all slots (including empty ones)\n";
        echo "  --slot=N      Extract only slot N (0-3)\n";
        echo "\n";
        echo "Example:\n";
        echo "  php " . basename($argv[0]) . " game.sav ./extracted_maps/\n";
        echo "  php " . basename($argv[0]) . " game.sav --list\n";
        return 1;
    }

    try {
        $saveData = readSaveFile($options['save_file']);
    } catch (RuntimeException $e) {
        echo "Error: " . $e->getMessage() . "\n";
        return 1;
    }

    echo "=== Game Boy Wars 3 Save Map Extractor ===\n";
    echo "Save file: " . $options['save_file'] . "\n\n";

    // Extract maps from slots
    $maps = [];
    $slotsToProcess = $options['slot'] !== null
        ? [$options['slot']]
        : range(0, MAP_SLOT_COUNT - 1);

    foreach ($slotsToProcess as $slot) {
        if ($slot < 0 || $slot >= MAP_SLOT_COUNT) {
            echo "Warning: Invalid slot number $slot (valid: 0-" . (MAP_SLOT_COUNT - 1) . ")\n";
            continue;
        }
        $mapInfo = extractMapSlot($saveData, $slot);
        if ($mapInfo !== null) {
            $maps[] = $mapInfo;
        }
    }

    // List maps
    echo "Found maps:\n";
    $validMaps = 0;
    foreach ($maps as $mapInfo) {
        printMapInfo($mapInfo);
        if (!$mapInfo['empty'] && !isset($mapInfo['error'])) {
            $validMaps++;
        }
    }
    echo "\n";

    if ($options['list_only']) {
        echo "Total: $validMaps valid map(s)\n";
        return 0;
    }

    // Extract maps to files
    $outputDir = $options['output_dir'];
    if (!is_dir($outputDir)) {
        if (!mkdir($outputDir, 0755, true)) {
            echo "Error: Could not create output directory: $outputDir\n";
            return 1;
        }
    }

    echo "Extracting to: $outputDir\n";
    $extracted = 0;

    foreach ($maps as $mapInfo) {
        // Skip empty slots unless --all specified
        if ($mapInfo['empty'] && !$options['include_empty']) {
            continue;
        }

        $filename = generateFilename($mapInfo);
        $outputPath = rtrim($outputDir, '/') . '/' . $filename;

        // Write map data
        if (file_put_contents($outputPath, $mapInfo['data']) !== false) {
            echo "  Extracted: $filename";
            if (!$mapInfo['empty'] && !isset($mapInfo['error'])) {
                echo " (" . strlen($mapInfo['data']) . " bytes)";
            }
            echo "\n";
            $extracted++;
        } else {
            echo "  Error writing: $filename\n";
        }
    }

    echo "\nExtracted $extracted file(s)\n";
    return 0;
}

exit(main($argv));
