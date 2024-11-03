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

        public function getItemName($id) {
            // TODO: Handle localization for user
            return self::ItemNames[$id];
        }

        public function getSpeciesName($id) {
            // TODO: Handle localization for user
            return self::SpeciesNames[$id];
        }
        public function getMoveName($id) {
            // TODO: Handle localization for user
            return self::MoveNames[$id];
        }

        public function unpackMail($region, $data) {
            $author_len = $region == 'j' ? 5 : 8;
            $message = substr($data, 0, 0x21);
            $author = substr($data, 0x21, $author_len);
            $unpack_start = 0x21 + $author_len;
            if ($region != 'j') {
                $region = unpack("n", $data, 0x21 + $author_len)[1];
                $region = ['e', 'f', 'd', 'i', 's'][$region];
                $unpack_start += 2;
            }
            $mail = unpack("nauthor_id/Cspecies/Ctype", $data, $unpack_start);

            //TODO: Detect earlier
            if ($mail["type"] == 0) return null;

            $mail["region"] = $region;
            $mail["length"] = strlen($data);
            
            $mail["author"] = $this->getString($region, $author);
            $mail["message"] = $this->getString($region, $message);
                
            return $mail;
        }

        public function unpackPokemon($region, $data) {
            $pkm = unpack(
                "Cspecies/Citem/C4move/not_id/nexp1/Cexp2/nhpEV/nattackEV/ndefenseEV/nspeedEV/nspecialEV/niv/C4pp/Cfriendship/Cpokerus/ncaughtData/Clevel"
                ."/Cstatus/Cunused/ncurrentHP/nmaxHP/nattack/ndefense/nspeed/nspecialAttack/nspecialDefense", $data);
    
            $pkm["experience"] = ($pkm["exp1"] << 8) | $pkm["exp2"];
            $pkm["ev"] = [
                "hp" => $pkm["hpEV"],
                "attack" => $pkm["attackEV"],
                "defense" => $pkm["defenseEV"],
                "speed" => $pkm["speedEV"],
                "special" => $pkm["specialEV"],
            ];
            $pkm["iv"] = $iv = [
                "defense" => $pkm["iv"] & 0xF,
                "attack" => ($pkm["iv"] >> 4) & 0xF,
                "special" => ($pkm["iv"] >> 8) & 0xF,
                "speed" => ($pkm["iv"] >> 12) & 0xF,
            ];
            $pkm["iv"]["hp"] = (($iv["attack"] % 2) * 2^3) + (($iv["defense"] % 2) * 2^2) + (($iv["speed"] % 2) * 2^1) + (($iv["special"] % 2) * 2^0);
            $pkm["is_shiny"] = ($iv["defense"] == 10) && ($iv["speed"] == 10) && ($iv["special"] == 10) && (($iv["attack"] & 2) == 2);
            $pkm["met_location"] = ($pkm["caughtData"] >> 8) & 0x7f;
            $pkm["met_level"] = $pkm["caughtData"] & 0x1f;
            $pkm["met_time"] = ($pkm["caughtData"] >> 6) & 3;
            $pkm["ot_gender"] = $pkm["caughtData"] >> 15;

            $ot_len = $region == 'j' ? 5 : 7;
            $nick_len = $region == 'j' ? 5 : 10;
            $pkm["ot_name"] = $this->getString($region, substr($data, 0x30, $ot_len));
            $pkm["name"] = $this->getString($region, substr($data, 0x30 + $ot_len, $nick_len));

            $pkm["species"] = [ 
                "id" =>  $pkm["species"],
                "name" => $this->getSpeciesName($pkm["species"])
            ];

            $pkm["item"] = [
                "id" =>  $pkm["item"],
                "name" => $this->getItemName($pkm["item"])
            ];

            if ($pkm["pokerus"] == 0) {
                $pkm["pokerus"] = null;
            } else { 
                $pkm["pokerus"] = [ 
                    "strain" =>  $pkm["pokerus"] >> 4,
                    "days" => $pkm["pokerus"] & 0xF,
                    "cured" => $pkm["pokerus"] & 0xF == 0,
                ];
            }

            foreach(range(1,4) as $i) {
                $pkm["move".$i] = [
                    "id" => $pkm["move".$i],
                    "name" => $this->getMoveName($pkm["move".$i]),
                    "pp" => $pkm["pp".$i] & 0x3F,
                    "ppUps" => ($pkm["pp".$i] & 0xC) >> 6,
                ];
                unset($pkm["pp".$i]);
            }

            unset($pkm["exp1"], $pkm["exp2"], $pkm["caughtData"], $pkm["pokerus"], $pkm["unused"]);
            unset($pkm["hpEV"], $pkm["attackEV"], $pkm["defenseEV"], $pkm["speedEV"], $pkm["specialEV"]);
            
            return $pkm;
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
        private const SLB = "\n"; // Single line break
        private const DLB = "\n"; // Double line break; mainly needed for mail

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
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', self::DLB, self::SLB, // 40-4F
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
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', self::DLB, self::SLB, // 40-4F
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
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', self::DLB, self::SLB, // 40-4F
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
            'パ', 'ピ', 'プ', 'ポ', 'ぱ', 'ぴ', 'ぷ', 'ペ', 'ぽ', '', '', '', '', '', self::DLB, self::SLB, // 40-4F
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

        // TODO: Localize these
        private const SpeciesNames =
        [
            "None",
            "Bulbasaur", "Ivysaur", "Venusaur", "Charmander", "Charmeleon", "Charizard",
            "Squirtle", "Wartortle", "Blastoise", "Caterpie", "Metapod", "Butterfree",
            "Weedle", "Kakuna", "Beedrill", "Pidgey", "Pidgeotto", "Pidgeot", "Rattata", "Raticate",
            "Spearow", "Fearow", "Ekans", "Arbok", "Pikachu", "Raichu", "Sandshrew", "Sandslash",
            "Nidoran♀", "Nidorina", "Nidoqueen", "Nidoran♂", "Nidorino", "Nidoking",
            "Clefairy", "Clefable", "Vulpix", "Ninetales", "Jigglypuff", "Wigglytuff",
            "Zubat", "Golbat", "Oddish", "Gloom", "Vileplume", "Paras", "Parasect", "Venonat", "Venomoth",
            "Diglett", "Dugtrio", "Meowth", "Persian", "Psyduck", "Golduck", "Mankey", "Primeape",
            "Growlithe", "Arcanine", "Poliwag", "Poliwhirl", "Poliwrath", "Abra", "Kadabra", "Alakazam",
            "Machop", "Machoke", "Machamp", "Bellsprout", "Weepinbell", "Victreebel", "Tentacool", "Tentacruel",
            "Geodude", "Graveler", "Golem", "Ponyta", "Rapidash", "Slowpoke", "Slowbro",
            "Magnemite", "Magneton", "Farfetch'd", "Doduo", "Dodrio", "Seel", "Dewgong", "Grimer", "Muk",
            "Shellder", "Cloyster", "Gastly", "Haunter", "Gengar", "Onix", "Drowzee", "Hypno",
            "Krabby", "Kingler", "Voltorb", "Electrode", "Exeggcute", "Exeggutor", "Cubone", "Marowak",
            "Hitmonlee", "Hitmonchan", "Lickitung", "Koffing", "Weezing", "Rhyhorn", "Rhydon", "Chansey",
            "Tangela", "Kangaskhan", "Horsea", "Seadra", "Goldeen", "Seaking", "Staryu", "Starmie",
            "Mr. Mime", "Scyther", "Jynx", "Electabuzz", "Magmar", "Pinsir", "Tauros", "Magikarp", "Gyarados",
            "Lapras", "Ditto", "Eevee", "Vaporeon", "Jolteon", "Flareon", "Porygon", "Omanyte", "Omastar",
            "Kabuto", "Kabutops", "Aerodactyl", "Snorlax", "Articuno", "Zapdos", "Moltres",
            "Dratini", "Dragonair", "Dragonite", "Mewtwo", "Mew",

            "Chikorita", "Bayleef", "Meganium", "Cyndaquil", "Quilava", "Typhlosion",
            "Totodile", "Croconaw", "Feraligatr", "Sentret", "Furret", "Hoothoot", "Noctowl",
            "Ledyba", "Ledian", "Spinarak", "Ariados", "Crobat", "Chinchou", "Lanturn", "Pichu", "Cleffa",
            "Igglybuff", "Togepi", "Togetic", "Natu", "Xatu", "Mareep", "Flaaffy", "Ampharos", "Bellossom",
            "Marill", "Azumarill", "Sudowoodo", "Politoed", "Hoppip", "Skiploom", "Jumpluff", "Aipom",
            "Sunkern", "Sunflora", "Yanma", "Wooper", "Quagsire", "Espeon", "Umbreon", "Murkrow", "Slowking",
            "Misdreavus", "Unown", "Wobbuffet", "Girafarig", "Pineco", "Forretress", "Dunsparce", "Gligar",
            "Steelix", "Snubbull", "Granbull", "Qwilfish", "Scizor", "Shuckle", "Heracross", "Sneasel",
            "Teddiursa", "Ursaring", "Slugma", "Magcargo", "Swinub", "Piloswine", "Corsola", "Remoraid", "Octillery",
            "Delibird", "Mantine", "Skarmory", "Houndour", "Houndoom", "Kingdra", "Phanpy", "Donphan",
            "Porygon2", "Stantler", "Smeargle", "Tyrogue", "Hitmontop", "Smoochum", "Elekid", "Magby", "Miltank",
            "Blissey", "Raikou", "Entei", "Suicune", "Larvitar", "Pupitar", "Tyranitar", "Lugia", "Ho-Oh", "Celebi"
        ];

        // TODO: Localize these
        private const ItemNames =
        [
            "None",
            "Master Ball","Ultra Ball","BrightPowder","Great Ball","Poké Ball","Teru-sama","Bicycle","Moon Stone","Antidote",
            "Burn Heal","Ice Heal","Awakening","Parlyz Heal","Full Restore","Max Potion","Hyper Potion","Super Potion",
            "Potion","Escape Rope","Repel","Max Elixer","Fire Stone","Thunderstone","Water Stone","Teru-sama","HP Up",
            "Protein","Iron","Carbos","Lucky Punch","Calcium","Rare Candy","X Accuracy","Leaf Stone","Metal Powder","Nugget",
            "Poké Doll","Full Heal","Revive","Max Revive","Guard Spec.","Super Repel","Max Repel","Dire Hit","Teru-sama",
            "Fresh Water","Soda Pop","Lemonade","X Attack","Teru-sama","X Defend","X Speed","X Special","Coin Case","Itemfinder",
            "Teru-sama","Exp.Share","Old Rod","Good Rod","Silver Leaf","Super Rod","PP Up","Ether","Max Ether","Elixer",
            "Red Scale","SecretPotion","S.S. Ticket","Mystery Egg","Clear Bell*","Silver Wing","Moomoo Milk","Quick Claw",
            "PSNCureBerry","Gold Leaf","Soft Sand","Sharp Beak","PRZCureBerry","Burnt Berry","Ice Berry","Poison Barb",
            "King's Rock","Bitter Berry","Mint Berry","Red Apricorn","TinyMushroom","Big Mushroom","SilverPowder","Blu Apricorn",
            "Teru-sama","Amulet Coin","Ylw Apricorn","Grn Apricorn","Cleanse Tag","Mystic Water","TwistedSpoon","Wht Apricorn",
            "Blackbelt","Blk Apricorn","Teru-sama","Pnk Apricorn","BlackGlasses","SlowpokeTail","Pink Bow","Stick","Smoke Ball",
            "NeverMeltIce","Magnet","MiracleBerry","Pearl","Big Pearl","Everstone","Spell Tag","RageCandyBar","GS Ball",
            "Blue Card","Miracle Seed","Thick Club","Focus Band","Teru-sama","EnergyPowder","Energy Root","Heal Powder",
            "Revival Herb","Hard Stone","Lucky Egg","Card Key","Machine Part","Egg Ticket","Lost Item","Stardust","Star Piece",
            "Basement Key","Pass","Teru-sama","Teru-sama","Teru-sama","Charcoal","Berry Juice","Scope Lens","Teru-sama",
            "Teru-sama","Metal Coat","Dragon Fang","Teru-sama","Leftovers","Teru-sama","Teru-sama","Teru-sama","MysteryBerry",
            "Dragon Scale","Berserk Gene","Teru-sama","Teru-sama","Teru-sama","Sacred Ash","Heavy Ball","Flower Mail",
            "Level Ball","Lure Ball","Fast Ball","Teru-sama","Light Ball","Friend Ball","Moon Ball","Love Ball","Normal Box",
            "Gorgeous Box","Sun Stone","Polkadot Bow","Teru-sama","Up-Grade","Berry","Gold Berry","SquirtBottle","Teru-sama",
            "Park Ball","Rainbow Wing","Teru-sama","Brick Piece","Surf Mail","Litebluemail","Portraitmail","Lovely Mail",
            "Eon Mail","Morph Mail","Bluesky Mail","Music Mail","Mirage Mail","Teru-sama","TM01","TM02","TM03","TM04","TM04",
            "TM05","TM06","TM07","TM08","TM09","TM10","TM11","TM12","TM13","TM14","TM15","TM16","TM17","TM18","TM19","TM20",
            "TM21","TM22","TM23","TM24","TM25","TM26","TM27","TM28","TM28","TM29","TM30","TM31","TM32","TM33","TM34","TM35",
            "TM36","TM37","TM38","TM39","TM40","TM41","TM42","TM43","TM44","TM45","TM46","TM47","TM48","TM49","TM50","HM01",
            "HM02","HM03","HM04","HM05","HM06","HM07","HM08","HM09","HM10","HM11","HM12"
        ];

        // TODO: Localize these
        private const MoveNames =
        [
            "None",
            "Pound", "Karate Chop", "DoubleSlap", "Comet Punch", "Mega Punch", "Pay Day", "Fire Punch", "Ice Punch", "ThunderPunch",
            "Scratch", "ViceGrip", "Guillotine", "Razor Wind", "Swords Dance", "Cut", "Gust", "Wing Attack", "Whirlwind", "Fly",
            "Bind", "Slam", "Vine Whip", "Stomp", "Double Kick", "Mega Kick", "Jump Kick", "Rolling Kick", "Sand-Attack", "Headbutt",
            "Horn Attack", "Fury Attack", "Horn Drill", "Tackle", "Body Slam", "Wrap", "Take Down", "Thrash", "Double-Edge", 
            "Tail Whip", "Poison Sting", "Twineedle", "Pin Missile", "Leer", "Bite", "Growl", "Roar", "Sing", "Supersonic", "SonicBoom",
            "Disable", "Acid", "Ember", "Flamethrower", "Mist", "Water Gun", "Hydro Pump", "Surf", "Ice Beam", "Blizzard", "Psybeam",
            "BubbleBeam", "Aurora Beam", "Hyper Beam", "Peck", "Drill Peck", "Submission", "Low Kick", "Counter", "Seismic Toss", 
            "Strength", "Absorb", "Mega Drain", "Leech Seed", "Growth", "Razor Leaf", "SolarBeam", "PoisonPowder", "Stun Spore",
            "Sleep Powder", "Petal Dance", "String Shot", "Dragon Rage", "Fire Spin", "ThunderShock", "Thunderbolt", "Thunder Wave",
            "Thunder", "Rock Throw", "Earthquake", "Fissure", "Dig", "Toxic", "Confusion", "Psychic", "Hypnosis", "Meditate", "Agility",
            "Quick Attack", "Rage", "Teleport", "Night Shade", "Mimic", "Screech", "Double Team", "Recover", "Harden", "Minimize",
            "SmokeScreen", "Confuse Ray", "Withdraw", "Defense Curl", "Barrier", "Light Screen", "Haze", "Reflect", "Focus Energy",
            "Bide", "Metronome", "Mirror Move", "Selfdestruct", "Egg Bomb", "Lick", "Smog", "Sludge", "Bone Club", "Fire Blast",
            "Waterfall", "Clamp", "Swift", "Skull Bash", "Spike Cannon", "Constrict", "Amnesia", "Kinesis", "Softboiled", "Hi Jump Kick",
            "Glare", "Dream Eater", "Poison Gas", "Barrage", "Leech Life", "Lovely Kiss", "Sky Attack", "Transform", "Bubble", 
            "Dizzy Punch", "Spore", "Flash", "Psywave", "Splash", "Acid Armor", "Crabhammer", "Explosion", "Fury Swipes", "Bonemerang",
            "Rest", "Rock Slide", "Hyper Fang", "Sharpen", "Conversion", "Tri Attack", "Super Fang", "Slash", "Substitute", "Struggle",
            "Sketch", "Triple Kick", "Thief", "Spider Web", "Mind Reader", "Nightmare", "Flame Wheel", "Snore", "Curse", "Flail",
            "Conversion 2", "Aeroblast", "Cotton Spore", "Reversal", "Spite", "Powder Snow", "Protect", "Mach Punch", "Scary Face",
            "Faint Attack", "Sweet Kiss", "Belly Drum", "Sludge Bomb", "Mud-Slap", "Octazooka", "Spikes", "Zap Cannon", "Foresight",
            "Destiny Bond", "Perish Song", "Icy Wind", "Detect", "Bone Rush", "Lock-On", "Outrage", "Sandstorm", "Giga Drain", "Endure",
            "Charm", "Rollout", "False Swipe", "Swagger", "Milk Drink", "Spark", "Fury Cutter", "Steel Wing", "Mean Look", "Attract",
            "Sleep Talk","Heal Bell", "Return", "Present", "Frustration", "Safeguard", "Pain Split", "Sacred Fire", "Magnitude",
            "DynamicPunch", "Megahorn", "DragonBreath", "Baton Pass", "Encore", "Pursuit", "Rapid Spin", "Sweet Scent", "Iron Tail",
            "Metal Claw", "Vital Throw", "Morning Sun", "Synthesis", "Moonlight", "Hidden Power", "Cross Chop", "Twister", "Rain Dance",
            "Sunny Day", "Crunch", "Mirror Coat", "Psych Up", "ExtremeSpeed", "AncientPower", "Shadow Ball", "Future Sight", "Rock Smash",
            "Whirlpool", "Beat Up"
        ];
    }
?>