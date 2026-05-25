<?php
	require_once("../../classes/TemplateUtil.php");
	require_once("../../classes/DBUtil.php");
	require_once("../../classes/SessionUtil.php");
	require_once("../../classes/PokemonUtil.php");
	session_start();

    /**
     * Resolve the effective trade region pool for a specific game region from
     * a user trade_region_allowlist string.
     *
     * Example allowlist values:
     * - "e,f,d,s,i,p,u,j"  (national only)
     * - "efdsipu,j"        (Latin languages together, JP separate)
     * - "efdsipuj"         (all regions together)
     */
    function bxt_trade_regions_for_user_region($game_region, $allowlist) {
        $region = strtolower(trim((string)$game_region));
        if ($region === '') {
            return array();
        }

        $allow = strtolower(trim((string)$allowlist));
        if ($allow === '') {
            $allow = "efdsipuj";
        }

        $pool_for_region = "";
        $pools = explode(",", $allow);
        foreach ($pools as $pool) {
            $pool = strtolower(preg_replace('/[^a-z]/', '', (string)$pool));
            if ($pool === '') {
                continue;
            }
            if (strpos($pool, $region) !== false) {
                $pool_for_region = $pool;
                break;
            }
        }

        // Fallback for malformed / legacy values where the region-specific pool
        // cannot be located explicitly.
        if ($pool_for_region === '') {
            $fallback = strtolower(preg_replace('/[^a-z]/', '', $allow));
            if ($fallback !== '' && strpos($fallback, $region) !== false) {
                $pool_for_region = $fallback;
            } else {
                $pool_for_region = $region;
            }
        }

        return array_values(array_unique(str_split($pool_for_region)));
    }

    /**
     * Convert a game region code to the short game ID label used on the UI.
     */
    function bxt_trade_game_id_label($game_region) {
        switch (strtolower(trim((string)$game_region))) {
            case "e":
                return "ENG";
            case "u":
                return "AUS";
            case "p":
                return "EUR";
            case "d":
                return "GER";
            case "s":
                return "SPA";
            case "i":
                return "ITA";
            case "j":
                return "JPN";
            default:
                return strtoupper((string)$game_region);
        }
    }

    /**
     * Region label text mirroring the Trade Corner dropdown wording.
     */
    function bxt_trade_region_dropdown_label($game_region) {
        switch (strtolower(trim((string)$game_region))) {
            case "j":
                return "JAPAN";
            case "e":
                return "USA";
            case "p":
                return "EUROPE";
            case "u":
                return "AUSTRALIA";
            case "f":
                return "FRANCE";
            case "d":
                return "GERMANY";
            case "i":
                return "ITALY";
            case "s":
                return "SPAIN";
            default:
                return bxt_trade_game_id_label($game_region);
        }
    }

    function bxt_trade_to_fullwidth_caps($text) {
        $text = strtoupper((string)$text);
        $map = array(
            "A" => "\u{FF21}", "B" => "\u{FF22}", "C" => "\u{FF23}", "D" => "\u{FF24}", "E" => "\u{FF25}",
            "F" => "\u{FF26}", "G" => "\u{FF27}", "H" => "\u{FF28}", "I" => "\u{FF29}", "J" => "\u{FF2A}",
            "K" => "\u{FF2B}", "L" => "\u{FF2C}", "M" => "\u{FF2D}", "N" => "\u{FF2E}", "O" => "\u{FF2F}",
            "P" => "\u{FF30}", "Q" => "\u{FF31}", "R" => "\u{FF32}", "S" => "\u{FF33}", "T" => "\u{FF34}",
            "U" => "\u{FF35}", "V" => "\u{FF36}", "W" => "\u{FF37}", "X" => "\u{FF38}", "Y" => "\u{FF39}",
            "Z" => "\u{FF3A}", " " => "\u{3000}",
        );
        return strtr($text, $map);
    }

    /**
     * Build a locale-agnostic species-name search string across all supported UI locales.
     */
    function bxt_trade_species_search_names($species_id) {
        static $cache = array();
        static $supported_locales = array('en', 'es', 'de', 'ja', 'it', 'fr');
        static $translator = null;

        $species_id = intval($species_id);
        if ($species_id <= 0) {
            return "";
        }

        if (isset($cache[$species_id])) {
            return $cache[$species_id];
        }

        if ($translator === null) {
            $translator = TemplateUtil::getTranslator();
        }

        $translation_key = "pokemon.species." . $species_id;
        $names = array();
        foreach ($supported_locales as $locale) {
            $name = trim((string)$translator->trans($translation_key, array(), null, $locale));
            if ($name === "" || $name === $translation_key) {
                continue;
            }
            $names[$name] = true;
        }

        $cache[$species_id] = implode(" ", array_keys($names));
        return $cache[$species_id];
    }

    function bxt_trade_menu_icon_by_species_id($species_id) {
        static $icons_by_species_id = null;
        static $available_icon_names = null;

        if ($icons_by_species_id === null) {
            $icons_by_species_id = array();
            $map_path = realpath(dirname(__DIR__) . "/images/crystal/menu_anims/MenuSprites.txt");
            if ($map_path !== false && is_file($map_path)) {
                $lines = @file($map_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (is_array($lines)) {
                    $curr_id = 1;
                    foreach ($lines as $line) {
                        if (preg_match('/^\s*(ICON_[A-Z0-9_]+)\s*;/', (string)$line, $m) === 1) {
                            $icons_by_species_id[$curr_id] = strtoupper((string)$m[1]);
                            $curr_id++;
                        }
                    }
                }
            }
        }

        if ($available_icon_names === null) {
            $available_icon_names = array();
            $icons_dir = dirname(__DIR__) . "/images/crystal/menu_anims";
            $icon_files = @glob($icons_dir . "/*.gif");
            if (is_array($icon_files)) {
                foreach ($icon_files as $icon_file) {
                    $base = strtoupper(pathinfo((string)$icon_file, PATHINFO_FILENAME));
                    if ($base !== "") {
                        $available_icon_names[$base] = true;
                    }
                }
            }
        }

        $species_id = intval($species_id);
        $icon = "ICON_MONSTER";
        if ($species_id > 0 && isset($icons_by_species_id[$species_id])) {
            $icon = (string)$icons_by_species_id[$species_id];
        }

        if (isset($available_icon_names[$icon])) {
            return $icon;
        }

        // Be tolerant if either historical filename is present.
        if ($icon === "ICON_MOTH" && isset($available_icon_names["ICON_MONTH"])) {
            return "ICON_MONTH";
        }
        if ($icon === "ICON_MONTH" && isset($available_icon_names["ICON_MOTH"])) {
            return "ICON_MOTH";
        }

        return "ICON_MONSTER";
    }

    /**
     * Build the translation key for the exchange-card warning.
     */
    function bxt_trade_warning_key($game_region, $allowlist) {
        $allowed = bxt_trade_regions_for_user_region($game_region, $allowlist);

        if (count($allowed) <= 1) {
            return "pokemon.trade-warning-only-own-game";
        }

        if (!in_array("j", $allowed, true)) {
            return "pokemon.trade-warning-no-japanese";
        }

        return null;
    }

    /**
     * Convert DB timestamp values to Unix epoch seconds.
     */
    function bxt_exchange_timestamp_to_epoch($timestamp_value, $fallback_epoch) {
        if ($timestamp_value instanceof DateTimeInterface) {
            return $timestamp_value->getTimestamp();
        }
        if (is_numeric($timestamp_value)) {
            return intval($timestamp_value);
        }
        $parsed = strtotime((string)$timestamp_value);
        if ($parsed === false) {
            return intval($fallback_epoch);
        }
        return intval($parsed);
    }

    /**
     * Attach countdown metadata for the trade availability timer UI.
     */
    function bxt_exchange_apply_timer_meta(&$entry, $lifetime_seconds, $now_epoch) {
        $created_epoch = 0;
        if (isset($entry["timestamp_epoch"]) && is_numeric($entry["timestamp_epoch"])) {
            $created_epoch = intval($entry["timestamp_epoch"]);
        } else {
            $created_epoch = bxt_exchange_timestamp_to_epoch($entry["timestamp"] ?? null, $now_epoch);
        }
        if ($created_epoch <= 0) {
            $created_epoch = intval($now_epoch);
        }
        $entry["exchange_created_epoch"] = $created_epoch;
        $entry["exchange_lifetime_seconds"] = intval($lifetime_seconds);
        $entry["exchange_expire_epoch"] = $created_epoch + intval($lifetime_seconds);
    }

    /**
     * Return a map of columns for a table: ['column_name' => true, ...]
     */
    function bxt_exchange_table_columns($db, $table_name) {
        $table_name = str_replace("`", "``", (string)$table_name);
        $columns = array();

        $result = $db->query("SHOW COLUMNS FROM `" . $table_name . "`");
        if (!$result) {
            return $columns;
        }

        while ($row = $result->fetch_assoc()) {
            $field = strtolower((string)($row["Field"] ?? ""));
            if ($field !== "") {
                $columns[$field] = true;
            }
        }
        $result->close();
        return $columns;
    }

    /**
     * True when a table exists.
     */
    function bxt_exchange_has_table($db, $table_name) {
        $escaped = $db->real_escape_string((string)$table_name);
        $result = $db->query("SHOW TABLES LIKE '" . $escaped . "'");
        if (!$result) {
            return false;
        }
        $exists = $result->num_rows > 0;
        $result->close();
        return $exists;
    }

    /**
     * True when a column exists in the given columns map.
     */
    function bxt_exchange_has_column($columns_map, $column_name) {
        $name = strtolower((string)$column_name);
        return isset($columns_map[$name]);
    }

    /**
     * Pick the first available column name from the candidate list.
     */
    function bxt_exchange_pick_column($columns_map, $candidates) {
        foreach ($candidates as $candidate) {
            if (bxt_exchange_has_column($columns_map, $candidate)) {
                return (string)$candidate;
            }
        }
        return null;
    }

    $valid_regions = array("global", "j", "int", "eng", "e", "p", "u", "f", "d", "i", "s");

    $region = strtolower($_GET["region"] ?? "global");
    $region = in_array($region, $valid_regions, true) ? $region : "global";
    $pkm_util = PokemonUtil::getInstance();

    $db_util = DBUtil::getInstance();
    
    $db = $db_util->getDB();

    $trades = array();
    $exchange_lifetime_seconds = 7 * 24 * 60 * 60;
    $exchange_now_epoch = time();

    $max_rows = 20;

    $where_clause = "";
    if ($region == 'j') {
        $where_clause = "where bxt_exchange.game_region = 'j' ";
    } else if ($region == 'int') {
        $where_clause = "where bxt_exchange.game_region != 'j' ";
    } else if ($region == 'eng') {
        $where_clause = "where bxt_exchange.game_region IN ('e', 'p', 'u') ";
    } else if (in_array($region, array("e", "p", "u", "f", "d", "i", "s"), true)) {
        $where_clause = "where bxt_exchange.game_region = '" . $region . "' ";
    }

    $exchange_columns = bxt_exchange_table_columns($db, "bxt_exchange");
    $sys_users_exists = bxt_exchange_has_table($db, "sys_users");
    $sys_users_columns = $sys_users_exists ? bxt_exchange_table_columns($db, "sys_users") : array();

    $timestamp_column = bxt_exchange_pick_column($exchange_columns, array("timestamp", "entry_time"));
    $player_name_column = bxt_exchange_pick_column($exchange_columns, array("player_name", "trainer_name"));
    $mail_column = bxt_exchange_has_column($exchange_columns, "mail") ? "mail" : null;
    $has_trade_allowlist = bxt_exchange_has_column($sys_users_columns, "trade_region_allowlist");

    $timestamp_select = "NOW() AS timestamp, UNIX_TIMESTAMP(NOW()) AS timestamp_epoch, ";
    $order_by = "bxt_exchange.trainer_id DESC";
    if (!empty($timestamp_column)) {
        $safe_timestamp_column = str_replace("`", "``", (string)$timestamp_column);
        $timestamp_select =
            "bxt_exchange.`" . $safe_timestamp_column . "` AS timestamp, " .
            "UNIX_TIMESTAMP(bxt_exchange.`" . $safe_timestamp_column . "`) AS timestamp_epoch, ";
        $order_by = "bxt_exchange.`" . $safe_timestamp_column . "` DESC";
    }

    $player_name_select = "'' AS player_name, ";
    if (!empty($player_name_column)) {
        $safe_player_name_column = str_replace("`", "``", (string)$player_name_column);
        $player_name_select = "bxt_exchange.`" . $safe_player_name_column . "` AS player_name, ";
    }

    $mail_select = "NULL AS mail, ";
    if (!empty($mail_column)) {
        $safe_mail_column = str_replace("`", "``", (string)$mail_column);
        $mail_select = "bxt_exchange.`" . $safe_mail_column . "` AS mail, ";
    }

    $query_with_users =
        "select " .
            "bxt_exchange.account_id, " .
            "bxt_exchange.trainer_id, " .
            "bxt_exchange.secret_id, " .
            "bxt_exchange.game_region, " .
            "bxt_exchange.offer_species, " .
            "bxt_exchange.offer_gender, " .
            "bxt_exchange.request_species, " .
            "bxt_exchange.request_gender, " .
            $player_name_select .
            $timestamp_select .
            "bxt_exchange.pokemon, " .
            $mail_select;

    if ($sys_users_exists && $has_trade_allowlist) {
        $query_with_users .=
            "COALESCE(sys_users.trade_region_allowlist, 'efdsipuj') AS trade_region_allowlist " .
            "from bxt_exchange " .
            "left join sys_users on bxt_exchange.account_id = sys_users.id " .
            $where_clause .
            "order by " . $order_by;
    } else {
        $query_with_users .=
            "'efdsipuj' AS trade_region_allowlist " .
            "from bxt_exchange " .
            $where_clause .
            "order by " . $order_by;
    }

    $query_without_users =
        "select " .
            "bxt_exchange.account_id, " .
            "bxt_exchange.trainer_id, " .
            "bxt_exchange.secret_id, " .
            "bxt_exchange.game_region, " .
            "bxt_exchange.offer_species, " .
            "bxt_exchange.offer_gender, " .
            "bxt_exchange.request_species, " .
            "bxt_exchange.request_gender, " .
            $player_name_select .
            $timestamp_select .
            "bxt_exchange.pokemon, " .
            $mail_select .
            "'efdsipuj' AS trade_region_allowlist " .
        "from bxt_exchange " .
        $where_clause .
        "order by " . $order_by;

    $genders = [null, "male", "female", null];
    $genders_symbols = [null, "\u{2642}\u{FE0E}", "\u{2640}\u{FE0E}", null];

    $data = array();
    try {
        $stmt = $db->prepare($query_with_users);
        if ($stmt) {
            $stmt->execute();
            $data = DBUtil::fancy_get_result($stmt);
        }
    } catch (Exception $e) {
        $data = array();
    }

    if (!is_array($data) || count($data) === 0) {
        try {
            $fallback_stmt = $db->prepare($query_without_users);
            if ($fallback_stmt) {
                $fallback_stmt->execute();
                $fallback_data = DBUtil::fancy_get_result($fallback_stmt);
                if (is_array($fallback_data)) {
                    $data = $fallback_data;
                }
            }
        } catch (Exception $e) {
            $data = array();
        }
    }
    foreach ($data as &$entry) {
        $pc = $entry['game_region'];
        $entry["player_name"] = $pkm_util->getString($pc, $entry['player_name']);
        $entry["pokemon"] = $pkm_util->unpackPokemon($pc, $entry["pokemon"]);
        //$entry["mail"] = $pkm_util->unpackMail($pc, $entry["mail"]);
        $request_menu_icon = bxt_trade_menu_icon_by_species_id($entry["request_species"]);
        $entry["request"] = [
            "id" => $entry["request_species"],
            "name" => $pkm_util->getSpeciesName($entry["request_species"]),
            "gender" => $genders[$entry["request_gender"]],
            "gender_symbol" => $genders_symbols[$entry["request_gender"]],
            "search_names" => bxt_trade_species_search_names($entry["request_species"]),
            "menu_icon" => $request_menu_icon,
            "menu_icon_path" => "/images/crystal/menu_anims/" . $request_menu_icon . ".gif",
        ];
        $entry["offer"] = [
            "id" => $entry["offer_species"],
            "name" => $pkm_util->getSpeciesName($entry["offer_species"]),
            "gender" => $genders[$entry["offer_gender"]],
            "gender_symbol" => $genders_symbols[$entry["offer_gender"]],
            "search_names" => bxt_trade_species_search_names($entry["offer_species"]),
        ];
        $entry["trade_warning_key"] = bxt_trade_warning_key(
            $entry["game_region"],
            $entry["trade_region_allowlist"] ?? ""
        );
        $entry["trade_warning_game_id"] = bxt_trade_game_id_label($entry["game_region"]);
        $entry["region_display_fullwidth"] = bxt_trade_to_fullwidth_caps(bxt_trade_game_id_label($entry["game_region"]));
        $entry["region_hover_text"] = bxt_trade_region_dropdown_label($entry["game_region"]);
        bxt_exchange_apply_timer_meta($entry, $exchange_lifetime_seconds, $exchange_now_epoch);
        array_push($trades, $entry);
    }

    if (isset($_GET["add_fakes"])) {

        $pkm = $pkm_util->fakePokemon(6);
        $pkm["pokerus"]["cured"] = true;
        array_push($trades,[
            "pokemon" => $pkm,
            "player_name" => "REON",
            "game_region" => 'e',
            "request" => [
                "id" => 250,
                "name" => $pkm_util->getSpeciesName(250),
                "gender" => $genders[0],
                "gender_symbol" => $genders_symbols[0],
                "search_names" => bxt_trade_species_search_names(250),
                "menu_icon" => bxt_trade_menu_icon_by_species_id(250),
                "menu_icon_path" => "/images/crystal/menu_anims/" . bxt_trade_menu_icon_by_species_id(250) . ".gif",
            ],
            "offer" => [
                "id" => $pkm["species"]["id"],
                "name" => $pkm["species"]["name"],
                "gender" => $genders[1],
                "gender_symbol" => $genders_symbols[1],
                "search_names" => bxt_trade_species_search_names($pkm["species"]["id"]),
            ],
        ]);
        bxt_exchange_apply_timer_meta($trades[count($trades) - 1], $exchange_lifetime_seconds, $exchange_now_epoch);

        $pkm = $pkm_util->fakePokemon(197);
        array_push($trades,[
            "pokemon" => $pkm,
            "player_name" => "REON",
            "game_region" => 'e',
            "request" => [
                "id" => 200,
                "name" => $pkm_util->getSpeciesName(200),
                "gender" => $genders[2],
                "gender_symbol" => $genders_symbols[2],
                "search_names" => bxt_trade_species_search_names(200),
                "menu_icon" => bxt_trade_menu_icon_by_species_id(200),
                "menu_icon_path" => "/images/crystal/menu_anims/" . bxt_trade_menu_icon_by_species_id(200) . ".gif",
            ],
            "offer" => [
                "id" => $pkm["species"]["id"],
                "name" => $pkm["species"]["name"],
                "gender" => $genders[2],
                "gender_symbol" => $genders_symbols[2],
                "search_names" => bxt_trade_species_search_names($pkm["species"]["id"]),
            ],
        ]);
        bxt_exchange_apply_timer_meta($trades[count($trades) - 1], $exchange_lifetime_seconds, $exchange_now_epoch);
        end($trades)["pokemon"]["pokerus"]["cured"] = true;
    }

    echo TemplateUtil::render("/pokemon/tradecorner", [
        'trades' => $trades,
        'region_filter' => $region
    ]);
	
