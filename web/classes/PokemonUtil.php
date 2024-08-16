<?php

	require_once dirname(__DIR__)."/vendor/autoload.php";
	require_once("DBUtil.php");
	
	class PokemonUtil {

		private static $instance;

		private final function  __construct() {
		}

		public static function getInstance() {
			if(!isset(self::$instance)) {
				self::$instance = new PokemonUtil();
			}
			return self::$instance;
		}

        public function getSubregion($region, $id) {
            $map = self::region_map[$region];
            return array_key_exists($id, $map) ? $map[$id] : "Unknown";
        }

        public function getString($region, $raw_text) {
            $char_table = [];
            switch ($region) {
                case "j":
                    $char_table = self::TableJP;
                    break;
                case "e":
                case "u":
                case "p": 
                    $char_table = self::TableEN;
                    break;
                case "f":
                case "d":
                   $char_table = self::TableFRE;
                   break;
                case "i":
                case "s":
                    $char_table = self::TableITA;
                    break;
            }
            $str = "";
            foreach(unpack("C*", $raw_text) as $b) {
                if ($b === self::terminator) break;
                if ($b === self::int_trade_code) {
                    $str .= self::int_trade_map[$region];
                    break;
                }
                $str .= $char_table[$b];
            }
            return $str;
        }

        private const terminator = 0x50;
        private const int_trade_code = 0x5d;

        private const NUL = '';
        private const TOT = '*';
        private const LPK = 'Pk'; // Pk
        private const LMN = 'Mn'; // Mn
        private const MNY = '¥'; // Yen
        private const LPO = 'Po'; // Po
        private const LKE = 'Ke'; // Ke
        private const LEA = '%'; // é for Box
        private const DOT = '․'; // . for MR.MIME (U+2024, not U+002E)
        private const SPF = '　'; // Full-width space (U+3000)
        private const SPH = ' '; // Half-width space
        private const LAP = '’'; // Apostrophe

        private static $int_trade_map = [
            'j' => 'トレーナー',
            'e' => 'Trainer',
            'u' => 'Trainer',
            'p' => 'Trainer',
            'f' => 'Dresseur',
            'i' => 'Allenatore',
            'd' => 'Trainer',
            's' => 'Entrenador'
        ];

        // The order of each prefecture/state/province MUST match what is displayed in game
        private const region_map = [
            'j' => [
                'Unknown',
                'JP-23', 'JP-02', 'JP-05', 'JP-17', 'JP-08', 'JP-03', 'JP-38', 'JP-44', 'JP-27', 'JP-33', 'JP-47', 'JP-37', 'JP-46', 'JP-14',
                'JP-21', 'JP-26', 'JP-43', 'JP-10', 'JP-39', 'JP-11', 'JP-41', 'JP-25', 'JP-22', 'JP-32', 'JP-12', 'JP-13', 'JP-36', 'JP-09',
                'JP-31', 'JP-16', 'JP-42', 'JP-20', 'JP-29', 'JP-15', 'JP-28', 'JP-34', 'JP-18', 'JP-40', 'JP-07', 'JP-01', 'JP-24', 'JP-04',
                'JP-45', 'JP-06', 'JP-35', 'JP-19'
            ],
            // Pulled from data/mobile/prefectures_list.asm
            'e' => [
                'Unknown',
                'US-AL', 'US-AK', 'US-AZ', 'US-AR', 'US-CA', 'US-CO', 'US-CT', 'US-DE', 'US-FL', 'US-GA', 'US-HI', 'US-ID', 'US-IL', 'US-IN',
                'US-IA', 'US-KS', 'US-KY', 'US-LA', 'US-ME', 'US-MD', 'US-MA', 'US-MI', 'US-MN', 'US-MS', 'US-MO', 'US-MT', 'US-NE', 'US-NV',
                'US-NH', 'US-NJ', 'US-NM', 'US-NY', 'US-NC', 'US-ND', 'US-OH', 'US-OK', 'US-OR', 'US-PA', 'US-RI', 'US-SC', 'US-SD', 'US-TN',
                'US-TX', 'US-UT', 'US-VT', 'US-VA', 'US-WA', 'US-WV', 'US-WI', 'US-WY', 'CA-AB', 'CA-BC', 'CA-MB', 'CA-NB', 'CA-NL', 'CA-NS',
                'CA-ON', 'CA-PE', 'CA-QC', 'CA-SK', 'CA-NT', 'CA-NU', 'CA-YT'
            ],
            'u' => [
                'Unknown',
                'AU-NSW', 'AU-QLD', 'AU-SA', 'AU-TAS', 'AU-VIC', 'AU-WA', 'AU-ACT', 'AU-NT', 'NZ-AUK', 'NZ-BOP', 'NZ-CAN', 'NZ-CIT', 'NZ-GIS',
                'NZ-WGN', 'NZ-HKB', 'NZ-MWT', 'NZ-MBH', 'NZ-NSN', 'NZ-NTL', 'NZ-OTA', 'NZ-STL', 'NZ-TKI', 'NZ-TAS', 'NZ-WKO', 'NZ-WTC'
            ],
            'p' => [
                'Unknown',
                'EU-AD', 'EU-AL', 'EU-AT', 'EU-BA', 'EU-BE', 'EU-BG', 'EU-BY', 'EU-CH', 'EU-CZ', 'EU-DE', 'EU-DK', 'EU-EE', 'EU-ES', 'EU-FI',
                'EU-FR', 'EU-GB', 'EU-GR', 'EU-HR', 'EU-HU', 'EU-IE', 'EU-IS', 'EU-IT', 'EU-LI', 'EU-LT', 'EU-LU', 'EU-LV', 'EU-MD', 'EU-MT',
                'EU-NL', 'EU-NO', 'EU-PL', 'EU-PT', 'EU-RO', 'EU-RS', 'EU-RU', 'EU-SE', 'EU-SI', 'EU-SK', 'EU-SM', 'EU-UA'
            ],
            's' => [
                'Unknown',
                'ES-C', 'ES-VI', 'ES-AB', 'ES-A', 'ES-AL', 'ES-O', 'ES-AV', 'ES-BA', 'ES-B', 'ES-BI', 'ES-BU', 'ES-CC', 'ES-CA', 'ES-S',
                'ES-CS', 'ES-CR', 'ES-CO', 'ES-CU', 'ES-SS', 'ES-GI', 'ES-GR', 'ES-GU', 'ES-H', 'ES-HU', 'ES-PM', 'ES-J', 'ES-LO', 'ES-GC',
                'ES-LE', 'ES-L', 'ES-LU', 'ES-M ', 'ES-MA', 'ES-MU', 'ES-NA', 'ES-OR', 'ES-P', 'ES-PO', 'ES-SA', 'ES-TF', 'ES-SG', 'ES-SE',
                'ES-SO', 'ES-T', 'ES-TE', 'ES-TO', 'ES-V', 'ES-VA', 'ES-ZA', 'ES-Z', 'AD-07', 'AD-02', 'AD-03', 'AD-08', 'AD-04', 'AD-05', 'AD-06'
            ],
            'i' => [
                'Unknown',
                'IT-AG', 'IT-AL', 'IT-AN', 'IT-AO', 'IT-AR', 'IT-AP', 'IT-AT', 'IT-AV', 'IT-BA', 'IT-BL', 'IT-BN', 'IT-BG', 'IT-BI', 'IT-BO',
                'IT-BS', 'IT-BR', 'IT-CA', 'IT-CL', 'IT-CB', 'IT-CE', 'IT-CT', 'IT-CZ', 'IT-CH', 'IT-CO', 'IT-CS', 'IT-CR', 'IT-KR', 'IT-CN',
                'IT-EN', 'IT-FE', 'IT-FI', 'IT-FG', 'IT-FC', 'IT-FR', 'IT-GE', 'IT-GO', 'IT-GR', 'IT-IM', 'IT-IS', 'IT-AQ', 'IT-SP', 'IT-LT',
                'IT-LE', 'IT-LC', 'IT-LI', 'IT-LO', 'IT-LU', 'IT-MC', 'IT-MN', 'IT-MS', 'IT-MT', 'IT-ME', 'IT-MI', 'IT-MO', 'IT-NA', 'IT-NO',
                'IT-NU', 'IT-OR', 'IT-PD', 'IT-PA', 'IT-PR', 'IT-PV', 'IT-PG', 'IT-PU', 'IT-PE', 'IT-PC', 'IT-PI', 'IT-PT', 'IT-PN', 'IT-PZ',
                'IT-PO', 'IT-RG', 'IT-RA', 'IT-RC', 'IT-RE', 'IT-RI', 'IT-RN', 'IT-RM', 'IT-RO', 'IT-SA', 'IT-SS', 'IT-SV', 'IT-SI', 'IT-SO',
                'IT-BZ', 'IT-SR', 'IT-TA', 'IT-TE', 'IT-TR', 'IT-TP', 'IT-TN', 'IT-TV', 'IT-TS', 'IT-TO', 'IT-UD', 'IT-VA', 'IT-VE', 'IT-VB',
                'IT-VC', 'IT-VR', 'IT-VV', 'IT-VI', 'IT-VT', 'CH-ZH', 'CH-BE', 'CH-LU', 'CH-UR', 'CH-SZ', 'CH-OW', 'CH-NW', 'CH-GL', 'CH-ZG',
                'CH-FR', 'CH-SO', 'CH-BS', 'CH-BL', 'CH-SH', 'CH-AR', 'CH-AI', 'CH-SG', 'CH-GR', 'CH-AG', 'CH-TG', 'CH-TI', 'CH-VD', 'CH-VS',
                'CH-NE', 'CH-GE', 'CH-JU', 'SM-01', 'SM-06', 'SM-02', 'SM-07', 'SM-03', 'SM-04', 'SM-05', 'SM-08', 'SM-09'
            ],
            'f' => [
                'Unknown',
                'FR-A', 'FR-B', 'FR-C', 'FR-D', 'FR-E', 'FR-F', 'FR-G', 'FR-H', 'FR-I', 'FR-J', 'FR-K', 'FR-L', 'FR-M', 'FR-N', 'FR-O', 'FR-P',
                'FR-Q', 'FR-R', 'FR-S', 'FR-T', 'FR-U', 'FR-V', 'BE-VAN', 'BE-WBR', 'BE-WHT', 'BE-WLG', 'BE-VLI', 'BE-WLX', 'BE-WNA', 'BE-VOV',
                'BE-VBR', 'BE-VWV', 'CH-ZH', 'CH-BE', 'CH-LU', 'CH-UR', 'CH-SZ', 'CH-OW', 'CH-NW', 'CH-GL', 'CH-ZG', 'CH-FR', 'CH-SO', 'CH-BS',
                'CH-BL', 'CH-SH', 'CH-AR', 'CH-AI', 'CH-SG', 'CH-GR', 'CH-AG', 'CH-TG', 'CH-TI', 'CH-VD', 'CH-VS', 'CH-NE', 'CH-GE', 'CH-JU',
                'LU-CA', 'LU-CL', 'LU-DI', 'LU-EC', 'LU-ES', 'LU-GR', 'LU-LU', 'LU-ME', 'LU-RD', 'LU-RM', 'LU-VD', 'LU-WI'
            ],
            'd' => [
                'Unknown',
                'DE-BW', 'DE-BY', 'DE-BE', 'DE-BB', 'DE-HB', 'DE-HH', 'DE-HE', 'DE-MV', 'DE-NI', 'DE-NW', 'DE-RP', 'DE-SL', 'DE-SN', 'DE-ST',
                'DE-SH', 'DE-TH', 'AT-1', 'AT-2', 'AT-3', 'AT-4', 'AT-5', 'AT-6', 'AT-7', 'AT-8', 'AT-9', 'CH-ZH', 'CH-BE', 'CH-LU', 'CH-UR',
                'CH-SZ', 'CH-OW', 'CH-NW', 'CH-GL', 'CH-ZG', 'CH-FR', 'CH-SO', 'CH-BS', 'CH-BL', 'CH-SH', 'CH-AR', 'CH-AI', 'CH-SG', 'CH-GR',
                'CH-AG', 'CH-TG', 'CH-TI', 'CH-VD', 'CH-VS', 'CH-NE', 'CH-GE', 'CH-JU', 'LI-01', 'LI-02', 'LI-03', 'LI-04', 'LI-05', 'LI-06',
                'LI-07', 'LI-08', 'LI-09', 'LI-10', 'LI-11', 'LU-CA', 'LU-CL', 'LU-DI', 'LU-EC', 'LU-ES', 'LU-GR', 'LU-LU', 'LU-ME', 'LU-RD',
                'LU-RM', 'LU-VD', 'LU-WI'
            ]
        ];

        // Tables ported from PKHeX
        private const TableEN = 
        [
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 00-0F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 10-1F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 20-2F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 30-3F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 40-4F
            '', '', '', '', '', '', '', '', '', '', '', '', '', self::TOT, '', '', // 50-5F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 60-6F
            self::LPO, self::LKE, '“', '”', '', '…', '', '', '', '┌', '─', '┐', '│', '└', '┘', self::SPH, // 70-7F
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 80-8F
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '(', ')', ':', ';', '[', ']', // 90-9F
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', // A0-AF
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'à', 'è', 'é', 'ù', 'À', 'Á', // B0-BF
            'Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', 'È', 'É', 'Ì', 'Í', 'Ñ', 'Ò', 'Ó', 'Ù', 'Ú', 'á', // C0-CF
            '’d', '’l', '’m', '’r', '’s', '’t', '’v', '', '', '', '', '', '', '', '', '←', // D0-DF
            self::LAP, self::LPK, self::LMN, '-', '+', '', '?', '!', self::DOT, '&', self::LEA, '→', '▷', '▶', '▼', '♂', // E0-EF
            self::MNY, '×', '.', '/', ',', '♀', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', // F0-FF
        ];

        private const TableFRE = // Also German
        [
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 00-0F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 10-1F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 20-2F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 30-3F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 40-4F
            '', '', '', '', '', '', '', '', '', '', '', '', '', self::TOT, '', '', // 50-5F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 60-6F
            self::LPO, self::LKE, '“', '”', '', '…', '', '', '', '┌', '─', '┐', '│', '└', '┘', self::SPH, // 70-7F
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 80-8F
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '(', ')', ':', ';', '[', ']', // 90-9F
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', // A0-AF
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'à', 'è', 'é', 'ù', 'ß', 'ç', // B0-BF
            'Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', 'È', 'É', 'Ì', 'Í', 'Ñ', 'Ò', 'Ó', 'Ù', 'Ú', 'á', // C0-CF
            '', '', '', '', 'c’', 'd’', 'j’', 'l’', 'm’', 'n’', 'p’', 's’', '’s', 't’', 'u’', 'y’', // D0-DF
            self::LAP, self::LPK, self::LMN, '-', '+', '', '?', '!', self::DOT, '&', self::LEA, '→', '▷', '▶', '▼', '♂', // E0-EF
            self::MNY, '×', '.', '/', ',', '♀', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', // F0-FF
        ];

        private const TableITA = // Also Spanish
        [
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 00-0F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 10-1F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 20-2F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 30-3F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 40-4F
            '', '', '', '', '', '', '', '', '', '', '', '', '', self::TOT, '', '', // 50-5F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 60-6F
            self::LPO, self::LKE, '“', '”', '', '…', '', '', '', '┌', '─', '┐', '│', '└', '┘', self::SPH, // 70-7F
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', // 80-8F
            'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '(', ')', ':', ';', '[', ']', // 90-9F
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', // A0-AF
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'à', 'è', 'é', 'ù', 'À', 'Á', // B0-BF
            'Ä', 'Ö', 'Ü', 'ä', 'ö', 'ü', 'È', 'É', 'Ì', 'Í', 'Ñ', 'Ò', 'Ó', 'Ù', 'Ú', 'á', // C0-CF
            'ì', 'í', 'ñ', 'ò', 'ó', 'ú', '', '', '’d', '’l', '’m', '’r', '’s', '’t', '’v', '', // D0-DF
            self::LAP, self::LPK, self::LMN, '-', '¿', '¡', '?', '!', self::DOT, '&', self::LEA, '→', '▷', '▶', '▼', '♂', // E0-EF
            self::MNY, '×', '.', '/', ',', '♀', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', // F0-FF
        ];

        private const TableJP =
        [
            '', '', '', '', '', 'ガ', 'ギ', 'グ', 'ゲ', 'ゴ', 'ザ', 'ジ', 'ズ', 'ゼ', 'ゾ', 'ダ', // 00-0F
            'ヂ', 'ヅ', 'デ', 'ド', '', '', '', '', '', 'バ', 'ビ', 'ブ', 'ボ', '',  '', '', // 10-1F
            '', '', '', '', '', '', 'が', 'ぎ', 'ぐ', 'げ', 'ご', 'ざ', 'じ', 'ず', 'ぜ', 'ぞ', // 20-2F
            'だ', 'ぢ', 'づ', 'で', 'ど', '', '', '', '',  '', 'ば', 'び', 'ぶ', 'ベ', 'ぼ', '', // 30-3F
            'パ', 'ピ', 'プ', 'ポ', 'ぱ', 'ぴ', 'ぷ', 'ペ', 'ぽ', '', '', '', '', '', '', '', // 40-4F
            '', '', '', '', '', '', '', '', '', '', '', '', '', self::TOT, '', '', // 50-5F
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // 60-6F
            '「', '」', '『', '』', '・', '⋯', '', '', '', '', '', '', '', '', '', self::SPF, // 70-7F
            'ア', 'イ', 'ウ', 'エ', 'オ', 'カ', 'キ', 'ク', 'ケ', 'コ', 'サ', 'シ', 'ス', 'セ', 'ソ', 'タ', // 80-8F
            'チ', 'ツ', 'テ', 'ト', 'ナ', 'ニ', 'ヌ', 'ネ', 'ノ', 'ハ', 'ヒ', 'フ', 'ホ', 'マ', 'ミ', 'ム', // 90-9F
            'メ', 'モ', 'ヤ', 'ユ', 'ヨ', 'ラ', 'ル', 'レ', 'ロ', 'ワ', 'ヲ', 'ン', 'ッ', 'ャ', 'ュ', 'ョ', // A0-AF
            'ィ', 'あ', 'い', 'う', 'え', 'お', 'か', 'き', 'く', 'け', 'こ', 'さ', 'し', 'す', 'せ', 'そ', // B0-BF
            'た', 'ち', 'つ', 'て', 'と', 'な', 'に', 'ぬ', 'ね', 'の', 'は', 'ひ', 'ふ', 'ヘ', 'ほ', 'ま', // C0-CF
            'み', 'む', 'め', 'も', 'や', 'ゆ', 'よ', 'ら', 'リ', 'る', 'れ', 'ろ', 'わ', 'を', 'ん', 'っ', // D0-DF
            'ゃ', 'ゅ', 'ょ', 'ー', 'ﾟ', 'ﾞ', '？', '！', '。', 'ァ', 'ゥ', 'ェ', '', '', '', '♂', // E0-EF
            self::MNY, '', '．', '／', 'ォ', '♀', '０', '１', '２', '３', '４', '５', '６', '７', '８', '９', // F0-FF
        ];
    }
?>