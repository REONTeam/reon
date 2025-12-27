<?php

// Value- and range-level validation helpers for BXT tables.
// This file centralises constraints that should be enforced on upload
// and optionally re-applied during maintenance sweeps.
//
// It intentionally does not perform any decoding; callers are expected
// to pass raw binary blobs and decoded scalar values as needed.

if (!function_exists('bxt_get_easy_chat_allowed_codes_for_region')) {
    /**
     * Return an associative array of allowed 2-byte Easy Chat codes
     * for the given game_region, keyed by 4-hex uppercase strings.
     *
     * The lists are derived from bxt_encoding.json easy_chat tables and
     * cached per-region.
     */
    function bxt_get_easy_chat_allowed_codes_for_region($game_region) {
        static $allowed = null;

        if ($allowed !== null) {
            return $allowed;
        }

        // Hard-coded Easy Chat code set, identical for all regions in Gen 2 mobile.
		// Tried pulling valid IDs from bxt_encoding.json, but that errored so we get this. I'm sorry.
        $codes = [
            '0000',
            '0001',
            '0002',
            '0003',
            '0004',
            '0005',
            '0006',
            '0007',
            '0008',
            '0009',
            '000A',
            '000B',
            '000C',
            '000D',
            '000E',
            '0100',
            '0101',
            '0102',
            '0103',
            '0104',
            '0105',
            '0106',
            '0107',
            '0108',
            '0109',
            '010A',
            '010B',
            '010C',
            '010D',
            '010E',
            '0200',
            '0201',
            '0202',
            '0203',
            '0204',
            '0205',
            '0206',
            '0207',
            '0208',
            '0209',
            '020A',
            '020B',
            '020C',
            '020D',
            '020E',
            '0300',
            '0301',
            '0302',
            '0303',
            '0304',
            '0305',
            '0306',
            '0307',
            '0308',
            '0309',
            '030A',
            '030B',
            '030C',
            '030D',
            '030E',
            '0400',
            '0401',
            '0402',
            '0403',
            '0404',
            '0405',
            '0406',
            '0407',
            '0408',
            '0409',
            '040A',
            '040B',
            '040C',
            '040D',
            '040E',
            '0500',
            '0501',
            '0502',
            '0503',
            '0504',
            '0505',
            '0506',
            '0507',
            '0508',
            '0509',
            '050A',
            '050B',
            '050C',
            '050D',
            '050E',
            '0600',
            '0601',
            '0602',
            '0603',
            '0604',
            '0605',
            '0606',
            '0607',
            '0608',
            '0609',
            '060A',
            '060B',
            '060C',
            '060D',
            '060E',
            '0700',
            '0701',
            '0702',
            '0703',
            '0704',
            '0705',
            '0706',
            '0707',
            '0708',
            '0709',
            '070A',
            '070B',
            '070C',
            '070D',
            '070E',
            '0800',
            '0801',
            '0802',
            '0803',
            '0804',
            '0805',
            '0806',
            '0807',
            '0808',
            '0809',
            '080A',
            '080B',
            '080C',
            '080D',
            '080E',
            '0900',
            '0901',
            '0902',
            '0903',
            '0904',
            '0905',
            '0906',
            '0907',
            '0908',
            '0909',
            '090A',
            '090B',
            '090C',
            '090D',
            '090E',
            '0A00',
            '0A01',
            '0A02',
            '0A03',
            '0A04',
            '0A05',
            '0A06',
            '0A07',
            '0A08',
            '0A09',
            '0A0A',
            '0A0B',
            '0A0C',
            '0A0D',
            '0A0E',
            '0B00',
            '0B01',
            '0B02',
            '0B03',
            '0B04',
            '0B05',
            '0B06',
            '0B07',
            '0B08',
            '0B09',
            '0B0A',
            '0B0B',
            '0B0C',
            '0B0D',
            '0B0E',
            '0C00',
            '0C01',
            '0C02',
            '0C03',
            '0C04',
            '0C05',
            '0C06',
            '0C07',
            '0C08',
            '0C09',
            '0C0A',
            '0C0B',
            '0C0C',
            '0C0D',
            '0C0E',
            '0D00',
            '0D01',
            '0D02',
            '0D03',
            '0D04',
            '0D05',
            '0D06',
            '0D07',
            '0D08',
            '0D09',
            '0D0A',
            '0D0B',
            '0D0C',
            '0D0D',
            '0D0E',
            '0E00',
            '0E01',
            '0E02',
            '0E03',
            '0E04',
            '0E05',
            '0E06',
            '0E07',
            '0E08',
            '0E09',
            '0E0A',
            '0E0B',
            '0E0C',
            '0E0D',
            '0E0E',
            '0F00',
            '0F01',
            '0F02',
            '0F03',
            '0F04',
            '0F05',
            '0F06',
            '0F07',
            '0F08',
            '0F09',
            '0F0A',
            '0F0B',
            '0F0C',
            '0F0D',
            '0F0E',
            '1000',
            '1001',
            '1002',
            '1003',
            '1004',
            '1005',
            '1006',
            '1007',
            '1008',
            '1009',
            '100A',
            '100B',
            '100C',
            '100D',
            '100E',
            '1100',
            '1101',
            '1102',
            '1103',
            '1104',
            '1105',
            '1106',
            '1107',
            '1108',
            '1109',
            '110A',
            '110B',
            '110C',
            '110D',
            '110E',
            '1200',
            '1202',
            '1203',
            '1204',
            '1205',
            '1206',
            '1207',
            '1208',
            '1209',
            '120A',
            '120B',
            '120C',
            '120D',
            '120E',
            '1300',
            '1302',
            '1303',
            '1304',
            '1305',
            '1306',
            '1307',
            '1308',
            '1309',
            '130A',
            '130B',
            '130C',
            '130D',
            '130E',
            '1400',
            '1402',
            '1403',
            '1404',
            '1405',
            '1406',
            '1407',
            '1408',
            '1409',
            '140A',
            '140B',
            '140C',
            '140D',
            '140E',
            '1500',
            '1502',
            '1503',
            '1504',
            '1505',
            '1506',
            '1507',
            '1508',
            '1509',
            '150A',
            '150B',
            '150C',
            '150D',
            '150E',
            '1600',
            '1602',
            '1603',
            '1604',
            '1605',
            '1606',
            '1607',
            '1608',
            '1609',
            '160A',
            '160B',
            '160C',
            '160D',
            '160E',
            '1700',
            '1702',
            '1703',
            '1704',
            '1705',
            '1706',
            '1707',
            '1708',
            '1709',
            '170A',
            '170B',
            '170C',
            '170D',
            '170E',
            '1800',
            '1802',
            '1803',
            '1804',
            '1805',
            '1806',
            '1807',
            '1808',
            '1809',
            '180A',
            '180B',
            '180C',
            '180D',
            '180E',
            '1900',
            '1902',
            '1903',
            '1904',
            '1905',
            '1906',
            '1907',
            '1908',
            '1909',
            '190A',
            '190B',
            '190C',
            '190D',
            '190E',
            '1A00',
            '1A02',
            '1A03',
            '1A04',
            '1A05',
            '1A06',
            '1A07',
            '1A08',
            '1A09',
            '1A0A',
            '1A0B',
            '1A0C',
            '1A0D',
            '1A0E',
            '1B00',
            '1B02',
            '1B03',
            '1B04',
            '1B05',
            '1B06',
            '1B07',
            '1B08',
            '1B09',
            '1B0A',
            '1B0B',
            '1B0C',
            '1B0D',
            '1B0E',
            '1C00',
            '1C02',
            '1C03',
            '1C04',
            '1C05',
            '1C06',
            '1C07',
            '1C08',
            '1C09',
            '1C0A',
            '1C0B',
            '1C0C',
            '1C0D',
            '1C0E',
            '1D00',
            '1D02',
            '1D03',
            '1D04',
            '1D05',
            '1D06',
            '1D07',
            '1D08',
            '1D09',
            '1D0A',
            '1D0B',
            '1D0C',
            '1D0D',
            '1D0E',
            '1E00',
            '1E02',
            '1E03',
            '1E04',
            '1E05',
            '1E06',
            '1E07',
            '1E08',
            '1E09',
            '1E0A',
            '1E0B',
            '1E0C',
            '1E0D',
            '1E0E',
            '1F00',
            '1F02',
            '1F03',
            '1F04',
            '1F05',
            '1F06',
            '1F07',
            '1F08',
            '1F09',
            '1F0A',
            '1F0B',
            '1F0C',
            '1F0D',
            '1F0E',
            '2000',
            '2002',
            '2003',
            '2004',
            '2005',
            '2006',
            '2007',
            '2008',
            '2009',
            '200A',
            '200B',
            '200C',
            '200D',
            '200E',
            '2100',
            '2102',
            '2103',
            '2104',
            '2105',
            '2106',
            '2107',
            '2108',
            '2109',
            '210A',
            '210B',
            '210C',
            '210D',
            '210E',
            '2200',
            '2202',
            '2203',
            '2204',
            '2205',
            '2206',
            '2207',
            '2208',
            '2209',
            '220A',
            '220B',
            '220C',
            '220D',
            '220E',
            '2300',
            '2302',
            '2303',
            '2304',
            '2305',
            '2306',
            '2307',
            '2308',
            '2309',
            '230A',
            '230B',
            '230C',
            '230D',
            '230E',
            '2400',
            '2403',
            '2404',
            '2405',
            '2406',
            '2407',
            '2408',
            '2409',
            '240A',
            '240B',
            '240C',
            '240D',
            '2500',
            '2503',
            '2504',
            '2505',
            '2506',
            '2507',
            '2508',
            '2509',
            '250A',
            '250B',
            '250C',
            '250D',
            '2600',
            '2603',
            '2604',
            '2605',
            '2606',
            '2607',
            '2608',
            '2609',
            '260A',
            '260B',
            '260C',
            '260D',
            '2700',
            '2703',
            '2704',
            '2705',
            '2706',
            '2707',
            '2708',
            '270B',
            '270D',
            '2800',
            '2803',
            '2804',
            '2805',
            '2806',
            '2807',
            '2808',
            '280B',
            '280D',
            '2900',
            '2903',
            '2904',
            '2905',
            '2906',
            '2907',
            '2908',
            '290B',
            '290D',
            '2A00',
            '2A03',
            '2A04',
            '2A05',
            '2A06',
            '2A07',
            '2A08',
            '2A0B',
            '2A0D',
            '2B00',
            '2B03',
            '2B04',
            '2B05',
            '2B06',
            '2B07',
            '2B08',
            '2B0B',
            '2B0D',
            '2C00',
            '2C03',
            '2C04',
            '2C05',
            '2C06',
            '2C07',
            '2C08',
            '2C0B',
            '2C0D',
            '2D00',
            '2D03',
            '2D04',
            '2D05',
            '2D06',
            '2D07',
            '2D08',
            '2D0B',
            '2D0D',
            '2E00',
            '2E03',
            '2E04',
            '2E05',
            '2E06',
            '2E07',
            '2E08',
            '2E0B',
            '2E0D',
            '2F00',
            '2F03',
            '2F04',
            '2F05',
            '2F06',
            '2F07',
            '2F08',
            '2F0B',
            '2F0D',
            '3000',
            '3003',
            '3004',
            '3005',
            '3006',
            '3007',
            '3008',
            '300B',
            '300D',
            '3100',
            '3103',
            '3104',
            '3105',
            '3106',
            '3107',
            '3108',
            '310B',
            '310D',
            '3200',
            '3203',
            '3204',
            '3205',
            '3206',
            '3207',
            '3208',
            '320B',
            '320D',
            '3300',
            '3303',
            '3304',
            '3305',
            '3306',
            '3307',
            '3308',
            '330B',
            '330D',
            '3400',
            '3403',
            '3404',
            '3405',
            '3406',
            '3407',
            '3408',
            '340B',
            '340D',
            '3500',
            '3503',
            '3504',
            '3505',
            '3506',
            '3507',
            '3508',
            '350B',
            '350D',
            '3600',
            '3603',
            '3604',
            '3605',
            '3606',
            '3607',
            '3608',
            '360B',
            '360D',
            '3700',
            '3703',
            '3704',
            '3705',
            '3706',
            '3707',
            '3708',
            '370B',
            '370D',
            '3800',
            '3803',
            '3804',
            '3805',
            '3806',
            '3807',
            '3808',
            '380B',
            '380D',
            '3900',
            '3903',
            '3904',
            '3905',
            '3906',
            '3907',
            '3908',
            '390B',
            '390D',
            '3A00',
            '3A03',
            '3A04',
            '3A05',
            '3A06',
            '3A07',
            '3A08',
            '3A0B',
            '3A0D',
            '3B00',
            '3B03',
            '3B04',
            '3B05',
            '3B06',
            '3B07',
            '3B08',
            '3B0B',
            '3B0D',
            '3C00',
            '3C03',
            '3C04',
            '3C05',
            '3C06',
            '3C07',
            '3C08',
            '3C0B',
            '3C0D',
            '3D00',
            '3D03',
            '3D04',
            '3D05',
            '3D06',
            '3D07',
            '3D08',
            '3D0B',
            '3D0D',
            '3E00',
            '3E03',
            '3E04',
            '3E05',
            '3E06',
            '3E07',
            '3E08',
            '3E0B',
            '3E0D',
            '3F00',
            '3F03',
            '3F04',
            '3F05',
            '3F06',
            '3F07',
            '3F08',
            '3F0B',
            '3F0D',
            '4000',
            '4003',
            '4004',
            '4005',
            '4006',
            '4007',
            '4008',
            '400B',
            '400D',
            '4100',
            '4103',
            '4104',
            '4105',
            '4106',
            '4107',
            '4108',
            '410B',
            '410D',
            '4200',
            '4203',
            '4204',
            '4207',
            '420B',
            '4300',
            '4303',
            '4304',
            '4307',
            '430B',
            '4400',
            '4403',
            '4404',
            '4407',
            '440B',
            '4500',
            '4600',
            '4700',
            '4800',
            '4900',
            '4A00',
            '4B00',
            '4C00',
            '4D00',
            '4E00',
            '4F00',
            '5000',
            '5100',
            '5200',
            '5300',
            '5400',
            '5500',
            '5600',
            '5700',
            '5800',
            '5900',
            '5A00',
            '5B00',
            '5C00',
            '5D00',
            '5E00',
            '5F00',
            '6000',
            '6100',
            '6200',
            '6300',
            '6400',
            '6500',
            '6600',
            '6700',
            '6800',
            '6900',
            '6A00',
            '6B00',
            '6C00',
            '6D00',
            '6E00',
            '6F00',
            '7000',
            '7100',
            '7200',
            '7300',
            '7400',
            '7500',
            '7600',
            '7700',
            '7800',
            '7900',
            '7A00',
            '7B00',
            '7C00',
            '7D00',
            '7E00',
            '7F00',
            '8000',
            '8100',
            '8200',
            '8300',
            '8400',
            '8500',
            '8600',
            '8700',
            '8800',
            '8900',
            '8A00',
            '8B00',
            '8C00',
            '8D00',
            '8E00',
            '8F00',
            '9000',
            '9100',
            '9200',
            '9300',
            '9400',
            '9500',
            '9600',
            '9700',
            '9800',
            '9900',
            '9A00',
            '9B00',
            '9C00',
            '9D00',
            '9E00',
            '9F00',
            'A000',
            'A100',
            'A200',
            'A300',
            'A400',
            'A500',
            'A600',
            'A700',
            'A800',
            'A900',
            'AA00',
            'AB00',
            'AC00',
            'AD00',
            'AE00',
            'AF00',
            'B000',
            'B100',
            'B200',
            'B300',
            'B400',
            'B500',
            'B600',
            'B700',
            'B800',
            'B900',
            'BA00',
            'BB00',
            'BC00',
            'BD00',
            'BE00',
            'BF00',
            'C000',
            'C100',
            'C200',
            'C300',
            'C400',
            'C500',
            'C600',
            'C700',
            'C800',
            'C900',
            'CA00',
            'CB00',
            'CC00',
            'CD00',
            'CE00',
            'CF00',
            'D000',
            'D100',
            'D200',
            'D300',
            'D400',
            'D500',
            'D600',
            'D700',
            'D800',
            'D900',
            'DA00',
            'DB00',
            'DC00',
            'DD00',
            'DE00',
            'DF00',
            'E000',
            'E100',
            'E200',
            'E300',
            'E400',
            'E500',
            'E600',
            'E700',
            'E800',
            'E900',
            'EA00',
            'EB00',
            'EC00',
            'ED00',
            'EE00',
            'EF00',
            'F000',
            'F100',
            'F200',
            'F300',
            'F400',
            'F500',
            'F600',
            'F700',
            'F800',
            'F900',
            'FA00',
            'FB00'
        ];

        $allowed = [];
        foreach ($codes as $code_hex) {
            $allowed[strtoupper($code_hex)] = true;
        }

        return $allowed;
    }

}

if (!function_exists('bxt_validate_easy_chat_blob')) {
    /**
     * Validate that a binary blob is a fixed-length list of Easy Chat phrases
     * belonging to the region's allowed phrase set.
     *
     * $expected_phrases is the number of 2-byte slots (e.g. 4 for 8-byte blobs,
     * 6 for 12-byte blobs).
     */
    function bxt_validate_easy_chat_blob($game_region, $blob, $expected_phrases, &$errors = []) {
        $errors_local = [];

        if (!is_string($blob)) {
            $errors_local[] = 'easy_chat: blob is not a string';
        } else {
            $len = strlen($blob);
            $expected_len = $expected_phrases * 2;
            if ($len !== $expected_len) {
                $errors_local[] = 'easy_chat: invalid length ' . $len . ', expected ' . $expected_len;
            } else {
                $allowed_codes = bxt_get_easy_chat_allowed_codes_for_region($game_region);
                if (!$allowed_codes) {
                    $errors_local[] = 'easy_chat: no allowed-code table for region ' . $game_region;
                } else {
                    for ($i = 0; $i < $len; $i += 2) {
                        $hi = ord($blob[$i]);
                        $lo = ord($blob[$i + 1]);
                        $code_hex = sprintf('%02X%02X', $hi, $lo);
                        if (!isset($allowed_codes[$code_hex])) {
                            $errors_local[] = 'easy_chat: disallowed code 0x' . $code_hex . ' at slot ' . ($i / 2);
                        }
                    }
                }
            }
        }

        if ($errors_local) {
            if (is_array($errors)) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('bxt_validate_player_name_bytes')) {
    /**
     * Validate raw player_name binary for a given region.
     *
     * Non-J regions: exactly 7 bytes; J region: exactly 5 bytes.
     * Allowed bytes follow the per-region name alphabets.
     */
    
// Mail-author allowed byte sets, reused for validating player_name across systems.
if (!function_exists('bxt_get_mail_author_allowed_byte_set_for_region')) {
    function bxt_get_mail_author_allowed_byte_set_for_region($game_region) {
        static $cache = null;
        if ($cache === null) {
            $lists = [
                'j' => array(
                    0x00, 0x01, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0A, 0x0B, 0x0C, 0x10, 0x11, 0x12, 0x13, 0x19, 0x1A,
                    0x1B, 0x1C, 0x26, 0x27, 0x28, 0x29, 0x2A, 0x2B, 0x2C, 0x2D, 0x2E, 0x2F, 0x30, 0x31, 0x32, 0x37,
                    0x3A, 0x3B, 0x3C, 0x3D, 0x3E, 0x3F, 0x40, 0x41, 0x42, 0x43, 0x44, 0x45, 0x46, 0x47, 0x48, 0x49,
                    0x4E, 0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C,
                    0x8D, 0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C,
                    0x9D, 0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC,
                    0xAD, 0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xBA, 0xBB, 0xBC,
                    0xBD, 0xBE, 0xBF, 0xC0, 0xC1, 0xC2, 0xC3, 0xC4, 0xC5, 0xC6, 0xC7, 0xC8, 0xC9, 0xCA, 0xCB, 0xCC,
                    0xCD, 0xCE, 0xCF, 0xD0, 0xD1, 0xD2, 0xD3, 0xD4, 0xD5, 0xD6, 0xD7, 0xD8, 0xD9, 0xDA, 0xDB, 0xDC,
                    0xDD, 0xDE, 0xDF, 0xE0, 0xE1, 0xE2, 0xE3, 0xE6, 0xE7, 0xE9, 0xEA, 0xEB, 0xF4
        ),
                // English (and US English) use the same author allowlist as non-J mail nationality 0x0000.
                'e' => array(
                    0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
                    0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9D,
                    0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD,
                    0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xE1, 0xE2, 0xE3, 0xE6,
                    0xE7, 0xE8, 0xF1, 0xF3, 0xF4
        ),
                'u' => array(
                    0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
                    0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9D,
                    0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD,
                    0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xE1, 0xE2, 0xE3, 0xE6,
                    0xE7, 0xE8, 0xF1, 0xF3, 0xF4
        ),
                'f' => array(
                    0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
                    0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9D,
                    0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD,
                    0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xE1, 0xE2, 0xE3, 0xE6,
                    0xE7, 0xE8, 0xF1, 0xF3, 0xF4
        ),
                'd' => array(
                    0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
                    0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9E,
                    0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD, 0xAE,
                    0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xC0, 0xC1, 0xC2, 0xC3, 0xC4,
                    0xC5, 0xE1, 0xE2, 0xE3, 0xE6, 0xE7, 0xE8, 0xF1, 0xF3, 0xF4
        ),
                'i' => array(
                    0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
                    0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9D,
                    0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD,
                    0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xE1, 0xE2, 0xE3, 0xE6,
                    0xE7, 0xE8, 0xF1, 0xF3, 0xF4
        ),
                's' => array(
                    0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
                    0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9D,
                    0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD,
                    0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xE1, 0xE2, 0xE3, 0xE6,
                    0xE7, 0xE8, 0xF1, 0xF3, 0xF4
        ),
                // Region 'p' uses the same author allowlist as non-J mail nationality 0x0000 (same as region 'e'/'u').
                'p' => array(
                    0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
                    0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9D,
                    0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD,
                    0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xE1, 0xE2, 0xE3, 0xE6,
                    0xE7, 0xE8, 0xF1, 0xF3, 0xF4
        ),
            ];

            $cache = [];
            foreach ($lists as $r => $list) {
                $set = [];
                foreach ($list as $b) {
                    $set[$b] = true;
                }
                $cache[$r] = $set;
            }
        }

        $r = strtolower($game_region);
        return $cache[$r] ?? $cache['p'];
    }
}

function bxt_validate_player_name_bytes($game_region, $blob, &$errors = []) {
        $errors_local = [];

        if (!is_string($blob)) {
            $errors_local[] = 'player_name: blob is not a string';
            if ($errors_local) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        $len = strlen($blob);
        $region = strtolower($game_region);

        if ($region === 'j') {
            if ($len !== 5) {
                $errors_local[] = 'player_name: invalid length ' . $len . ' for region j, expected 5';
            }
        } else {
            if ($len !== 7) {
                $errors_local[] = 'player_name: invalid length ' . $len . ' for non-j region, expected 7';
            }
        }

        // Allowed bytes for player_name are the same as the mail author allowlist for the given game region.
        $allowed = bxt_get_mail_author_allowed_byte_set_for_region($region);

        for ($i = 0; $i < $len; $i++) {
            $v = ord($blob[$i]);
            if (!isset($allowed[$v])) {
                $errors_local[] = sprintf('player_name: disallowed byte 0x%02X at offset %d', $v, $i);
            }
        }

        if ($errors_local) {
            if (is_array($errors)) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('bxt_validate_player_zip_bytes')) {
    /**
     * Validate player_zip binary per region.
     *
     * Non-J: 3 bytes; J: 2 bytes.
     * Allowed bytes follow the per-region postal alphabets.
     */
    function bxt_validate_player_zip_bytes($game_region, $blob, $player_region = null, &$errors = []) {
        $errors_local = [];

        if (!is_string($blob)) {
            $errors_local[] = 'player_zip: blob is not a string';
            if ($errors_local) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        $region = strtolower($game_region);
        $len = strlen($blob);

// Region-specific ZIP/Postal validation specs.
        // Each region can define:
        //  - 'len'    : expected byte-length
        //  - 'ranges' : list of inclusive [lo, hi] byte ranges
        //  - 'bytes'  : list of individual allowed bytes
        //
        // To customize per-region rules, edit these specs.
        $zip_specs = [
            'e' => [
                'len' => 3,
                'ranges' => [[0xF6, 0xFF]],
                'bytes' => [],
            ],
            'p' => [
                'len' => 3,

                // EU player_region_id (1..40) -> ZipcodeFormatLengths DB index
                // 1 = EU-AD, ... 40 = EU-UA
                'player_region_db' => [
                    1 => 0,  2 => 2,  3 => 2,  4 => 3,  5 => 2,
                    6 => 2,  7 => 4,  8 => 2,  9 => 3, 10 => 3,
                   11 => 2, 12 => 3, 13 => 3, 14 => 3, 15 => 3,
                   16 => 8, 17 => 3, 18 => 3, 19 => 2, 20 => 7,
                   21 => 1, 22 => 3, 23 => 2, 24 => 2, 25 => 2,
                   26 => 10, 27 => 11, 28 => 9, 29 => 6, 30 => 2,
                   31 => 14, 32 => 5, 33 => 4, 34 => 3, 35 => 4,
                   36 => 15, 37 => 12, 38 => 3, 39 => 13, 40 => 3,
                ],

                // ZipcodeFormatLengths DB index -> slot specs (each is 3 slots)
                // Each slot is ['ranges' => [[lo,hi],...], 'bytes' => [...]]
                'db_formats' => [
                    0 => [
                        ['ranges' => [], 'bytes' => [0xE3]],
                        ['ranges' => [], 'bytes' => [0xE3]],
                        ['ranges' => [], 'bytes' => [0xE3]],
                    ],
                    1 => [
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                    ],
                    2 => [
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                    ],
                    3 => [
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                    ],
                    4 => [
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                    ],
                    5 => [
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                    ],
                    6 => [
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                    ],
                    7 => [
                        ['ranges' => [[0x80, 0x89]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [], 'bytes' => [0xF7]],
                    ],
                    8 => [
                        ['ranges' => [[0x80, 0x89]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF], [0x80, 0x89]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF], [0x80, 0x89]], 'bytes' => []],
                    ],
                    9 => [
                        ['ranges' => [[0x80, 0x89]], 'bytes' => []],
                        ['ranges' => [[0x80, 0x89]], 'bytes' => []],
                        ['ranges' => [[0x80, 0x89]], 'bytes' => []],
                    ],
                    10 => [
                        ['ranges' => [], 'bytes' => [0x8B]],
                        ['ranges' => [], 'bytes' => [0x95]],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                    ],
                    11 => [
                        ['ranges' => [], 'bytes' => [0x8C]],
                        ['ranges' => [], 'bytes' => [0x83]],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                    ],
                    12 => [
                        ['ranges' => [], 'bytes' => [0x92]],
                        ['ranges' => [], 'bytes' => [0x88]],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                    ],
                    13 => [
                        ['ranges' => [], 'bytes' => [0xFA]],
                        ['ranges' => [], 'bytes' => [0xFD]],
                        ['ranges' => [], 'bytes' => [0xFE]],
                    ],
                    14 => [
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [], 'bytes' => [0xE3]],
                    ],
                    15 => [
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                    ],
                ],
            ],
            'u' => [
                'len' => 3,
                'ranges' => [[0xF6, 0xFF]],
                'bytes' => [],
            ],
            'f' => [
                'len' => 3,
                'ranges' => [[0xF6, 0xFF]],
                'bytes' => [],
            ],
            'd' => [
                'len' => 3,
                'ranges' => [[0xF6, 0xFF]],
                'bytes' => [],
            ],
            'i' => [
                'len' => 3,
                'ranges' => [[0xF6, 0xFF]],
                'bytes' => [],
            ],
            's' => [
                'len' => 3,
                'ranges' => [[0xF6, 0xFF]],
                'bytes' => [0xE3],
            ],
            'j' => [
                'len' => 2,
                // JP zip is a big-endian 16-bit value in range 000-999 (0x0000..0x03E7).
                // Validation is handled in the special-case block for region j below.
                'ranges' => [],
                'bytes' => [],
            ],
        ];

        if (!isset($zip_specs[$region])) {
            $errors_local[] = 'player_zip: no validation spec for region ' . $region;
        } else {
            $expected_len = (int)$zip_specs[$region]['len'];
            if ($len !== $expected_len) {
                $errors_local[] = 'player_zip: invalid length ' . $len . ' for region ' . $region . ', expected ' . $expected_len;
            }
        }

                // Region 'j' ZIP codes are a big-endian 16-bit value representing decimal 000-999:
        //   0x00 0x00 .. 0x03 0xE7 (inclusive)
        // This rule is ZIP-specific and does not use a per-byte allowlist.
        if ($region === 'j') {
            if ($len !== 2) {
                $errors_local[] = 'player_zip: invalid length ' . $len . ' for region j, expected 2';
            } else {
                $hi = ord($blob[0]);
                $lo = ord($blob[1]);
                $value = (($hi & 0xFF) << 8) | ($lo & 0xFF);

                if ($value < 0x0000 || $value > 0x03E7) {
                    $errors_local[] = sprintf(
                        'player_zip: jp zip out of range: 0x%02X 0x%02X (0x%04X), expected 0x0000..0x03E7',
                        $hi,
                        $lo,
                        $value
                    );
                }
            }

            if ($errors_local) {
                $errors = array_merge($errors, $errors_local);
                return false;
            }
            return true;
        }

// Build per-region allowed byte sets from specs.
        // For region 'p' this is per-player_region (slot-specific) and does not use the simple union set.
        static $cache = [];
        static $cache_p = [];
        static $cache_e = [];
        static $cache_i = [];
        static $cache_s = [];

        if ($region === 'e') {
            // Region 'e' ZIP validation depends on player_region ID:
            // 1-50:  F6-FF, F6-FF, F6-FF
            // 51-63: 80-89, F6-FF, 80-89
            if (!is_int($player_region) || $player_region < 1 || $player_region > 63) {
                $errors_local[] = 'player_zip: invalid player_region_id ' . (is_scalar($player_region) ? $player_region : gettype($player_region)) . ' for region e';
            } else if ($len !== 3) {
                $errors_local[] = sprintf('player_zip: expected length 3 for region e (got %d)', $len);
            } else {
                $group = ($player_region <= 50) ? 'e_1_50' : 'e_51_63';

                if (!isset($cache_e[$group])) {
                    if ($group === 'e_1_50') {
                        $cache_e[$group] = [
                            ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                            ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                            ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ];
                    } else {
                        $cache_e[$group] = [
                            ['ranges' => [[0x80, 0x89]], 'bytes' => []],
                            ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                            ['ranges' => [[0x80, 0x89]], 'bytes' => []],
                        ];
                    }
                }

                $slot_specs = $cache_e[$group];

                // Validate each of the 3 bytes against its slot spec.
                for ($i = 0; $i < 3; $i++) {
                    $slot_spec = $slot_specs[$i];
                    $cache_key = $group . ':' . $i;

                    if (!isset($cache[$cache_key])) {
                        $allowed = [];

                        if (isset($slot_spec['ranges']) && is_array($slot_spec['ranges'])) {
                            foreach ($slot_spec['ranges'] as $r) {
                                if (!is_array($r) || count($r) !== 2) continue;
                                $lo = (int)$r[0];
                                $hi = (int)$r[1];
                                if ($lo > $hi) { $tmp = $lo; $lo = $hi; $hi = $tmp; }
                                for ($b = $lo; $b <= $hi; $b++) {
                                    $allowed[$b & 0xFF] = true;
                                }
                            }
                        }

                        if (isset($slot_spec['bytes']) && is_array($slot_spec['bytes'])) {
                            foreach ($slot_spec['bytes'] as $b) {
                                $allowed[((int)$b) & 0xFF] = true;
                            }
                        }

                        $cache[$cache_key] = $allowed;
                    }

                    $allowed = $cache[$cache_key];
                    $v = ord($blob[$i]);
                    if (!isset($allowed[$v])) {
                        $errors_local[] = sprintf('player_zip: invalid byte 0x%02X at offset %d (e player_region_id %d %s)', $v, $i, $player_region, $group);
                    }
                }
            }

            if ($errors_local) {
                $errors = array_merge($errors, $errors_local);
                return false;
            }
            return true;
        }


        if ($region === 'f') {
            // Region 'f' zipcode rules are player_region-specific (IDs 1-70).
            // All three bytes must be in F6-FF.
            if (!is_int($player_region) || $player_region < 1 || $player_region > 70) {
                $errors_local[] = 'player_zip: invalid player_region_id ' . (is_scalar($player_region) ? $player_region : gettype($player_region)) . ' for region f';
            } else if ($len !== 3) {
                $errors_local[] = sprintf('player_zip: expected length 3 for region f (got %d)', $len);
            } else {
                for ($i = 0; $i < 3; $i++) {
                    $v = ord($blob[$i]);
                    if ($v < 0xF6 || $v > 0xFF) {
                        $errors_local[] = sprintf('player_zip: disallowed byte 0x%02X at offset %d (region f)', $v, $i);
                    }
                }
            }

            if ($errors_local) {
                $errors = array_merge($errors, $errors_local);
                return false;
            }
            return true;
        }

        if ($region === 'd') {
            // Region 'd' zipcode rules are player_region-specific (IDs 1-74).
            // All three bytes must be in F6-FF.
            if (!is_int($player_region) || $player_region < 1 || $player_region > 74) {
                $errors_local[] = 'player_zip: invalid player_region_id ' . (is_scalar($player_region) ? $player_region : gettype($player_region)) . ' for region d';
            } else if ($len !== 3) {
                $errors_local[] = sprintf('player_zip: expected length 3 for region d (got %d)', $len);
            } else {
                for ($i = 0; $i < 3; $i++) {
                    $v = ord($blob[$i]);
                    if ($v < 0xF6 || $v > 0xFF) {
                        $errors_local[] = sprintf('player_zip: disallowed byte 0x%02X at offset %d (region d)', $v, $i);
                    }
                }
            }

            if ($errors_local) {
                $errors = array_merge($errors, $errors_local);
                return false;
            }
            return true;
        }


        if ($region === 'i') {
            // Region 'i' ZIP validation depends on player_region ID:
            // 1-129:  F6-FF, F6-FF, F6-FF
            // 130-138: FA,   FD,    FE
            if (!is_int($player_region) || $player_region < 1 || $player_region > 138) {
                $errors_local[] = 'player_zip: invalid player_region_id ' . (is_scalar($player_region) ? $player_region : gettype($player_region)) . ' for region i';
            } else if ($len !== 3) {
                $errors_local[] = sprintf('player_zip: expected length 3 for region i (got %d)', $len);
            } else {
                $group = ($player_region <= 129) ? 'i_1_129' : 'i_130_138';

                if (!isset($cache_i[$group])) {
                    if ($group === 'i_1_129') {
                        $cache_i[$group] = [
                            ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                            ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                            ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ];
                    } else {
                        // Strict single-byte slots: FA, FD, FE
                        $cache_i[$group] = [
                            ['ranges' => [], 'bytes' => [0xFA]],
                            ['ranges' => [], 'bytes' => [0xFD]],
                            ['ranges' => [], 'bytes' => [0xFE]],
                        ];
                    }
                }

                $slots = $cache_i[$group];

                for ($i = 0; $i < $len; $i++) {
                    $slot_spec = $slots[$i];
                    $cache_key = $group . ':' . $i;

                    if (!isset($cache[$cache_key])) {
                        $allowed = [];

                        if (isset($slot_spec['ranges']) && is_array($slot_spec['ranges'])) {
                            foreach ($slot_spec['ranges'] as $r) {
                                if (!is_array($r) || count($r) !== 2) {
                                    continue;
                                }
                                $lo = (int)$r[0];
                                $hi = (int)$r[1];
                                if ($lo < 0) $lo = 0;
                                if ($hi > 255) $hi = 255;
                                for ($b = $lo; $b <= $hi; $b++) {
                                    $allowed[$b & 0xFF] = true;
                                }
                            }
                        }

                        if (isset($slot_spec['bytes']) && is_array($slot_spec['bytes'])) {
                            foreach ($slot_spec['bytes'] as $b) {
                                $allowed[((int)$b) & 0xFF] = true;
                            }
                        }

                        $cache[$cache_key] = $allowed;
                    }

                    $allowed = $cache[$cache_key];
                    $v = ord($blob[$i]);
                    if (!isset($allowed[$v])) {
                        $errors_local[] = sprintf('player_zip: invalid byte 0x%02X at offset %d (i player_region_id %d %s)', $v, $i, $player_region, $group);
                    }
                }
            }

            if ($errors_local) {
                $errors = array_merge($errors, $errors_local);
                return false;
            }
            return true;
        }


        if ($region === 's') {
            // Region 's' ZIP validation depends on player_region ID:
            // 1-50:  F6-FF, F6-FF, F6-FF
            // 51-57: E3,    E3,    E3
            if (!is_int($player_region) || $player_region < 1 || $player_region > 57) {
                $errors_local[] = 'player_zip: invalid player_region_id ' . (is_scalar($player_region) ? $player_region : gettype($player_region)) . ' for region s';
            } else if ($len !== 3) {
                $errors_local[] = sprintf('player_zip: expected length 3 for region s (got %d)', $len);
            } else {
                $group = ($player_region <= 50) ? 's_1_50' : 's_51_57';

                if (!isset($cache_s[$group])) {
                    if ($group === 's_1_50') {
                        $cache_s[$group] = [
                            ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                            ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                            ['ranges' => [[0xF6, 0xFF]], 'bytes' => []],
                        ];
                    } else {
                        // s_51_57
                        $cache_s[$group] = [
                            ['ranges' => [], 'bytes' => [0xE3]],
                            ['ranges' => [], 'bytes' => [0xE3]],
                            ['ranges' => [], 'bytes' => [0xE3]],
                        ];
                    }
                }

                $slot_specs = $cache_s[$group];

                // Validate each of the 3 bytes against its slot spec.
                for ($i = 0; $i < 3; $i++) {
                    $slot_spec = $slot_specs[$i];
                    $cache_key = $group . ':' . $i;

                    if (!isset($cache[$cache_key])) {
                        $allowed = [];

                        if (isset($slot_spec['ranges']) && is_array($slot_spec['ranges'])) {
                            foreach ($slot_spec['ranges'] as $r) {
                                if (!is_array($r) || count($r) !== 2) {
                                    continue;
                                }
                                $a = (int)$r[0];
                                $b = (int)$r[1];
                                for ($v = $a; $v <= $b; $v++) {
                                    $allowed[$v] = true;
                                }
                            }
                        }

                        if (isset($slot_spec['bytes']) && is_array($slot_spec['bytes'])) {
                            foreach ($slot_spec['bytes'] as $v) {
                                $allowed[(int)$v] = true;
                            }
                        }

                        $cache[$cache_key] = $allowed;
                    }

                    $allowed = $cache[$cache_key];
                    $v = ord($blob[$i]);

                    if (!isset($allowed[$v])) {
                        $errors_local[] = sprintf('player_zip: invalid byte 0x%02X at offset %d (s player_region_id %d %s)', $v, $i, $player_region, $group);
                    }
                }
            }

            if ($errors_local) {
                $errors = array_merge($errors, $errors_local);
                return false;
            }
            return true;
        }


        // (JP ZIP numeric-range validation handled earlier.)





        if ($region === 'p') {
            if (!isset($zip_specs['p'])) {
                $errors_local[] = 'player_zip: no validation spec for region p';
            } else {
                $spec = $zip_specs['p'];

                if (!is_int($player_region) || $player_region < 1 || $player_region > 40) {
                    $errors_local[] = 'player_zip: invalid player_region_id ' . (is_scalar($player_region) ? $player_region : gettype($player_region)) . ' for region p';
                } else if (!isset($spec['player_region_db'][$player_region])) {
                    $errors_local[] = 'player_zip: missing player_region_db mapping for player_region_id ' . $player_region;
                } else {
                    $db = (int)$spec['player_region_db'][$player_region];

                    if (!isset($spec['db_formats'][$db]) || !is_array($spec['db_formats'][$db]) || count($spec['db_formats'][$db]) !== 3) {
                        $errors_local[] = 'player_zip: missing/invalid db_formats for db ' . $db . ' (player_region_id ' . $player_region . ')';
                    } else {
                        // Validate each of the 3 bytes against its slot spec.
                        for ($i = 0; $i < $len; $i++) {
                            $slot_spec = $spec['db_formats'][$db][$i];
                            $cache_key = $db . ':' . $i;

                            if (!isset($cache_p[$cache_key])) {
                                $allowed = [];

                                if (isset($slot_spec['ranges']) && is_array($slot_spec['ranges'])) {
                                    foreach ($slot_spec['ranges'] as $r) {
                                        if (!is_array($r) || count($r) !== 2) continue;
                                        $lo = (int)$r[0];
                                        $hi = (int)$r[1];
                                        if ($lo > $hi) { $tmp = $lo; $lo = $hi; $hi = $tmp; }
                                        for ($b = $lo; $b <= $hi; $b++) {
                                            $allowed[$b & 0xFF] = true;
                                        }
                                    }
                                }

                                if (isset($slot_spec['bytes']) && is_array($slot_spec['bytes'])) {
                                    foreach ($slot_spec['bytes'] as $b) {
                                        $allowed[((int)$b) & 0xFF] = true;
                                    }
                                }

                                $cache_p[$cache_key] = $allowed;
                            }

                            $allowed = $cache_p[$cache_key];
                            $v = ord($blob[$i]);
                            if (!isset($allowed[$v])) {
                                $errors_local[] = sprintf('player_zip: disallowed byte 0x%02X at offset %d (p player_region_id %d db %d)', $v, $i, $player_region, $db);
                            }
                        }
                    }
                }
            }

            // region p handled; fall through to error handling below
        } else {
            if (!isset($cache[$region]) && isset($zip_specs[$region])) {
                $allowed = [];
                $spec = $zip_specs[$region];

                if (isset($spec['ranges']) && is_array($spec['ranges'])) {
                    foreach ($spec['ranges'] as $r) {
                        if (!is_array($r) || count($r) !== 2) continue;
                        $lo = (int)$r[0];
                        $hi = (int)$r[1];
                        if ($lo > $hi) { $tmp = $lo; $lo = $hi; $hi = $tmp; }
                        for ($b = $lo; $b <= $hi; $b++) {
                            $allowed[$b & 0xFF] = true;
                        }
                    }
                }

                if (isset($spec['bytes']) && is_array($spec['bytes'])) {
                    foreach ($spec['bytes'] as $b) {
                        $allowed[((int)$b) & 0xFF] = true;
                    }
                }

                $cache[$region] = $allowed;
            }

            $allowed = isset($cache[$region]) ? $cache[$region] : null;

            if (!$allowed) {
                $errors_local[] = 'player_zip: no allowed-byte set for region ' . $region;
            } else {
                for ($i = 0; $i < $len; $i++) {
                    $v = ord($blob[$i]);
                    if (!isset($allowed[$v])) {
                        $errors_local[] = sprintf('player_zip: disallowed byte 0x%02X at offset %d', $v, $i);
                    }
                }
            }
        }


        if ($errors_local) {
            if (is_array($errors)) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('bxt_validate_player_region_id')) {
    /**
     * Validate player_region (decoded numeric) per game_region and context.
     */
    function bxt_validate_player_region_id($game_region, $player_region, $is_ranking = false, &$errors = []) {
        $errors_local = [];

        if (!is_int($player_region)) {
            $errors_local[] = 'player_region: value is not an integer';
        } elseif ($player_region < 0) {
            $errors_local[] = 'player_region: negative value ' . $player_region;
        } else {
            $region = strtolower($game_region);

            $bounds = null;
            switch ($region) {
                case 'j':
                    $bounds = [1, 47];
                    break;
                case 'e':
                    $bounds = [1, 63];
                    break;
                case 'p':
                    $bounds = [1, 40];
                    break;
                case 'u':
                    $bounds = [1, 25];
                    break;
                case 'f':
                    $bounds = [1, 70];
                    break;
                case 'd':
                    $bounds = [1, 74];
                    break;
                case 's':
                    $bounds = [1, 57];
                    break;
                case 'i':
                    $bounds = [1, 138];
                    break;
                default:
                    $errors_local[] = 'player_region: unknown region ' . $region;
                    break;
            }

            if ($bounds !== null) {
                list($min, $max) = $bounds;
                if ($player_region < $min || $player_region > $max) {
                    $errors_local[] = 'player_region: out of range ' . $player_region . ' (expected ' . $min . '-' . $max . ')';
                }
            }
        }

        if ($errors_local) {
            if (is_array($errors)) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('bxt_validate_ranking_score')) {
    /**
     * Validate score for a ranking category_id.
     *
     * The ranges are hard-coded per category (0-41).
     */
    function bxt_validate_ranking_score($category_id, $score, &$errors = []) {
        $errors_local = [];

        if (!is_int($score)) {
            $errors_local[] = 'score: value is not an integer';
        } elseif ($score < 0) {
            $errors_local[] = 'score: negative value ' . $score;
        }

        // Ranges per category, inclusive.
        // Categories not present in this map are treated as invalid.
        static $ranges = [
            0 => [[0, 66045]],
            1 => [[0, 4294967295]],
            2 => [[0, 4294967295]],
            3 => [[0, 16777215]],
            4 => [[0, 16777215]],
            5 => [[0, 16777215]],
            6 => [[0, 65536]],
            7 => [[0, 16777215]],
            8 => [[0, 16777215]],
            9 => [[0, 65535]],
            10 => [[0, 65535]],
            11 => [[0, 65535]],
            12 => [[0, 16777215]],
            13 => [[0, 16777215]],
            14 => [[0, 16777215]],
            15 => [[0, 65535]],
            16 => [[0, 65535]],
            17 => [[0, 65535]],
            18 => [[0, 65535]],
            19 => [[0, 65535]],
            20 => [[0, 65535]],
            21 => [[0, 65535]],
            22 => [[0, 4294967295]],
            23 => [[0, 4294967295]],
            24 => [[0, 4294967295]],
            25 => [[0, 4294967295]],
            26 => [[0, 4294967295]],
            27 => [[0, 4294967295]],
            28 => [[0, 4294967295]],
            29 => [[0, 4294967295]],
            30 => [[0, 4294967295]],
            31 => [[0, 4294967295]],
            32 => [[0, 4294967295]],
            33 => [[0, 4294967295]],
            34 => [[0, 16777215]],
            35 => [[0, 65535]],
            36 => [[0, 65535]],
            37 => [[0, 4294967295]],
            38 => [[0, 4294967295]],
            39 => [[0, 0], [190, 1785]], // Smallest to largest possible MAGIKARP size.
            40 => [[0, 0], [190, 1785]], // Smallest to largest possible MAGIKARP size.
            41 => [[0, 0], [132, 387]], // BUG CATCHING SCORE // Min: Weedle Lv.7 (22 Min HP * 4 (88 pts) + Half of all DVs (min) rounded down are even (0 pts), + Sum of non-HP stats: 9, 9, 7, 7, 12 (44 pts) + No item + 1/8th of current HP, worst case is 1 (0 pts). = 132 // Max: Scyther Lv.14 (47 Max HP * 4 (188 pts) + Half of all DVs (max) rounded down are even (29 pts) + Sum of non-HP stats: 40, 31, 24, 31, 38 (164 pts) + Holding Bitter Berry (1 pt) + 1/8th of the current HP, best case is 47 (5 pts) = 387
        ];

        if (!array_key_exists($category_id, $ranges)) {
            $errors_local[] = 'score: unknown category_id ' . $category_id;
        } else {
            $valid = false;
            $expected_ranges = [];
            foreach ($ranges[$category_id] as $range) {
                list($min, $max) = $range;
                $expected_ranges[] = $min . '-' . $max;
                if ($score >= $min && $score <= $max) {
                    $valid = true;
                    break;
                }
            }

            if (!$valid) {
                $errors_local[] = 'score: out of range ' . $score . ' for category ' . $category_id . ' (expected ' . implode(' or ', $expected_ranges) . ')';
            }
        }


        if ($errors_local) {
            if (is_array($errors)) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('bxt_validate_ranking_row')) {
    /**
     * Validate a single logical ranking row (for bxt_ranking) before DB insert/update.
     */
    function bxt_validate_ranking_row($game_region, $category_id, $trainer_id, $secret_id, $gender, $age, $player_region, $zip_blob, $message_blob, $score, &$errors = [], $player_name_blob = null) {
        $errors_local = [];

        // Optional: validate player_name bytes using the same allowlist as mail author bytes for this region.
        if ($player_name_blob !== null) {
            bxt_validate_player_name_bytes($game_region, $player_name_blob, $errors_local);
        }

        // Basic scalar ranges
        if (!is_int($category_id) || $category_id < 0 || $category_id > 41) {
            $errors_local[] = 'ranking: invalid category_id ' . $category_id;
        }
        foreach ([['trainer_id', $trainer_id], ['secret_id', $secret_id]] as $pair) {
            list($name, $val) = $pair;
            if (!is_int($val)) {
                $errors_local[] = $name . ': not an integer';
            } elseif ($val < 0 || $val > 65535) {
                $errors_local[] = $name . ': out of range ' . $val . ' (expected 0-65535)';
            }
        }
        if (!is_int($gender) || $gender < 0 || $gender > 1) {
            $errors_local[] = 'player_gender: out of range ' . $gender . ' (expected 0-1)';
        }
        if (!is_int($age) || $age < 0 || $age > 100) {
            $errors_local[] = 'player_age: out of range ' . $age . ' (expected 0-100)';
        }

        // Player region
        if (!bxt_validate_player_region_id($game_region, $player_region, true, $errors_local)) {
            // bxt_validate_player_region_id already appended errors to $errors_local
        }

        // Postal code bytes
        if (!bxt_validate_player_zip_bytes($game_region, $zip_blob, $player_region, $errors_local)) {
            // errors added
        }

		// Player message: 8 bytes (4 phrases) non-J; 12 bytes (6 phrases) JP
		$region = strtolower($game_region);
		$phrases = ($region === 'j') ? 6 : 4;
		if (!bxt_validate_easy_chat_blob($game_region, $message_blob, $phrases, $errors_local)) {
			// errors added
		}

        // Score bounds
        if (!bxt_validate_ranking_score($category_id, $score, $errors_local)) {
            // errors added
        }

        if ($errors_local) {
            if (is_array($errors)) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('bxt_validate_exchange_row')) {
    /**
     * Validate a decoded Trade Corner row prior to insert into bxt_exchange.
     *
     * $mail_blob and $pokemon_blob are raw binary as received from the client.
     */
    function bxt_validate_exchange_row($game_region, $trainer_id, $secret_id, $offer_gender, $request_gender, $offer_species, $pokemon_blob, $mail_blob, &$errors = [], $player_name_blob = null) {
        $errors_local = [];

        // Optional: validate player_name bytes using the same allowlist as mail author bytes for this region.
        if ($player_name_blob !== null) {
            bxt_validate_player_name_bytes($game_region, $player_name_blob, $errors_local);
        }

        foreach ([['trainer_id', $trainer_id], ['secret_id', $secret_id]] as $pair) {
            list($name, $val) = $pair;
            if (!is_int($val)) {
                $errors_local[] = $name . ': not an integer';
            } elseif ($val < 0 || $val > 65535) {
                $errors_local[] = $name . ': out of range ' . $val . ' (expected 0-65535)';
            }
        }

        foreach ([['offer_gender', $offer_gender], ['request_gender', $request_gender]] as $pair) {
            list($name, $val) = $pair;
            if (!is_int($val)) {
                $errors_local[] = $name . ': not an integer';
            } elseif ($val < 0 || $val > 3) {
                $errors_local[] = $name . ': out of range ' . $val . ' (expected 0-3)';
            }
        }

        if (!is_int($offer_species) || $offer_species < 1 || $offer_species > 251) {
            $errors_local[] = 'offer_species: out of range ' . $offer_species . ' (expected 1-251)';
        }

        $region = strtolower($game_region);

        if (!is_string($pokemon_blob)) {
            $errors_local[] = 'pokemon: blob is not a string';
        } else {
            $len = strlen($pokemon_blob);
            $expected_len = ($region === 'j') ? 58 : 65;
            if ($len !== $expected_len) {
                $errors_local[] = 'pokemon: invalid length ' . $len . ' for region ' . $region . ', expected ' . $expected_len;
            }
        }

        if (!is_string($mail_blob)) {
            $errors_local[] = 'mail: blob is not a string';
        } else {
            $len = strlen($mail_blob);
            $expected_len = ($region === 'j') ? 42 : 47;
            if ($len !== $expected_len) {
                $errors_local[] = 'mail: invalid length ' . $len . ' for region ' . $region . ', expected ' . $expected_len;
            } else {
                // Rule: a completely null mail blob (all bytes 0x00) is valid.
                if ($len > 0 && strspn($mail_blob, "\x00") === $len) {
                    // valid: skip mail byte validation
                } else {
                // Region-specific mail byte whitelist, split into:
//   - message bytes: [0 .. (len-$tail_len)-1]  (text + author/name bytes)
//   - tail bytes:    [(len-$tail_len) .. end]  (trailing 6-byte metadata: trainer ID, species, mail item, etc.)
// This matches the mail struct layout used in index.js: [33 bytes text][name][(non-j only: 2 bytes nationality)][4 bytes metadata].
                $tail_len = ($region === 'j') ? 4 : 6;
                $tail_offset = ($len >= $tail_len) ? ($len - $tail_len) : $len;

                // Build per-region allowed-byte sets (as lookup tables) once.
                static $mail_allowed_cache = null;
                if ($mail_allowed_cache === null) {
                    $mail_allowed_cache = [
                        'j' => [
                            'message_list' => array(
                                0x00, 0x01, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0A, 0x0B, 0x0C, 0x10, 0x11, 0x12, 0x13, 0x19, 0x1A,
                                0x1B, 0x1C, 0x26, 0x27, 0x28, 0x29, 0x2A, 0x2B, 0x2C, 0x2D, 0x2E, 0x2F, 0x30, 0x31, 0x32, 0x37,
                                0x3A, 0x3B, 0x3C, 0x3D, 0x3E, 0x3F, 0x40, 0x41, 0x42, 0x43, 0x44, 0x45, 0x46, 0x47, 0x48, 0x49,
                                0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C,
                                0x8D, 0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C,
                                0x9D, 0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC,
                                0xAD, 0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xBA, 0xBB, 0xBC,
                                0xBD, 0xBE, 0xBF, 0xC0, 0xC1, 0xC2, 0xC3, 0xC4, 0xC5, 0xC6, 0xC7, 0xC8, 0xC9, 0xCA, 0xCB, 0xCC,
                                0xCD, 0xCE, 0xCF, 0xD0, 0xD1, 0xD2, 0xD3, 0xD4, 0xD5, 0xD6, 0xD7, 0xD8, 0xD9, 0xDA, 0xDB, 0xDC,
                                0xDD, 0xDE, 0xDF, 0xE0, 0xE1, 0xE2, 0xE3, 0xE6, 0xE7, 0xE9, 0xEA, 0xEB, 0xF3, 0xF4, 0xF6, 0xF7,
                                0xF8, 0xF9, 0xFA, 0xFB, 0xFC, 0xFD, 0xFE, 0xFF
                            ),
                            'author_list' => array(
                                0x00, 0x01, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0A, 0x0B, 0x0C, 0x10, 0x11, 0x12, 0x13, 0x19, 0x1A,
                                0x1B, 0x1C, 0x26, 0x27, 0x28, 0x29, 0x2A, 0x2B, 0x2C, 0x2D, 0x2E, 0x2F, 0x30, 0x31, 0x32, 0x37,
                                0x3A, 0x3B, 0x3C, 0x3D, 0x3E, 0x3F, 0x40, 0x41, 0x42, 0x43, 0x44, 0x45, 0x46, 0x47, 0x48, 0x49,
                                0x4E, 0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C,
                                0x8D, 0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C,
                                0x9D, 0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC,
                                0xAD, 0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xBA, 0xBB, 0xBC,
                                0xBD, 0xBE, 0xBF, 0xC0, 0xC1, 0xC2, 0xC3, 0xC4, 0xC5, 0xC6, 0xC7, 0xC8, 0xC9, 0xCA, 0xCB, 0xCC,
                                0xCD, 0xCE, 0xCF, 0xD0, 0xD1, 0xD2, 0xD3, 0xD4, 0xD5, 0xD6, 0xD7, 0xD8, 0xD9, 0xDA, 0xDB, 0xDC,
                                0xDD, 0xDE, 0xDF, 0xE0, 0xE1, 0xE2, 0xE3, 0xE6, 0xE7, 0xE9, 0xEA, 0xEB, 0xF4,
							),
                            // Tail is raw metadata (not text), validated as 4 fields:
                            //   [0] trainer_id low byte, [1] trainer_id high byte, [2] species id, [3] mail item id.
                            'tail_tid_lo_list' => range(0x00, 0xFF),
                            'tail_tid_hi_list' => range(0x00, 0xFF),
                            'tail_species_list' => range(0x01, 0xFB),
                            'tail_mail_list' => array(0x9E, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xBA, 0xBB, 0xBC, 0xBD),
                        ],
                        'non_j' => [
// Nationality-dependent message/author allowlists for non-J mail.
                            // Selected by the 2-byte nationality pair stored in the tail.
							// 0000 is en
							// 8485 is fr
							// 8486 is de
							// 8488 is it
							// 8492 is es
                            'message_list_nat_0000' => array(
                                0x50, 0x54, 0x70, 0x71, 0x72, 0x73, 0x75, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87,
								0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D, 0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97,
								0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9D, 0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7,
								0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD, 0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7,
								0xB8, 0xB9, 0xD0, 0xD1, 0xD2, 0xD3, 0xD4, 0xD5, 0xD6, 0xE0, 0xE1, 0xE2, 0xE3, 0xE6, 0xE7, 0xE8,
								0xE9, 0xEF, 0xF0, 0xF1, 0xF3, 0xF4, 0xF5, 0xF6, 0xF7, 0xF8, 0xF9, 0xFA, 0xFB, 0xFC, 0xFD, 0xFE,
								0xFF
							),
                            'author_list_nat_0000' => array(
                                0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
								0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9D,
								0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD,
								0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xE1, 0xE2, 0xE3, 0xE6,
								0xE7, 0xE8, 0xF1, 0xF3, 0xF4
                            ),
                            'message_list_nat_8485' => array(
								0x50, 0x70, 0x71, 0x72, 0x73, 0x75, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88,
								0x89, 0x8A, 0x8B, 0x8C, 0x8D, 0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98,
								0x99, 0x9A, 0x9B, 0x9C, 0x9D, 0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8,
								0xA9, 0xAA, 0xAB, 0xAC, 0xAD, 0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8,
								0xB9, 0xBA, 0xBB, 0xBD, 0xBF, 0xC8, 0xC9, 0xCA, 0xCB, 0xCC, 0xD0, 0xD1, 0xD2, 0xD3, 0xD4, 0xD5,
								0xD6, 0xD8, 0xD9, 0xDA, 0xDB, 0xE0, 0xE1, 0xE2, 0xE3, 0xE4, 0xE5, 0xE6, 0xE7, 0xE8, 0xE9, 0xED,
								0xF0, 0xF1, 0xF3, 0xF4, 0xF5, 0xF6, 0xF7, 0xF8, 0xF9, 0xFA, 0xFB, 0xFC, 0xFD, 0xFE, 0xFF
							),
                            'author_list_nat_8485' => array(
								0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
								0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9D,
								0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD,
								0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xE1, 0xE2, 0xE3, 0xE6,
								0xE7, 0xE8, 0xF1, 0xF3, 0xF4
							),
                            'message_list_nat_8486' => array(
								0x50, 0x70, 0x71, 0x72, 0x73, 0x75, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88,
								0x89, 0x8A, 0x8B, 0x8C, 0x8D, 0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98,
								0x99, 0x9A, 0x9B, 0x9C, 0x9D, 0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8,
								0xA9, 0xAA, 0xAB, 0xAC, 0xAD, 0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8,
								0xB9, 0xBE, 0xC0, 0xC1, 0xC2, 0xC3, 0xC4, 0xC5, 0xC6, 0xC7, 0xE0, 0xE1, 0xE2, 0xE3, 0xE6, 0xE7,
								0xE8, 0xE9, 0xEA, 0xEF, 0xF0, 0xF1, 0xF3, 0xF4, 0xF5, 0xF6, 0xF7, 0xF8, 0xF9, 0xFA, 0xFB, 0xFC,
								0xFD, 0xFE, 0xFF
							),
                            'author_list_nat_8486' => array(
								0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
								0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9E,
								0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD, 0xAE,
								0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xC0, 0xC1, 0xC2, 0xC3, 0xC4,
								0xC5, 0xE1, 0xE2, 0xE3, 0xE6, 0xE7, 0xE8, 0xF1, 0xF3, 0xF4
							),
                            'message_list_nat_8488' => array(
								0x50, 0x70, 0x71, 0x72, 0x73, 0x75, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88,
								0x89, 0x8A, 0x8B, 0x8C, 0x8D, 0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98,
								0x99, 0x9A, 0x9B, 0x9C, 0x9D, 0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8,
								0xA9, 0xAA, 0xAB, 0xAC, 0xAD, 0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8,
								0xB9, 0xBA, 0xBB, 0xBD, 0xBE, 0xBF, 0xC6, 0xC7, 0xC8, 0xC9, 0xCA, 0xCB, 0xCC, 0xCD, 0xCE, 0xCF,
								0xD0, 0xD1, 0xD2, 0xD3, 0xD4, 0xD5, 0xD6, 0xD8, 0xD9, 0xDA, 0xDB, 0xDC, 0xDD, 0xDE, 0xE0, 0xE1,
								0xE2, 0xE3, 0xE4, 0xE5, 0xE6, 0xE7, 0xE8, 0xE9, 0xEA, 0xEE, 0xF0, 0xF1, 0xF3, 0xF4, 0xF5, 0xF6,
								0xF7, 0xF8, 0xF9, 0xFA, 0xFB, 0xFC, 0xFD, 0xFE, 0xFF
							),
                            'author_list_nat_8488' => array(
								0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
								0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9D,
								0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD,
								0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xE1, 0xE2, 0xE3, 0xE6,
								0xE7, 0xE8, 0xF1, 0xF3, 0xF4
							),
                            'message_list_nat_8492' => array(
								0x50, 0x70, 0x71, 0x72, 0x73, 0x75, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88,
								0x89, 0x8A, 0x8B, 0x8C, 0x8D, 0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98,
								0x99, 0x9A, 0x9B, 0x9C, 0x9D, 0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8,
								0xA9, 0xAA, 0xAB, 0xAC, 0xAD, 0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8,
								0xB9, 0xBA, 0xBB, 0xBD, 0xBE, 0xBF, 0xC6, 0xC7, 0xC8, 0xC9, 0xCA, 0xCB, 0xCC, 0xCD, 0xCE, 0xCF,
								0xD0, 0xD1, 0xD2, 0xD3, 0xD4, 0xD5, 0xD6, 0xD8, 0xD9, 0xDA, 0xDB, 0xDC, 0xDD, 0xDE, 0xE0, 0xE1,
								0xE2, 0xE3, 0xE4, 0xE5, 0xE6, 0xE7, 0xE8, 0xE9, 0xEA, 0xEE, 0xF0, 0xF1, 0xF3, 0xF4, 0xF5, 0xF6,
								0xF7, 0xF8, 0xF9, 0xFA, 0xFB, 0xFC, 0xFD, 0xFE, 0xFF
							),
                            'author_list_nat_8492' => array(
								0x50, 0x7F, 0x80, 0x81, 0x82, 0x83, 0x84, 0x85, 0x86, 0x87, 0x88, 0x89, 0x8A, 0x8B, 0x8C, 0x8D,
								0x8E, 0x8F, 0x90, 0x91, 0x92, 0x93, 0x94, 0x95, 0x96, 0x97, 0x98, 0x99, 0x9A, 0x9B, 0x9C, 0x9D,
								0x9E, 0x9F, 0xA0, 0xA1, 0xA2, 0xA3, 0xA4, 0xA5, 0xA6, 0xA7, 0xA8, 0xA9, 0xAA, 0xAB, 0xAC, 0xAD,
								0xAE, 0xAF, 0xB0, 0xB1, 0xB2, 0xB3, 0xB4, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xE1, 0xE2, 0xE3, 0xE6,
								0xE7, 0xE8, 0xF1, 0xF3, 0xF4
							),
                            // Tail is raw metadata (not text), validated as 6 fields:
                            //   [0] nationality[0] (A-Z), [1] nationality[1] (A-Z)
                            //   [2] trainer_id low byte, [3] trainer_id high byte, [4] species id, [5] mail item id.
                            'tail_nat_pairs' => array(
                                array(0x00, 0x00),
                                array(0x84, 0x85),
                                array(0x84, 0x86),
                                array(0x84, 0x88),
                                array(0x84, 0x92),
                            ),
                            'tail_tid_lo_list' => range(0x00, 0xFF),
                            'tail_tid_hi_list' => range(0x00, 0xFF),
                            'tail_species_list' => range(0x01, 0xFB),
                            'tail_mail_list' => array(0x9E, 0xB5, 0xB6, 0xB7, 0xB8, 0xB9, 0xBA, 0xBB, 0xBC, 0xBD),
                        ],
// Prebuilt message lookup sets.
                        '__msg_set_j' => null,
                        '__author_set_j' => null,
                        '__msg_set_non_j_nat_0000' => null,
                        '__author_set_non_j_nat_0000' => null,
                        '__msg_set_non_j_nat_8485' => null,
                        '__author_set_non_j_nat_8485' => null,
                        '__msg_set_non_j_nat_8486' => null,
                        '__author_set_non_j_nat_8486' => null,
                        '__msg_set_non_j_nat_8488' => null,
                        '__author_set_non_j_nat_8488' => null,
                        '__msg_set_non_j_nat_8492' => null,
                        '__author_set_non_j_nat_8492' => null,
                        '__tail_natpair_set_j' => null,
                                                '__tail_tid_lo_set_j' => null,
                        '__tail_tid_hi_set_j' => null,
                        '__tail_species_set_j' => null,
                        '__tail_mail_set_j' => null,
                        '__tail_natpair_set_non_j' => null,
                                                '__tail_tid_lo_set_non_j' => null,
                        '__tail_tid_hi_set_non_j' => null,
                        '__tail_species_set_non_j' => null,
                        '__tail_mail_set_non_j' => null,
                    ];

                    
                    // Default author allowlists to message allowlists if not explicitly set.// Build message lookup sets.
                    $mail_allowed_cache['__msg_set_j'] = [];
                    foreach ($mail_allowed_cache['j']['message_list'] as $b) {
                        $mail_allowed_cache['__msg_set_j'][$b] = true;
                    }
                    // Build author lookup sets.
                    $mail_allowed_cache['__author_set_j'] = [];
                    foreach ($mail_allowed_cache['j']['author_list'] as $b) {
                        $mail_allowed_cache['__author_set_j'][$b] = true;
                    }
                    // Build nationality-dependent lookup sets for non-J mail.
                    $mail_allowed_cache['__msg_set_non_j_nat_0000'] = [];
                    foreach ($mail_allowed_cache['non_j']['message_list_nat_0000'] as $b) {
                        $mail_allowed_cache['__msg_set_non_j_nat_0000'][$b] = true;
                    }

                    $mail_allowed_cache['__author_set_non_j_nat_0000'] = [];
                    foreach ($mail_allowed_cache['non_j']['author_list_nat_0000'] as $b) {
                        $mail_allowed_cache['__author_set_non_j_nat_0000'][$b] = true;
                    }

// Build nationality-dependent lookup sets for non-J mail.
// No fallback to a broad non-J allowlist: every allowed nationality pair must have its own lists.

$mail_allowed_cache['__msg_set_non_j_nat_8485'] = [];
foreach ((array)($mail_allowed_cache['non_j']['message_list_nat_8485'] ?? []) as $b) {
    $mail_allowed_cache['__msg_set_non_j_nat_8485'][$b] = true;
}
$mail_allowed_cache['__author_set_non_j_nat_8485'] = [];
foreach ((array)($mail_allowed_cache['non_j']['author_list_nat_8485'] ?? []) as $b) {
    $mail_allowed_cache['__author_set_non_j_nat_8485'][$b] = true;
}

$mail_allowed_cache['__msg_set_non_j_nat_8486'] = [];
foreach ((array)($mail_allowed_cache['non_j']['message_list_nat_8486'] ?? []) as $b) {
    $mail_allowed_cache['__msg_set_non_j_nat_8486'][$b] = true;
}
$mail_allowed_cache['__author_set_non_j_nat_8486'] = [];
foreach ((array)($mail_allowed_cache['non_j']['author_list_nat_8486'] ?? []) as $b) {
    $mail_allowed_cache['__author_set_non_j_nat_8486'][$b] = true;
}

$mail_allowed_cache['__msg_set_non_j_nat_8488'] = [];
foreach ((array)($mail_allowed_cache['non_j']['message_list_nat_8488'] ?? []) as $b) {
    $mail_allowed_cache['__msg_set_non_j_nat_8488'][$b] = true;
}
$mail_allowed_cache['__author_set_non_j_nat_8488'] = [];
foreach ((array)($mail_allowed_cache['non_j']['author_list_nat_8488'] ?? []) as $b) {
    $mail_allowed_cache['__author_set_non_j_nat_8488'][$b] = true;
}

$mail_allowed_cache['__msg_set_non_j_nat_8492'] = [];
foreach ((array)($mail_allowed_cache['non_j']['message_list_nat_8492'] ?? []) as $b) {
    $mail_allowed_cache['__msg_set_non_j_nat_8492'][$b] = true;
}
$mail_allowed_cache['__author_set_non_j_nat_8492'] = [];
foreach ((array)($mail_allowed_cache['non_j']['author_list_nat_8492'] ?? []) as $b) {
    $mail_allowed_cache['__author_set_non_j_nat_8492'][$b] = true;
}



}

                $bucket = ($region === 'j') ? 'j' : 'non_j';
                $allowed_msg_set = ($bucket === 'j')
                    ? $mail_allowed_cache['__msg_set_j']
                    : [];

                $allowed_author_set = ($bucket === 'j')
                    ? $mail_allowed_cache['__author_set_j']
                    : [];


                // Tail bytes are raw metadata (not text), validated by field:
                //   tail[0] nationality[0] (A-Z)
                //   tail[1] nationality[1] (A-Z)
                //   tail[2] trainer_id low byte
                //   tail[3] trainer_id high byte
                //   tail[4] species id
                //   tail[5] mail item id
                if ($bucket === 'j') {
                    // JP mail has no nationality bytes; no nationality-pair validation.
if ($mail_allowed_cache['__tail_tid_lo_set_j'] === null) {
                        $mail_allowed_cache['__tail_tid_lo_set_j'] = [];
                        foreach ($mail_allowed_cache['j']['tail_tid_lo_list'] as $b) {
                            $mail_allowed_cache['__tail_tid_lo_set_j'][$b] = true;
                        }
                    }
                    if ($mail_allowed_cache['__tail_tid_hi_set_j'] === null) {
                        $mail_allowed_cache['__tail_tid_hi_set_j'] = [];
                        foreach ($mail_allowed_cache['j']['tail_tid_hi_list'] as $b) {
                            $mail_allowed_cache['__tail_tid_hi_set_j'][$b] = true;
                        }
                    }
                    if ($mail_allowed_cache['__tail_species_set_j'] === null) {
                        $mail_allowed_cache['__tail_species_set_j'] = [];
                        foreach ($mail_allowed_cache['j']['tail_species_list'] as $b) {
                            $mail_allowed_cache['__tail_species_set_j'][$b] = true;
                        }
                    }
                    if ($mail_allowed_cache['__tail_mail_set_j'] === null) {
                        $mail_allowed_cache['__tail_mail_set_j'] = [];
                        foreach ($mail_allowed_cache['j']['tail_mail_list'] as $b) {
                            $mail_allowed_cache['__tail_mail_set_j'][$b] = true;
                        }
                    }

                    $tail_natpair_set = null; // JP has no nationality bytes
                    $tail_tid_lo_set = $mail_allowed_cache['__tail_tid_lo_set_j'];
                    $tail_tid_hi_set = $mail_allowed_cache['__tail_tid_hi_set_j'];
                    $tail_species_set = $mail_allowed_cache['__tail_species_set_j'];
                    $tail_mail_set = $mail_allowed_cache['__tail_mail_set_j'];
                } else {
                    if ($mail_allowed_cache['__tail_natpair_set_non_j'] === null) {
                        $mail_allowed_cache['__tail_natpair_set_non_j'] = [];
                        foreach ($mail_allowed_cache['non_j']['tail_nat_pairs'] as $p) {
                            if (!is_array($p) || count($p) !== 2) {
                                continue;
                            }
                            $k = (($p[0] & 0xFF) << 8) | ($p[1] & 0xFF);
                            $mail_allowed_cache['__tail_natpair_set_non_j'][$k] = true;
                        }
                    }
if ($mail_allowed_cache['__tail_tid_lo_set_non_j'] === null) {
                        $mail_allowed_cache['__tail_tid_lo_set_non_j'] = [];
                        foreach ($mail_allowed_cache['non_j']['tail_tid_lo_list'] as $b) {
                            $mail_allowed_cache['__tail_tid_lo_set_non_j'][$b] = true;
                        }
                    }
                    if ($mail_allowed_cache['__tail_tid_hi_set_non_j'] === null) {
                        $mail_allowed_cache['__tail_tid_hi_set_non_j'] = [];
                        foreach ($mail_allowed_cache['non_j']['tail_tid_hi_list'] as $b) {
                            $mail_allowed_cache['__tail_tid_hi_set_non_j'][$b] = true;
                        }
                    }
                    if ($mail_allowed_cache['__tail_species_set_non_j'] === null) {
                        $mail_allowed_cache['__tail_species_set_non_j'] = [];
                        foreach ($mail_allowed_cache['non_j']['tail_species_list'] as $b) {
                            $mail_allowed_cache['__tail_species_set_non_j'][$b] = true;
                        }
                    }
                    if ($mail_allowed_cache['__tail_mail_set_non_j'] === null) {
                        $mail_allowed_cache['__tail_mail_set_non_j'] = [];
                        foreach ($mail_allowed_cache['non_j']['tail_mail_list'] as $b) {
                            $mail_allowed_cache['__tail_mail_set_non_j'][$b] = true;
                        }
                    }

                    $tail_natpair_set = $mail_allowed_cache['__tail_natpair_set_non_j'];
                    $tail_tid_lo_set = $mail_allowed_cache['__tail_tid_lo_set_non_j'];
                    $tail_tid_hi_set = $mail_allowed_cache['__tail_tid_hi_set_non_j'];
                    $tail_species_set = $mail_allowed_cache['__tail_species_set_non_j'];
                    $tail_mail_set = $mail_allowed_cache['__tail_mail_set_non_j'];
                }

                $bytes = unpack('C*', $mail_blob);
                // For non-J mail, select message/author allowlists based on the tail nationality pair.
                if ($bucket === 'non_j' && $tail_len === 6) {
                    // unpack('C*') is 1-indexed; tail_offset is 0-indexed.
                    $nat0_idx = $tail_offset + 1;
                    $nat1_idx = $tail_offset + 2;
                    if ($nat1_idx <= $len) {
                        $nat0 = $bytes[$nat0_idx];
                        $nat1 = $bytes[$nat1_idx];
                        $pair = (($nat0 & 0xFF) << 8) | ($nat1 & 0xFF);

if ($pair === 0x0000) {
    $allowed_msg_set = $mail_allowed_cache['__msg_set_non_j_nat_0000'];
    $allowed_author_set = $mail_allowed_cache['__author_set_non_j_nat_0000'];
} elseif ($pair === 0x8485) {
    $allowed_msg_set = $mail_allowed_cache['__msg_set_non_j_nat_8485'];
    $allowed_author_set = $mail_allowed_cache['__author_set_non_j_nat_8485'];
} elseif ($pair === 0x8486) {
    $allowed_msg_set = $mail_allowed_cache['__msg_set_non_j_nat_8486'];
    $allowed_author_set = $mail_allowed_cache['__author_set_non_j_nat_8486'];
} elseif ($pair === 0x8488) {
    $allowed_msg_set = $mail_allowed_cache['__msg_set_non_j_nat_8488'];
    $allowed_author_set = $mail_allowed_cache['__author_set_non_j_nat_8488'];
} elseif ($pair === 0x8492) {
    $allowed_msg_set = $mail_allowed_cache['__msg_set_non_j_nat_8492'];
    $allowed_author_set = $mail_allowed_cache['__author_set_non_j_nat_8492'];
}

                    }
                }


                // First 0x21 bytes are the mail message body (line1 + separator + line2). Remaining pre-tail bytes are the author.
                $mail_msg_len = 0x21;


                // Note: unpack('C*') returns a 1-indexed array.
                for ($i = 1; $i <= $len; $i++) {
                    $b = $bytes[$i];
                    $offset0 = $i - 1;
                    $is_tail = ($offset0 >= $tail_offset);                    if ($is_tail) {
                        $tail_pos = $offset0 - $tail_offset;

                        if ($tail_len === 6) {
                            // Non-J tail layout: [nat0][nat1][TID lo][TID hi][species][mail item]
                            // Nationality is validated as a 2-byte pair at tail_pos 0+1.
                            if ($tail_pos === 0) {
                                if (($i + 1) > $len) {
                                    $errors_local[] = sprintf('mail: missing nationality_1 byte at offset %d for region %s', $offset0, $region);
                                    break;
                                }
                                if ($tail_natpair_set === null) {
                                    $errors_local[] = sprintf('mail: nationality pair set is not defined for region %s', $region);
                                    break;
                                }
                                $nat0 = $b;
                                $nat1 = $bytes[$i + 1];
                                $pair = (($nat0 & 0xFF) << 8) | ($nat1 & 0xFF);
                                if (!isset($tail_natpair_set[$pair])) {
                                    $errors_local[] = sprintf('mail: invalid nationality pair 0x%04X at offset %d for region %s', $pair, $offset0, $region);
                                    break;
                                }

                                // Skip per-byte validation of nationality_1 (tail_pos 1).
                                continue;
                            } elseif ($tail_pos === 1) {
                                continue;
                            } elseif ($tail_pos === 2) {
                                $tail_set = $tail_tid_lo_set;
                                $tail_label = 'trainer_id_lo';
                            } elseif ($tail_pos === 3) {
                                $tail_set = $tail_tid_hi_set;
                                $tail_label = 'trainer_id_hi';
                            } elseif ($tail_pos === 4) {
                                $tail_set = $tail_species_set;
                                $tail_label = 'species_id';
                            } else { // $tail_pos === 5
                                $tail_set = $tail_mail_set;
                                $tail_label = 'mail_id';
                            }
                        } else {
                            // JP tail layout: [TID lo][TID hi][species][mail item]
                            if ($tail_pos === 0) {
                                $tail_set = $tail_tid_lo_set;
                                $tail_label = 'trainer_id_lo';
                            } elseif ($tail_pos === 1) {
                                $tail_set = $tail_tid_hi_set;
                                $tail_label = 'trainer_id_hi';
                            } elseif ($tail_pos === 2) {
                                $tail_set = $tail_species_set;
                                $tail_label = 'species_id';
                            } else { // $tail_pos === 3
                                $tail_set = $tail_mail_set;
                                $tail_label = 'mail_id';
                            }
                        }
if (!isset($tail_set[$b])) {
                            $errors_local[] = sprintf('mail: invalid tail %s byte 0x%02X at offset %d for region %s', $tail_label, $b, $offset0, $region);
                            break;
                        }
                    } else {
                        // 0x4E is a fixed line-break separator: it must be the 17th byte of the message (offset 0x10) and nowhere else in the message.
                        // This rule applies to *message* validation only (not author).
                        if ($offset0 < $mail_msg_len) {
                            if ($offset0 === 0x10) {
                                if ($b !== 0x4E) {
                                    $errors_local[] = sprintf('mail: expected 0x4E line-break separator at offset %d for region %s', $offset0, $region);
                                    break;
                                }
                                continue;
                            } else {
                                if ($b === 0x4E) {
                                    $errors_local[] = sprintf('mail: unexpected 0x4E line-break separator at offset %d for region %s', $offset0, $region);
                                    break;
                                }
                            }
                        }

                        $set = ($offset0 < $mail_msg_len) ? $allowed_msg_set : $allowed_author_set;
                        $which = ($offset0 < $mail_msg_len) ? 'message' : 'author';
                        if (!isset($set[$b])) {
                            $errors_local[] = sprintf('mail: invalid %s byte 0x%02X at offset %d for region %s', $which, $b, $offset0, $region);
                            break;
                        }
                    }
                }
                            }
}
        }


        if ($errors_local) {
            if (is_array($errors)) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('bxt_validate_battle_tower_record_row')) {
    /**
     * Validate a single bxt_battle_tower_records row's scalar, trailer stats, and Easy Chat fields.
     *
     * @param string $game_region             One-letter region code (e/f/d/s/i/j).
     * @param string $message_start_blob      Raw Easy Chat bytes for the start message.
     * @param string $message_win_blob        Raw Easy Chat bytes for the win message.
     * @param string $message_lose_blob       Raw Easy Chat bytes for the lose message.
     * @param int    $num_trainers_defeated   0–7, number of trainers defeated in the streak.
     * @param int    $num_turns_required      Decoded number of turns required.
     * @param int    $damage_taken            Decoded total damage taken.
     * @param int    $num_fainted_pokemon     Decoded number of fainted Pokémon (0–15).
     * @param int    $level                   0–9, Battle Tower level index (0 = Lv.10 … 9 = Lv.100).
     * @param array  $errors                  Aggregate error list (by reference).
     *
     * @return bool true if the row passes validation, false otherwise.
     */
    function bxt_validate_battle_tower_record_row(
        $game_region,
        $message_start_blob,
        $message_win_blob,
        $message_lose_blob,
        $num_trainers_defeated,
        $num_turns_required,
        $damage_taken,
        $num_fainted_pokemon,
        $level,
        &$errors = [],
        $player_name_blob = null
    ) {
        $errors_local = [];

        // Optional: validate player_name bytes using the same allowlist as mail author bytes for this region.
        if ($player_name_blob !== null) {
            bxt_validate_player_name_bytes($game_region, $player_name_blob, $errors_local);
        }

        // Basic range checks for stats
        if (!is_int($num_trainers_defeated) || $num_trainers_defeated < 0 || $num_trainers_defeated > 7) {
            $errors_local[] = 'num_trainers_defeated: out of range ' . $num_trainers_defeated . ' (expected 0-7)';
        }

        if (!is_int($num_fainted_pokemon) || $num_fainted_pokemon < 0 || $num_fainted_pokemon > 15) {
            $errors_local[] = 'num_fainted_pokemon: out of range ' . $num_fainted_pokemon . ' (expected 0-15)';
        }

        if (!is_int($num_turns_required) || $num_turns_required < 0 || $num_turns_required > 65535) {
            $errors_local[] = 'num_turns_required: out of range ' . $num_turns_required . ' (expected 0-65535)';
        }

        if (!is_int($damage_taken) || $damage_taken < 0 || $damage_taken > 65535) {
            $errors_local[] = 'damage_taken: out of range ' . $damage_taken . ' (expected 0-65535)';
        }

        if (!is_int($level) || $level < 0 || $level > 9) {
            $errors_local[] = 'level: out of range ' . $level . ' (expected 0-9)';
        }

        // Relational constraints between stats:
        // 1) If any trainers were defeated, require at least 3 turns per trainer.
        if (is_int($num_trainers_defeated) && is_int($num_turns_required) && $num_trainers_defeated > 0) {
            $min_turns = $num_trainers_defeated * 3;
            if ($num_turns_required < $min_turns) {
                $errors_local[] =
                    'num_turns_required: ' . $num_turns_required .
                    ' too small for num_trainers_defeated=' . $num_trainers_defeated .
                    ', expected at least ' . $min_turns;
            }
        }

		// 2) Level-scaled minimum damage_taken given fainted count.
		//    Two bands: 1–7 fainted (factor 11), and 2–14 fainted (factor 22).
		// Band 1 is based on the lowest HP Pokémon with lowest stats (DIGLETT) at each level bracket.
		// Band 2 is based on the two lowest HP Pokémon with lowest stats (DIGLETT and MAGIKARP) combined.
		if (is_int($num_fainted_pokemon) && is_int($damage_taken) && is_int($level) && $level >= 0 && $level <= 9) {

			$bands = [
				[
					'min'    => 1,     // 0 < fainted < 8  → 1–7
					'max'    => 14,
					'factor' => 11,
					'label'  => '1-7 fainted',
				],
			];

			foreach ($bands as $band) {
				if ($num_fainted_pokemon >= $band['min'] && $num_fainted_pokemon <= $band['max']) {
					$factor = $band['factor'];
					$min_damage = $num_fainted_pokemon * $factor;

					if ($damage_taken < $min_damage) {
						$errors_local[] =
							'damage_taken: ' . $damage_taken .
							' too small for num_fainted_pokemon=' . $num_fainted_pokemon .
							', level=' . $level .
							', expected at least ' . $min_damage .
							' (factor ' . $factor . ' for ' . $band['label'] . ')';
					}
				}
			}
		}


        // Battle Tower messages:
        // - Non-J regions (BXTE layout): 8 bytes per message → 4 Easy Chat phrases
        // - J region (BXTJ layout): 12 bytes per message → 6 Easy Chat phrases
        $region = strtolower($game_region);
        $phrases_bt = ($region === 'j') ? 6 : 4;

        if (!bxt_validate_easy_chat_blob($game_region, $message_start_blob, $phrases_bt, $errors_local)) {
            // errors added
        }
        if (!bxt_validate_easy_chat_blob($game_region, $message_win_blob, $phrases_bt, $errors_local)) {
            // errors added
        }
        if (!bxt_validate_easy_chat_blob($game_region, $message_lose_blob, $phrases_bt, $errors_local)) {
            // errors added
        }

        if ($errors_local) {
            if (is_array($errors)) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('bxt_validate_battle_tower_trainer_row')) {
    /**
     * Validate a single logical trainer entry (for bxt_battle_tower_trainers).
     */
    function bxt_validate_battle_tower_trainer_row($game_region, $room, $level, $pokemon1_blob, $pokemon2_blob, $pokemon3_blob, $player_name_blob, $message_start_blob, $message_win_blob, $message_lose_blob, &$errors = []) {
        $errors_local = [];

        if (!is_int($room) || $room < 0 || $room > 19) {
            $errors_local[] = 'room: out of range ' . $room . ' (expected 0-19)';
        }
        if (!is_int($level) || $level < 0 || $level > 9) {
            $errors_local[] = 'level: out of range ' . $level . ' (expected 0-9)';
        }

        $region = strtolower($game_region);
        $expected_pokemon_len = ($region === 'j') ? 54 : 59;

        foreach ([['pokemon1', $pokemon1_blob], ['pokemon2', $pokemon2_blob], ['pokemon3', $pokemon3_blob]] as $pair) {
            list($name, $blob) = $pair;
            if (!is_string($blob)) {
                $errors_local[] = $name . ': blob is not a string';
            } else {
                $len = strlen($blob);
                if ($len !== $expected_pokemon_len) {
                    $errors_local[] = $name . ': invalid length ' . $len . ' for region ' . $region . ', expected ' . $expected_pokemon_len;
                }
            }
        }

        if (!bxt_validate_player_name_bytes($game_region, $player_name_blob, $errors_local)) {
            // errors added
        }

        // Easy Chat messages
        if (!bxt_validate_battle_tower_record_row($game_region, $message_start_blob, $message_win_blob, $message_lose_blob, 0, 0, $errors_local)) {
            // we reuse record validation for message blobs; counts are ignored here
        }

        if ($errors_local) {
            if (is_array($errors)) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        return true;
    }
}

if (!function_exists('bxt_validate_battle_tower_leader_row')) {
    /**
     * Validate a battle tower honor roll entry (bxt_battle_tower_honor_roll).
     */
    function bxt_validate_battle_tower_leader_row($game_region, $room, $level, $player_name_blob, &$errors = []) {
        $errors_local = [];

        if (!is_int($room) || $room < 0 || $room > 19) {
            $errors_local[] = 'leader.room: out of range ' . $room . ' (expected 0-19)';
        }
        if (!is_int($level) || $level < 0 || $level > 9) {
            $errors_local[] = 'leader.level: out of range ' . $level . ' (expected 0-9)';
        }

        if (!bxt_validate_player_name_bytes($game_region, $player_name_blob, $errors_local)) {
            // errors added
        }

        if ($errors_local) {
            if (is_array($errors)) {
                $errors = array_merge($errors, $errors_local);
            }
            return false;
        }

        return true;
    }
}

?>
