<?php
/**
 * Map Preview Renderer for Game Boy Wars 3 maps
 *
 * Renders map data as PNG images using terrain tile sprites
 */
class MapRenderer
{
    private const TILE_SIZE_SMALL = 8;
    private const TILE_SIZE_LARGE = 16;

    private static ?GdImage $tileset8 = null;
    private static ?GdImage $tileset16 = null;

    /**
     * Get the tileset image for the specified size
     */
    private static function getTileset(int $tileSize): GdImage
    {
        $imagesDir = dirname(__DIR__) . '/htdocs/images/gbwars';

        if ($tileSize === self::TILE_SIZE_SMALL) {
            if (self::$tileset8 === null) {
                self::$tileset8 = imagecreatefrompng($imagesDir . '/terrain_8x8.png');
            }
            return self::$tileset8;
        } else {
            if (self::$tileset16 === null) {
                self::$tileset16 = imagecreatefrompng($imagesDir . '/terrain_16x16.png');
            }
            return self::$tileset16;
        }
    }

    // Tile IDs that have valid sprites in our tileset
    private static array $validTileIds = [
        0x00, // Null/out of bounds
        0x01, // RS base
        0x02, // RS city
        0x03, // RS ruined city
        0x04, // RS factory
        0x05, // RS ruined factory
        0x06, // RS airport
        0x07, // RS ruined airport
        0x08, // RS simple airport
        0x09, // RS harbor
        0x0A, // RS ruined harbor
        0x0B, // RS Transmission Tower
        0x0C, // WM base
        0x0D, // WM city
        0x0E, // WM ruined city
        0x0F, // WM factory
        0x10, // WM ruined factory
        0x11, // WM airport
        0x12, // WM ruined airport
        0x13, // WM simple airport
        0x14, // WM harbor
        0x15, // WM ruined harbor
        0x16, // WM Transmission Tower
        0x17, // Neutral city
        0x18, // Neutral ruined city
        0x19, // Neutral factory
        0x1A, // Neutral ruined factory
        0x1B, // Neutral airport
        0x1C, // Neutral ruined airport
        0x1D, // Neutral harbor
        0x1E, // Neutral ruined harbor
        0x1F, // Neutral Transmission Tower
        0x20, // Plains
        0x21, // Highway
        0x22, // Bridge
        0x23, // Bridge (variant)
        0x24, // Mountains
        0x25, // Forest
        0x26, // Wasteland
        0x27, // Desert
        0x28, // River
        0x29, // Sea
        0x2A, // Shoal
    ];

    /**
     * Get the source coordinates for a tile in the tileset
     *
     * @param int $tileId The terrain tile ID (0x00-0x2A)
     * @param int $tileSize 8 or 16
     * @return array [x, y] coordinates in the tileset
     */
    private static function getTileCoords(int $tileId, int $tileSize): array
    {
        // Map missing tile IDs to fallbacks
        if (!in_array($tileId, self::$validTileIds)) {
            // RS buildings (0x01-0x0B) -> RS base
            if ($tileId >= 0x01 && $tileId <= 0x0B) {
                $tileId = 0x01;
            }
            // WM buildings (0x0C-0x16) -> WM base
            else if ($tileId >= 0x0C && $tileId <= 0x16) {
                $tileId = 0x0C;
            }
            // Neutral buildings (0x17-0x1F) -> Neutral city
            else if ($tileId >= 0x17 && $tileId <= 0x1F) {
                $tileId = 0x17;
            }
            // Unknown terrain -> plains
            else {
                $tileId = 0x20;
            }
        }

        $tilesPerRow = 8;
        $x = ($tileId % $tilesPerRow) * $tileSize;
        $y = intdiv($tileId, $tilesPerRow) * $tileSize;
        return [$x, $y];
    }

    /**
     * Render a map preview image
     *
     * @param array $mapData Parsed map data with 'width', 'height', 'tiles' keys
     * @param int $tileSize 8 for small preview, 16 for large
     * @param bool $staggered Whether to use staggered row offset like the game (default true)
     * @return GdImage The rendered map image
     */
    public static function renderMap(array $mapData, int $tileSize = self::TILE_SIZE_SMALL, bool $staggered = true): GdImage
    {
        $width = $mapData['width'];
        $height = $mapData['height'];
        $tiles = $mapData['tiles'];

        // Stagger offset: 8px for 16x16 tiles, 4px for 8x8 tiles
        $staggerOffset = $staggered ? intdiv($tileSize, 2) : 0;

        // Image width needs extra space for the stagger offset
        $imgWidth = $width * $tileSize + $staggerOffset;
        $imgHeight = $height * $tileSize;

        $image = imagecreatetruecolor($imgWidth, $imgHeight);
        $tileset = self::getTileset($tileSize);

        // Fill background with black (for edges exposed by stagger)
        $seaColor = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $seaColor);

        // Render each tile
        for ($y = 0; $y < $height; $y++) {
            // Odd rows (1, 3, 5...) are offset by staggerOffset
            $rowOffset = ($y % 2 === 1) ? $staggerOffset : 0;

            for ($x = 0; $x < $width; $x++) {
                $tileIndex = $y * $width + $x;
                $tileId = $tiles[$tileIndex] ?? 0x20; // Default to plains

                // Clamp tile ID to valid range
                if ($tileId > 0x2A) {
                    $tileId = 0x20; // Invalid tiles become plains
                }

                [$srcX, $srcY] = self::getTileCoords($tileId, $tileSize);

                imagecopy(
                    $image,
                    $tileset,
                    $x * $tileSize + $rowOffset,
                    $y * $tileSize,
                    $srcX,
                    $srcY,
                    $tileSize,
                    $tileSize
                );
            }
        }

        return $image;
    }

    /**
     * Render a map and return as PNG data
     *
     * @param array $mapData Parsed map data
     * @param int $tileSize 8 or 16
     * @return string PNG image data
     */
    public static function renderMapPng(array $mapData, int $tileSize = self::TILE_SIZE_SMALL): string
    {
        $image = self::renderMap($mapData, $tileSize);

        ob_start();
        imagepng($image);
        $data = ob_get_clean();

        imagedestroy($image);

        return $data;
    }

    /**
     * Render a map preview from database map_data (headerless format)
     *
     * @param string $mapDataBinary Raw binary map data from database
     * @param int $tileSize 8 or 16
     * @return string PNG image data
     */
    public static function renderFromDatabase(string $mapDataBinary, int $tileSize = self::TILE_SIZE_SMALL): string
    {
        require_once __DIR__ . '/GameboyWars3Util.php';

        $parsed = GameboyWars3Util::parseMapFile($mapDataBinary, false);

        return self::renderMapPng([
            'width' => $parsed['width'],
            'height' => $parsed['height'],
            'tiles' => $parsed['tiles'],
        ], $tileSize);
    }

    /**
     * Save a map preview to a file
     *
     * @param array $mapData Parsed map data
     * @param string $filePath Output file path
     * @param int $tileSize 8 or 16
     */
    public static function saveMapPreview(array $mapData, string $filePath, int $tileSize = self::TILE_SIZE_SMALL): void
    {
        $image = self::renderMap($mapData, $tileSize);
        imagepng($image, $filePath);
        imagedestroy($image);
    }
}
