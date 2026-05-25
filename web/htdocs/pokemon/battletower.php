<?php
    require_once("../../classes/TemplateUtil.php");
    require_once("../../classes/DBUtil.php");
    require_once("../../classes/SessionUtil.php");
    require_once("../../classes/PokemonUtil.php");
    require_once("../../scripts/bxt_decode_helpers.php");
    session_start();

    function bxt_battle_tower_parse_level($raw_value) {
        $level = intval($raw_value);
        if ($level < 10 || $level > 100 || ($level % 10) !== 0) {
            return 10;
        }
        return $level;
    }

    function bxt_battle_tower_parse_room($raw_value) {
        $room = intval($raw_value);
        if ($room < 1 || $room > 20) {
            return 1;
        }
        return $room;
    }

    function bxt_battle_tower_normalize_class_key($class_name) {
        $normalized = strtolower(trim((string)$class_name));
        $normalized = str_replace(["’", "'", "-", ".", " "], "", $normalized);
        return preg_replace('/[^a-z0-9]/', '', $normalized);
    }

    function bxt_battle_tower_sprite_exists($sprite_name) {
        static $cache = [];
        $key = strtolower((string)$sprite_name);
        if (isset($cache[$key])) {
            return $cache[$key];
        }

        $path = dirname(__DIR__) . "/images/crystal/trainer_sprites/" . $key . ".png";
        $cache[$key] = file_exists($path);
        return $cache[$key];
    }

    function bxt_battle_tower_trainer_sprite_name($trainer_class_id, $trainer_class_decode = "") {
        static $sprite_map = [
            1 => "falkner",
            2 => "whitney",
            3 => "bugsy",
            4 => "morty",
            5 => "pryce",
            6 => "jasmine",
            7 => "chuck",
            8 => "clair",
            9 => "rival1",
            10 => "oak",
            11 => "will",
            12 => "cal",
            13 => "bruno",
            14 => "karen",
            15 => "koga",
            16 => "champion",
            17 => "brock",
            18 => "misty",
            19 => "lt_surge",
            20 => "scientist",
            21 => "erika",
            22 => "youngster",
            23 => "schoolboy",
            24 => "bird_keeper",
            25 => "lass",
            26 => "janine",
            27 => "cooltrainer_m",
            28 => "cooltrainer_f",
            29 => "beauty",
            30 => "pokemaniac",
            31 => "grunt_m",
            32 => "gentleman",
            33 => "skier",
            34 => "teacher",
            35 => "sabrina",
            36 => "bug_catcher",
            37 => "fisher",
            38 => "swimmer_m",
            39 => "swimmer_f",
            40 => "sailor",
            41 => "super_nerd",
            42 => "rival2",
            43 => "guitarist",
            44 => "hiker",
            45 => "biker",
            46 => "blaine",
            47 => "burglar",
            48 => "firebreather",
            49 => "juggler",
            50 => "blackbelt_t",
            51 => "executive_m",
            52 => "psychic_t",
            53 => "picnicker",
            54 => "camper",
            55 => "executive_f",
            56 => "sage",
            57 => "medium",
            58 => "boarder",
            59 => "pokefan_m",
            60 => "kimono_girl",
            61 => "twins",
            62 => "pokefan_f",
            63 => "red",
            64 => "blue",
            65 => "officer",
            66 => "grunt_f",
            67 => "mysticalman",
        ];

        static $class_name_map = [
            "falkner" => "falkner",
            "whitney" => "whitney",
            "bugsy" => "bugsy",
            "morty" => "morty",
            "pryce" => "pryce",
            "jasmine" => "jasmine",
            "chuck" => "chuck",
            "clair" => "clair",
            "rival1" => "rival1",
            "pokemonprof" => "oak",
            "will" => "will",
            "cal" => "cal",
            "bruno" => "bruno",
            "karen" => "karen",
            "koga" => "koga",
            "champion" => "champion",
            "brock" => "brock",
            "misty" => "misty",
            "ltsurge" => "lt_surge",
            "scientist" => "scientist",
            "erika" => "erika",
            "youngster" => "youngster",
            "schoolboy" => "schoolboy",
            "birdkeeper" => "bird_keeper",
            "lass" => "lass",
            "janine" => "janine",
            "cooltrainerm" => "cooltrainer_m",
            "cooltrainerf" => "cooltrainer_f",
            "beauty" => "beauty",
            "pokemaniac" => "pokemaniac",
            "gruntm" => "grunt_m",
            "gentleman" => "gentleman",
            "skier" => "skier",
            "teacher" => "teacher",
            "sabrina" => "sabrina",
            "bugcatcher" => "bug_catcher",
            "fisher" => "fisher",
            "swimmerm" => "swimmer_m",
            "swimmerf" => "swimmer_f",
            "sailor" => "sailor",
            "supernerd" => "super_nerd",
            "rival2" => "rival2",
            "guitarist" => "guitarist",
            "hiker" => "hiker",
            "biker" => "biker",
            "blaine" => "blaine",
            "burglar" => "burglar",
            "firebreather" => "firebreather",
            "juggler" => "juggler",
            "blackbeltt" => "blackbelt_t",
            "executivem" => "executive_m",
            "psychict" => "psychic_t",
            "picnicker" => "picnicker",
            "camper" => "camper",
            "executivef" => "executive_f",
            "sage" => "sage",
            "medium" => "medium",
            "boarder" => "boarder",
            "pokefanm" => "pokefan_m",
            "kimonogirl" => "kimono_girl",
            "twins" => "twins",
            "pokefanf" => "pokefan_f",
            "red" => "red",
            "blue" => "blue",
            "officer" => "officer",
            "gruntf" => "grunt_f",
            "mysticalman" => "mysticalman",
        ];

        $class_id = intval($trainer_class_id);
        if (isset($sprite_map[$class_id]) && bxt_battle_tower_sprite_exists($sprite_map[$class_id])) {
            return $sprite_map[$class_id];
        }

        $class_key = bxt_battle_tower_normalize_class_key($trainer_class_decode);
        if ($class_key !== "" && isset($class_name_map[$class_key]) && bxt_battle_tower_sprite_exists($class_name_map[$class_key])) {
            return $class_name_map[$class_key];
        }

        if (bxt_battle_tower_sprite_exists("youngster")) {
            return "youngster";
        }

        return "cooltrainer_m";
    }

    function bxt_battle_tower_detect_storage_indexing($db) {
        $is_room_zero_based = true;
        $is_level_zero_based = true;

        $stmt = $db->prepare(
            "select " .
                "MIN(room) as min_room, " .
                "MAX(room) as max_room, " .
                "MIN(level) as min_level, " .
                "MAX(level) as max_level " .
            "from bxt_battle_tower_honor_roll"
        );

        if (!$stmt) {
            return [$is_room_zero_based, $is_level_zero_based];
        }

        $stmt->execute();
        $result = DBUtil::fancy_get_result($stmt);
        if (!is_array($result) || count($result) === 0) {
            return [$is_room_zero_based, $is_level_zero_based];
        }

        $row = $result[0];
        $min_room = isset($row["min_room"]) ? intval($row["min_room"]) : null;
        $max_room = isset($row["max_room"]) ? intval($row["max_room"]) : null;
        $min_level = isset($row["min_level"]) ? intval($row["min_level"]) : null;
        $max_level = isset($row["max_level"]) ? intval($row["max_level"]) : null;

        if ($min_room !== null && $max_room !== null) {
            if ($min_room >= 1 && $max_room <= 20) {
                $is_room_zero_based = false;
            } else if ($min_room >= 0 && $max_room <= 19) {
                $is_room_zero_based = true;
            }
        }

        if ($min_level !== null && $max_level !== null) {
            if ($min_level >= 10 && $max_level <= 100) {
                $is_level_zero_based = false;
            } else if ($min_level >= 0 && $max_level <= 9) {
                $is_level_zero_based = true;
            }
        }

        return [$is_room_zero_based, $is_level_zero_based];
    }

    function bxt_battle_tower_fallback_pokemon_name($pokemon_decode) {
        $text = trim((string)$pokemon_decode);
        if ($text === "") {
            return "";
        }

        $text = preg_replace('/\s+/u', ' ', $text);

        // bxt_summarize_pk2_blob() primary field is:
        // - "SPECIES" (no nickname), or
        // - "NICKNAME (SPECIES)" (nickname differs from species)
        // followed by " | ..." metadata segments.
        $primary_segment = trim((string)explode("|", $text, 2)[0]);
        if ($primary_segment === "") {
            return "";
        }

        if (preg_match('/\(([^()]+)\)\s*$/u', $primary_segment, $matches)) {
            return trim((string)$matches[1]);
        }

        return trim($primary_segment);
    }

    function bxt_battle_tower_easy_chat_table_id($game_region) {
        $region = strtolower((string)$game_region);
        if (function_exists('bxt_effective_region_for_display')) {
            $region = strtolower((string)bxt_effective_region_for_display($region));
        }

        switch ($region) {
            case 'f':
                return 'btxf_easy_chat';
            case 'd':
                return 'btxd_easy_chat';
            case 's':
                return 'btxs_easy_chat';
            case 'i':
                return 'btxi_easy_chat';
            case 'j':
                return 'btxj_easy_chat';
            case 'e':
            case 'p':
            case 'u':
            default:
                return 'btxe_btxp_btxu_easy_chat';
        }
    }

    function bxt_battle_tower_decode_message_tokens($game_region, $raw_message_binary) {
        if (!is_string($raw_message_binary) || $raw_message_binary === "") {
            return [];
        }

        if (!function_exists('bxt_load_encoding_json')) {
            return [];
        }

        $cfg = bxt_load_encoding_json();
        if (!is_array($cfg)) {
            return [];
        }

        $table_id = bxt_battle_tower_easy_chat_table_id($game_region);
        if (!isset($cfg[$table_id]) || !is_array($cfg[$table_id])) {
            return [];
        }

        $table = $cfg[$table_id];
        $hex = strtoupper(bin2hex($raw_message_binary));
        $tokens = [];

        for ($i = 0; $i + 3 < strlen($hex); $i += 4) {
            $code = substr($hex, $i, 4);
            if (isset($table[$code])) {
                $tokens[] = trim((string)$table[$code]);
            } elseif ($code !== '0000') {
                $tokens[] = $code;
            }
        }

        $tokens = array_values(array_filter($tokens, function ($token) {
            return $token !== "";
        }));

        return $tokens;
    }

    function bxt_battle_tower_token_has_word($token) {
        return preg_match('/[\p{L}\p{N}]/u', (string)$token) === 1;
    }

    function bxt_battle_tower_tokens_to_text($tokens) {
        $out = "";
        foreach ($tokens as $token) {
            $token = trim((string)$token);
            if ($token === "") {
                continue;
            }

            if ($out === "") {
                $out = $token;
                continue;
            }

            if (bxt_battle_tower_token_has_word($token)) {
                $out .= " " . $token;
            } else {
                $out .= $token;
            }
        }
        return $out;
    }

    function bxt_battle_tower_message_wrap_word_limit($game_region) {
        return (strtolower((string)$game_region) === "j") ? 3 : 2;
    }

    function bxt_battle_tower_format_message_tokens($tokens, $game_region) {
        if (!is_array($tokens) || count($tokens) === 0) {
            return "";
        }

        $word_limit = bxt_battle_tower_message_wrap_word_limit($game_region);
        $lines = [];
        $current_line_tokens = [];
        $current_line_words = 0;

        foreach ($tokens as $token) {
            $current_line_tokens[] = $token;
            if (bxt_battle_tower_token_has_word($token)) {
                $current_line_words++;
            }

            if ($current_line_words >= $word_limit) {
                $line_text = bxt_battle_tower_tokens_to_text($current_line_tokens);
                if ($line_text !== "") {
                    $lines[] = $line_text;
                }
                $current_line_tokens = [];
                $current_line_words = 0;
            }
        }

        if (count($current_line_tokens) > 0) {
            $line_text = bxt_battle_tower_tokens_to_text($current_line_tokens);
            if ($line_text !== "") {
                $lines[] = $line_text;
            }
        }

        return implode("\n\n", $lines);
    }

    function bxt_battle_tower_decode_trainer_class_name($game_region, $class_id, $fallback = "") {
        $class_name = "";
        if (function_exists('bxt_decode_trainer_class_for_region')) {
            $class_name = trim((string)bxt_decode_trainer_class_for_region($game_region, $class_id));
        }
        if ($class_name === "") {
            $class_name = trim((string)$fallback);
        }
        if ($class_name === "") {
            $class_name = "TRAINER";
        }
        return $class_name;
    }

    function bxt_battle_tower_decode_species_name($pkm_util, $game_region, $pokemon_blob, $fallback_decode = "") {
        if (is_string($pokemon_blob) && strlen($pokemon_blob) >= 1) {
            $species_id = intval(unpack("Cspecies", substr($pokemon_blob, 0, 1))["species"] ?? 0);
            if ($species_id > 0) {
                $name = "";
                if (function_exists('bxt_decode_pokemon_species_for_region')) {
                    $name = trim((string)bxt_decode_pokemon_species_for_region($game_region, $species_id));
                }
                if ($name === "") {
                    $name = trim((string)$pkm_util->getSpeciesName($species_id));
                }
                if ($name !== "") {
                    return $name;
                }
            }
        }

        $fallback = bxt_battle_tower_fallback_pokemon_name($fallback_decode);
        if ($fallback !== "") {
            return $fallback;
        }

        return "UNKNOWN";
    }

    $selected_level = bxt_battle_tower_parse_level($_GET["level"] ?? 10);
    $selected_room = bxt_battle_tower_parse_room($_GET["room"] ?? 1);

    $pkm_util = PokemonUtil::getInstance();
    $db_util = DBUtil::getInstance();
    $db = $db_util->getDB();

    [$room_is_zero_based, $level_is_zero_based] = bxt_battle_tower_detect_storage_indexing($db);

    $db_level = $level_is_zero_based ? intval(($selected_level / 10) - 1) : $selected_level;
    $db_room = $room_is_zero_based ? ($selected_room - 1) : $selected_room;

    $leaders = [];

    $stmt = $db->prepare(
        "select " .
            "id, " .
            "game_region, " .
            "player_name, " .
            "player_name_decode, " .
            "`class` as trainer_class_id, " .
            "class_decode, " .
            "pokemon1, " .
            "pokemon1_decode, " .
            "pokemon2, " .
            "pokemon2_decode, " .
            "pokemon3, " .
            "pokemon3_decode, " .
            "message_start, " .
            "message_start_decode, " .
            "timestamp " .
        "from bxt_battle_tower_honor_roll " .
        "where level = ? and room = ? " .
        "order by timestamp desc, id desc"
    );

    if (!$stmt) {
        error_log("Battle Tower query prepare failed: " . $db->error);
        echo TemplateUtil::render("/pokemon/battletower", [
            "leaders" => [],
            "selected_level" => $selected_level,
            "selected_room" => $selected_room,
            "level_options" => range(10, 100, 10),
            "room_options" => range(1, 20),
        ]);
        exit;
    }

    $stmt->bind_param("ii", $db_level, $db_room);
    if (!$stmt->execute()) {
        error_log("Battle Tower query execute failed: " . $stmt->error);
        echo TemplateUtil::render("/pokemon/battletower", [
            "leaders" => [],
            "selected_level" => $selected_level,
            "selected_room" => $selected_room,
            "level_options" => range(10, 100, 10),
            "room_options" => range(1, 20),
        ]);
        exit;
    }

    $data = DBUtil::fancy_get_result($stmt);
    foreach ($data as $entry) {
        $region = strtolower((string)($entry["game_region"] ?? "e"));
        $player_name = trim((string)($entry["player_name_decode"] ?? ""));
        if ($player_name === "" && isset($entry["player_name"])) {
            $player_name = trim((string)$pkm_util->getString($region, $entry["player_name"]));
        }
        if ($player_name === "") {
            $player_name = "UNKNOWN";
        }

        $trainer_class_id = intval($entry["trainer_class_id"] ?? 0);
        $trainer_class = bxt_battle_tower_decode_trainer_class_name(
            $region,
            $trainer_class_id,
            $entry["class_decode"] ?? ""
        );

        $pokemon_names = [];
        $slot_map = [
            "pokemon1" => "pokemon1_decode",
            "pokemon2" => "pokemon2_decode",
            "pokemon3" => "pokemon3_decode",
        ];

        foreach ($slot_map as $pokemon_slot => $decode_slot) {
            $pokemon_blob = $entry[$pokemon_slot] ?? null;
            $pokemon_names[] = bxt_battle_tower_decode_species_name(
                $pkm_util,
                $region,
                $pokemon_blob,
                $entry[$decode_slot] ?? ""
            );
        }

        $message_tokens = bxt_battle_tower_decode_message_tokens($region, $entry["message_start"] ?? null);
        $message = bxt_battle_tower_format_message_tokens($message_tokens, $region);

        if ($message === "") {
            $message = trim((string)($entry["message_start_decode"] ?? ""));
        }
        if ($message === "") {
            $message = "...";
        }

        $leaders[] = [
            "sprite_path" => "/images/crystal/trainer_sprites/" . bxt_battle_tower_trainer_sprite_name($trainer_class_id, $trainer_class) . ".png",
            "trainer_class" => $trainer_class,
            "player_name" => $player_name,
            "pokemon_names" => $pokemon_names,
            "message" => $message,
        ];
    }

    echo TemplateUtil::render("/pokemon/battletower", [
        "leaders" => $leaders,
        "selected_level" => $selected_level,
        "selected_room" => $selected_room,
        "level_options" => range(10, 100, 10),
        "room_options" => range(1, 20),
    ]);
