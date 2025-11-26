<?php
/**
 * Utility class for Game Boy Wars 3 (CGB-BWWJ/CGB-BWWE) operations
 */
class GameboyWars3Util {

    /**
     * Map header bytes for each game version
     * These are prepended to map data when serving downloads
     */
    private static array $mapHeaders = [
        'j' => "\x20\x00", // Japanese (CGB-BWWJ)
        'e' => "\x21\x00", // English translation (CGB-BWWE)
    ];

    /**
     * Map category text at offset 0x10-0x18 (in full file with header)
     * This is 0x0E-0x16 in stored data without header.
     * Displayed on the third line of the map details box in-game.
     * Keys are game region codes (j=Japanese, e=English).
     * Note: Category field is 9 bytes max. English uses uppercase only.
     */
    private static array $mapCategories = [
        'j' => 'オフィシャルマップ',
        'e' => 'OFFICIAL',  // 8 chars, fits in 9-byte limit
    ];

    /**
     * Map ID ranges:
     * - 0000-1999: Official maps (from original game)
     * - 2000-9999: REON designer maps
     */
    public const MAP_ID_OFFICIAL_MAX = 1999;
    public const MAP_ID_REON_MIN = 2000;
    public const MAP_ID_REON_MAX = 9999;

    /**
     * Game Boy Wars 3 character encoding map (byte -> UTF-8)
     * Derived from gbwars3-en decompilation char_main.inc
     */
    private static array $charMapDecode = [
        // Control characters
        0x00 => '', // [ED] - end marker
        0x01 => "\n", // [LF] - line feed
        // Symbols 0x10-0x1E
        0x10 => '•', 0x11 => '◀', 0x12 => '▶', 0x13 => '▼', 0x14 => '▲',
        0x15 => '♪', 0x16 => '〇', 0x17 => '〜', 0x18 => '♥',
        0x1b => '「', 0x1c => '|', 0x1d => '」', 0x1e => '\\',
        // ASCII 0x20-0x5F (mostly standard, with exceptions)
        0x2d => 'ー', 0x2e => '。', 0x5c => '¥',
        // Hiragana 0x60-0xAF
        0x60 => 'を', 0x61 => 'あ', 0x62 => 'い', 0x63 => 'う', 0x64 => 'え',
        0x65 => 'お', 0x66 => 'か', 0x67 => 'き', 0x68 => 'く', 0x69 => 'け',
        0x6a => 'こ', 0x6b => 'さ', 0x6c => 'し', 0x6d => 'す', 0x6e => 'せ',
        0x6f => 'そ', 0x70 => 'た', 0x71 => 'ち', 0x72 => 'つ', 0x73 => 'て',
        0x74 => 'と', 0x75 => 'な', 0x76 => 'に', 0x77 => 'ぬ', 0x78 => 'ね',
        0x79 => 'の', 0x7a => 'は', 0x7b => 'ひ', 0x7c => 'ふ', 0x7d => 'へ',
        0x7e => 'ほ', 0x7f => 'ま', 0x80 => 'み', 0x81 => 'む', 0x82 => 'め',
        0x83 => 'も', 0x84 => 'や', 0x85 => 'ゆ', 0x86 => 'よ', 0x87 => 'ら',
        0x88 => 'り', 0x89 => 'る', 0x8a => 'れ', 0x8b => 'ろ', 0x8c => 'わ',
        0x8d => 'ん', 0x8e => 'が', 0x8f => 'ぎ', 0x90 => 'ぐ', 0x91 => 'げ',
        0x92 => 'ご', 0x93 => 'ざ', 0x94 => 'じ', 0x95 => 'ず', 0x96 => 'ぜ',
        0x97 => 'ぞ', 0x98 => 'だ', 0x99 => 'ぢ', 0x9a => 'づ', 0x9b => 'で',
        0x9c => 'ど', 0x9d => 'ば', 0x9e => 'び', 0x9f => 'ぶ', 0xa0 => 'べ',
        0xa1 => 'ぼ', 0xa2 => 'ぱ', 0xa3 => 'ぴ', 0xa4 => 'ぷ', 0xa5 => 'ぺ',
        0xa6 => 'ぽ', 0xa7 => 'ぁ', 0xa8 => 'ぃ', 0xa9 => 'ぅ', 0xaa => 'ぇ',
        0xab => 'ぉ', 0xac => 'ゃ', 0xad => 'ゅ', 0xae => 'ょ', 0xaf => 'っ',
        // Katakana 0xB0-0xFF
        0xb0 => '★', 0xb1 => 'ア', 0xb2 => 'イ', 0xb3 => 'ウ', 0xb4 => 'エ',
        0xb5 => 'オ', 0xb6 => 'カ', 0xb7 => 'キ', 0xb8 => 'ク', 0xb9 => 'ケ',
        0xba => 'コ', 0xbb => 'サ', 0xbc => 'シ', 0xbd => 'ス', 0xbe => 'セ',
        0xbf => 'ソ', 0xc0 => 'タ', 0xc1 => 'チ', 0xc2 => 'ツ', 0xc3 => 'テ',
        0xc4 => 'ト', 0xc5 => 'ナ', 0xc6 => 'ニ', 0xc7 => 'ヌ', 0xc8 => 'ネ',
        0xc9 => 'ノ', 0xca => 'ハ', 0xcb => 'ヒ', 0xcc => 'フ', 0xcd => 'ヘ',
        0xce => 'ホ', 0xcf => 'マ', 0xd0 => 'ミ', 0xd1 => 'ム', 0xd2 => 'メ',
        0xd3 => 'モ', 0xd4 => 'ヤ', 0xd5 => 'ユ', 0xd6 => 'ヨ', 0xd7 => 'ラ',
        0xd8 => 'リ', 0xd9 => 'ル', 0xda => 'レ', 0xdb => 'ロ', 0xdc => 'ワ',
        0xdd => 'ン', 0xde => 'ガ', 0xdf => 'ギ', 0xe0 => 'グ', 0xe1 => 'ゲ',
        0xe2 => 'ゴ', 0xe3 => 'ザ', 0xe4 => 'ジ', 0xe5 => 'ズ', 0xe6 => 'ゼ',
        0xe7 => 'ゾ', 0xe8 => 'ダ', 0xe9 => 'ヂ', 0xea => 'ヅ', 0xeb => 'デ',
        0xec => 'ド', 0xed => 'バ', 0xee => 'ビ', 0xef => 'ブ', 0xf0 => 'ベ',
        0xf1 => 'ボ', 0xf2 => 'パ', 0xf3 => 'ピ', 0xf4 => 'プ', 0xf5 => 'ペ',
        0xf6 => 'ポ', 0xf7 => 'ァ', 0xf8 => 'ィ', 0xf9 => 'ゥ', 0xfa => 'ェ',
        0xfb => 'ォ', 0xfc => 'ャ', 0xfd => 'ュ', 0xfe => 'ョ', 0xff => 'ッ',
    ];

    /** @var array|null Reverse lookup table (UTF-8 -> byte), built on first use */
    private static ?array $charMapEncode = null;

    /**
     * Get the map header bytes for a specific game region
     */
    public static function getMapHeader(string $gameRegion): string {
        return self::$mapHeaders[$gameRegion] ?? self::$mapHeaders['j'];
    }

    /**
     * Build the reverse encoding lookup table on first use
     */
    private static function buildEncodeLookup(): void {
        if (self::$charMapEncode !== null) {
            return;
        }

        self::$charMapEncode = [];

        // Add all special mappings from decode table
        foreach (self::$charMapDecode as $byte => $char) {
            if ($char !== '') {
                self::$charMapEncode[$char] = $byte;
            }
        }

        // Add standard ASCII range (0x20-0x5F) that aren't overridden
        for ($i = 0x20; $i <= 0x5F; $i++) {
            $char = chr($i);
            if (!isset(self::$charMapEncode[$char]) && !isset(self::$charMapDecode[$i])) {
                self::$charMapEncode[$char] = $i;
            }
        }

        // Add hyphen-minus as alternative for ー (long vowel mark)
        self::$charMapEncode['-'] = 0x2d;
        // Add period as alternative for 。
        self::$charMapEncode['.'] = 0x2e;
    }

    /**
     * Decode a map name from game encoding to UTF-8
     *
     * @param string $encoded Raw bytes from map file
     * @return string UTF-8 decoded name
     */
    public static function decodeMapName(string $encoded): string {
        $result = '';
        $len = strlen($encoded);

        for ($i = 0; $i < $len; $i++) {
            $byte = ord($encoded[$i]);

            // Skip null bytes and control characters used as padding
            if ($byte === 0x00 || ($byte >= 0x02 && $byte <= 0x0F) || $byte === 0x19 || $byte === 0x1a) {
                continue;
            }

            if (isset(self::$charMapDecode[$byte])) {
                $result .= self::$charMapDecode[$byte];
            } elseif ($byte >= 0x20 && $byte <= 0x5F) {
                // Standard ASCII range (except special cases already in map)
                $result .= chr($byte);
            } else {
                // Unknown byte - skip or use replacement character
                $result .= '?';
            }
        }

        return $result;
    }

    /**
     * Encode a UTF-8 string to game encoding for map names
     *
     * Note: The game only supports uppercase ASCII letters (A-Z), not lowercase.
     * Lowercase letters will be converted to uppercase automatically.
     *
     * @param string $text UTF-8 text to encode
     * @param int $maxBytes Maximum bytes in output (default 12 for map names)
     * @return string Encoded bytes, padded with nulls to $maxBytes
     */
    public static function encodeMapName(string $text, int $maxBytes = 12): string {
        self::buildEncodeLookup();

        $result = '';
        $chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($chars as $char) {
            if (strlen($result) >= $maxBytes) {
                break;
            }

            // Try the character as-is first
            if (isset(self::$charMapEncode[$char])) {
                $result .= chr(self::$charMapEncode[$char]);
            } elseif (ctype_lower($char)) {
                // Convert lowercase ASCII to uppercase
                $upper = strtoupper($char);
                if (isset(self::$charMapEncode[$upper])) {
                    $result .= chr(self::$charMapEncode[$upper]);
                }
            } else {
                // Character not in encoding - skip
                // Could also throw an exception for strict mode
            }
        }

        // Pad to max length with nulls
        return str_pad($result, $maxBytes, "\x00");
    }

    /**
     * Get the default map category text for a game region
     *
     * @param string $region Game region code ('j' or 'e')
     * @return string Category text in the appropriate language
     */
    public static function getMapCategory(string $region = 'j'): string {
        return self::$mapCategories[$region] ?? self::$mapCategories['j'];
    }

    /**
     * Check if a map ID is an official map (from original game)
     *
     * @param int $mapId Map ID number
     * @return bool True if official map
     */
    public static function isOfficialMap(int $mapId): bool {
        return $mapId <= self::MAP_ID_OFFICIAL_MAX;
    }

    /**
     * Check if a map ID is a REON designer map
     *
     * @param int $mapId Map ID number
     * @return bool True if REON designer map
     */
    public static function isReonMap(int $mapId): bool {
        return $mapId >= self::MAP_ID_REON_MIN && $mapId <= self::MAP_ID_REON_MAX;
    }

    /**
     * Decode a map number from BCD format
     *
     * @param int $high High byte (hundreds)
     * @param int $low Low byte (units and tens)
     * @return int|null Decoded map number, or null if invalid/empty
     */
    public static function decodeMapNumber(int $high, int $low): ?int {
        if ($high === 0 && $low === 0) {
            return null; // User-made maps have no number
        }
        // BCD format: high byte is hundreds digit as hex, low byte is tens+units
        // e.g., 0x0A 0x03 = 10*100 + 03 = 1003
        return ($high * 100) + $low;
    }

    /**
     * Encode a map number to BCD format
     *
     * @param int|null $number Map number (0-9999), or null for user-made maps
     * @return array [high, low] bytes
     */
    public static function encodeMapNumber(?int $number): array {
        if ($number === null || $number <= 0) {
            return [0, 0];
        }
        $high = intdiv($number, 100); // Hundreds (as decimal value, stored as hex)
        $low = $number % 100;         // Tens and units
        return [$high, $low];
    }

    /**
     * Create map data for serving to a specific game region
     * Returns data from offset 0x02 onwards (header added dynamically on serve)
     *
     * Checksum formulas (corrected from Dan Docs):
     * - Size sum (0x02-0x03): Number of bytes from 0x20 to 0xFF terminator (inclusive)
     * - Data checksum (0x04): Sum of bytes from 0x20 to 0xFF terminator (inclusive), masked to 8 bits
     *
     * @param string $region Game region ('j' or 'e')
     * @param string $name Map name (max 12 bytes, should be in region's language)
     * @param int $width Map width (20-50)
     * @param int $height Map height (20-50)
     * @param array $tiles Array of tile bytes
     * @param array $units Array of unit data ['x' => int, 'y' => int, 'unit_id' => int]
     * @param int $price Map price in yen (default 10)
     * @param int $mapIndex Map index/variant byte (default 1)
     * @param int|null $mapNumber Map number (null for user-made maps)
     * @param string|null $category Category text (null uses default for region if map number is set)
     * @return string Binary map data without header
     */
    public static function createMapData(string $region, string $name, int $width, int $height, array $tiles, array $units = [], int $price = 10, int $mapIndex = 1, ?int $mapNumber = null, ?string $category = null): string {
        if ($width < 20 || $width > 50 || $height < 20 || $height > 50) {
            throw new InvalidArgumentException("Map dimensions must be 20-50");
        }

        if (strlen($name) > 12) {
            $name = substr($name, 0, 12);
        }
        $name = str_pad($name, 12, "\x00");

        // Build map data (starting from offset 0x02 - no header bytes)
        // Full file layout (with 2-byte header):
        //   0x00-0x01: Header (added on serve, not stored)
        //   0x02-0x03: Size sum
        //   0x04:      Data checksum
        //   0x05-0x0F: Zeros (11 bytes)
        //   0x10-0x18: Category text (9 bytes, e.g., "オフィシャルマップ" or "Official Map")
        //   0x19-0x1D: Zeros (5 bytes)
        //   0x1E-0x1F: Map number (BCD format, e.g., 0x0A 0x03 = 1003)
        //   0x20-0x2B: Map name (12 bytes)
        //   0x2C:      Width
        //   0x2D:      Height
        //   0x2E+:     Tile data, then units, then 0xFF terminator
        //
        // Stored data (without 2-byte header) starts at offset 0x02 of full file:
        //   0x00-0x01: Size sum
        //   0x02:      Data checksum
        //   0x03-0x0D: Zeros (11 bytes)
        //   0x0E-0x16: Category text (9 bytes)
        //   0x17-0x1B: Zeros (5 bytes)
        //   0x1C-0x1D: Map number (BCD format)
        //   0x1E-0x29: Map name (12 bytes)
        //   0x2A:      Width
        //   0x2B:      Height
        //   0x2C+:     Tile data

        // Encode map number to BCD
        [$mapNumHigh, $mapNumLow] = self::encodeMapNumber($mapNumber);

        // Get category text (default to region-appropriate "Official Map" if map number is set)
        if ($category === null && $mapNumber !== null) {
            $category = self::getMapCategory($region);
        }
        $categoryEncoded = $category !== null
            ? self::encodeMapName($category, 9)
            : str_repeat("\x00", 9);

        $data = "\x00\x00"; // 0x00-0x01: Placeholder for size sum
        $data .= "\x00"; // 0x02: Placeholder for checksum
        $data .= str_repeat("\x00", 0x0B); // 0x03-0x0D: Zeros (11 bytes)
        $data .= $categoryEncoded; // 0x0E-0x16: Category text (9 bytes)
        $data .= str_repeat("\x00", 0x05); // 0x17-0x1B: Zeros (5 bytes)
        $data .= chr($mapNumHigh); // 0x1C: Map number high byte (BCD hundreds)
        $data .= chr($mapNumLow); // 0x1D: Map number low byte (BCD tens+units)
        $data .= $name; // 0x1E-0x29: Map name (12 bytes)
        $data .= chr($width); // 0x2A: Width
        $data .= chr($height); // 0x2B: Height

        // Add tiles
        foreach ($tiles as $tile) {
            $data .= chr($tile);
        }

        // Add units
        foreach ($units as $unit) {
            $data .= chr($unit['x']) . chr($unit['y']) . chr($unit['unit_id']);
        }

        // Add terminator
        $data .= "\xFF";

        // Calculate checksums
        // Data from 0x1E (name start in stored) to end (0xFF terminator)
        $dataStart = 0x1E; // Where checksummed data starts in our headerless format
        $ffPos = strlen($data) - 1; // Position of 0xFF terminator

        // Size sum: number of bytes from name start to terminator (inclusive)
        $sizeSum = $ffPos - $dataStart + 1;
        $data[0] = chr($sizeSum & 0xFF);
        $data[1] = chr(($sizeSum >> 8) & 0xFF);

        // Data checksum: sum of bytes from name start to terminator (inclusive)
        $sum = 0;
        for ($i = $dataStart; $i <= $ffPos; $i++) {
            $sum += ord($data[$i]);
        }
        $data[2] = chr($sum & 0xFF);

        return $data;
    }

    /**
     * Parse a map file (with or without header)
     *
     * @param string $data Raw map binary data
     * @param bool $hasHeader Whether the data includes the 2-byte header
     * @return array Parsed map data including category and map_number
     */
    public static function parseMapFile(string $data, bool $hasHeader = true): array {
        $offset = $hasHeader ? 0 : -2; // Adjust if no header

        // Parse category text (0x10-0x18 in full file, 0x0E-0x16 in headerless)
        $categoryRaw = substr($data, 0x10 + $offset, 9);
        $category = self::decodeMapName($categoryRaw);

        // Parse map number (0x1E-0x1F in full file, 0x1C-0x1D in headerless)
        $mapNumHigh = ord($data[0x1E + $offset]);
        $mapNumLow = ord($data[0x1F + $offset]);
        $mapNumber = self::decodeMapNumber($mapNumHigh, $mapNumLow);

        $name = substr($data, 0x20 + $offset, 12);
        $name = rtrim($name, "\x00");

        $width = ord($data[0x2C + $offset]);
        $height = ord($data[0x2D + $offset]);

        $tileCount = $width * $height;
        $tiles = [];
        for ($i = 0; $i < $tileCount; $i++) {
            $tiles[] = ord($data[0x2E + $offset + $i]);
        }

        // Parse units (everything after tiles until 0xFF)
        $units = [];
        $pos = 0x2E + $offset + $tileCount;
        while ($pos < strlen($data) - 1 && ord($data[$pos]) !== 0xFF) {
            $units[] = [
                'x' => ord($data[$pos]),
                'y' => ord($data[$pos + 1]),
                'unit_id' => ord($data[$pos + 2]),
            ];
            $pos += 3;
        }

        return [
            'name' => $name,
            'width' => $width,
            'height' => $height,
            'tiles' => $tiles,
            'units' => $units,
            'category' => $category ?: null,
            'map_number' => $mapNumber,
        ];
    }

    /**
     * Strip header from full map file for database storage
     */
    public static function stripMapHeader(string $fullMapData): string {
        return substr($fullMapData, 2);
    }

    /**
     * Validate map checksums (works with headerless data)
     *
     * Checksum formulas (corrected from Dan Docs):
     * - Size sum (0x02-0x03): Number of bytes from 0x20 to 0xFF terminator (inclusive)
     * - Data checksum (0x04): Sum of bytes from 0x20 to 0xFF terminator (inclusive), masked to 8 bits
     */
    public static function validateMap(string $data, bool $hasHeader = true): bool {
        $offset = $hasHeader ? 0 : -2;
        $checksumOffset = $hasHeader ? 2 : 0;

        if (strlen($data) < (0x2E + $offset)) {
            return false;
        }

        // Find 0xFF terminator (starts searching from map data area)
        $dataStart = 0x20 + $offset;
        $ffPos = null;
        for ($i = 0x2E + $offset; $i < strlen($data); $i++) {
            if (ord($data[$i]) === 0xFF) {
                $ffPos = $i;
                break;
            }
        }

        if ($ffPos === null) {
            return false; // No terminator found
        }

        // Validate size sum: number of bytes from 0x20 to 0xFF (inclusive)
        $expectedSize = $ffPos - $dataStart + 1;
        $actualSize = ord($data[$checksumOffset]) | (ord($data[$checksumOffset + 1]) << 8);

        if ($expectedSize !== $actualSize) {
            return false;
        }

        // Validate data checksum: sum of bytes from 0x20 to 0xFF (inclusive)
        $sum = 0;
        for ($i = $dataStart; $i <= $ffPos; $i++) {
            $sum += ord($data[$i]);
        }
        $expectedChecksum = $sum & 0xFF;
        $actualChecksum = ord($data[$checksumOffset + 2]);

        return $expectedChecksum === $actualChecksum;
    }

    /**
     * Import existing binary map file to database format
     * Strips header and returns data ready for storage
     */
    public static function importMapFile(string $filePath): array {
        $fullData = file_get_contents($filePath);
        if ($fullData === false) {
            throw new RuntimeException("Could not read file: $filePath");
        }

        $parsed = self::parseMapFile($fullData, true);
        $dataWithoutHeader = self::stripMapHeader($fullData);

        return [
            'map_name' => $parsed['name'],
            'width' => $parsed['width'],
            'height' => $parsed['height'],
            'map_data' => $dataWithoutHeader,
        ];
    }

    /**
     * Create a properly formatted mbox message for storage
     *
     * Format:
     * - Line 1: "BD=xx" control header (xx = 2 uppercase hex digits)
     * - Line 2: Title (will be truncated to 9 chars by game)
     * - Line 3+: Message body
     * - All lines terminated with CR/LF
     * - Encoding: Shift JIS
     *
     * @param string $title Message title (max 9 chars displayed)
     * @param string $body Message body (UTF-8, will be converted to Shift JIS)
     * @param int $bdValue Optional BD header value (0x00-0xFF)
     * @return string Properly formatted message data in Shift JIS
     */
    public static function createMboxMessage(string $title, string $body, int $bdValue = 0): string {
        $crlf = "\r\n";

        // Build header line
        $header = sprintf("BD=%02X", $bdValue & 0xFF);

        // Convert body lines to use CRLF termination
        $bodyLines = preg_split('/\r?\n/', $body);
        $formattedBody = implode($crlf, $bodyLines);

        // Assemble message
        $message = $header . $crlf . $title . $crlf . $formattedBody . $crlf;

        // Convert to Shift JIS if needed (assuming input is UTF-8)
        if (function_exists('mb_convert_encoding')) {
            $message = mb_convert_encoding($message, 'SJIS', 'UTF-8');
        }

        return $message;
    }

    /**
     * Create mbox message using legacy 7-space format (for compatibility with existing files)
     *
     * @param string $title Message title
     * @param string $body Message body (UTF-8)
     * @return string Properly formatted message data
     */
    public static function createMboxMessageLegacy(string $title, string $body): string {
        $crlf = "\r\n";

        // 7 spaces as first line (legacy format)
        $header = "       ";

        // Convert body lines to use CRLF termination
        $bodyLines = preg_split('/\r?\n/', $body);
        $formattedBody = implode($crlf, $bodyLines);

        // Assemble message: 7 spaces + title on same line, then body
        $message = $header . $title . $crlf . $crlf . $formattedBody . $crlf;

        // Convert to Shift JIS if needed (assuming input is UTF-8)
        if (function_exists('mb_convert_encoding')) {
            $message = mb_convert_encoding($message, 'SJIS', 'UTF-8');
        }

        return $message;
    }

    /**
     * Validate mbox message format
     * Checks for proper CRLF termination and BD=xx or 7-space header
     */
    public static function validateMboxMessage(string $data): array {
        $errors = [];

        // Check for CRLF line endings
        if (strpos($data, "\r\n") === false) {
            $errors[] = 'Message must use CRLF (\\r\\n) line termination';
        }

        // Check for proper line termination (not just delimiting)
        if (!str_ends_with($data, "\r\n")) {
            $errors[] = 'Message must end with CRLF';
        }

        // Check for BD=xx header or 7-space legacy format on first line
        $firstChars = substr($data, 0, 7);
        $hasBdHeader = preg_match('/^BD=[0-9A-F]{2}/', $data);
        $hasLegacyHeader = $firstChars === "       "; // 7 spaces

        if (!$hasBdHeader && !$hasLegacyHeader) {
            $errors[] = 'First line must be BD=XX (2 uppercase hex digits) or 7 spaces';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'format' => $hasBdHeader ? 'bd' : ($hasLegacyHeader ? 'legacy' : 'unknown'),
        ];
    }

    /**
     * Generate mbox_serial.txt content from database
     *
     * @param array $serials Array of [mailbox_id => serial_number] for active messages
     * @return string Serial file content (16 lines, LF terminated)
     */
    public static function generateSerialFile(array $serials): string {
        $lines = [];
        for ($i = 0; $i < 16; $i++) {
            $lines[] = $serials[$i] ?? '0000';
        }
        return implode("\n", $lines);
    }
}
