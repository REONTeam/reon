<?php
	require_once("../../classes/TemplateUtil.php");
	require_once("../../classes/DBUtil.php");
	require_once("../../classes/SessionUtil.php");
	require_once("../../classes/PokemonUtil.php");
	session_start();
	
    $valid_regions = array("j", "int");

    $region = strtolower($_GET["region"] ?? '');
    $region = in_array($region, $valid_regions) ? $region : "global";
    $pkm_util = PokemonUtil::getInstance();

    $db_util = DBUtil::getInstance();
    
    $db = $db_util->getDB();

    $trades = array();

    $max_rows = 20;

    $sql_jp = "select account_id, trainer_id, secret_id, 'j' game_region, offer_species, offer_gender, request_species, request_gender, trainer_name, entry_time, pokemon, mail from bxtj_exchange";
    $sql_int = "select account_id, trainer_id, secret_id, game_region, offer_species, offer_gender, request_species, request_gender, trainer_name, entry_time, pokemon, mail from bxt_exchange";

    $stmt = $db->prepare(
        ($region == 'j' || $region == 'global' ? $sql_jp : '').
        ($region == 'global' ? ' UNION ALL ' : '').
        ($region == 'int' || $region == 'global' ? $sql_int : '').
        " order by entry_time desc"
    );
    
    $genders = [null, "male", "female", null];
    $genders_symbols = [null, "♂", "♀", null];

    $stmt->execute();
    $data = DBUtil::fancy_get_result($stmt);
    foreach ($data as &$entry) {
        $pc = $entry['game_region'];
        $entry["trainer_name"] = $pkm_util->getString($pc, $entry['trainer_name']);
        $entry["pokemon"] = $pkm_util->unpackPokemon($pc, $entry["pokemon"]);
        //$entry["mail"] = $pkm_util->unpackMail($pc, $entry["mail"]);
        $entry["request"] = [
            "id" => $entry["request_species"],
            "name" => $pkm_util->getSpeciesName($entry["request_species"]),
            "gender" => $genders[$entry["request_gender"]],
            "gender_symbol" => $genders_symbols[$entry["request_gender"]],
        ];
        $entry["offer"] = [
            "id" => $entry["offer_species"],
            "name" => $pkm_util->getSpeciesName($entry["offer_species"]),
            "gender" => $genders[$entry["offer_gender"]],
            "gender_symbol" => $genders_symbols[$entry["offer_gender"]],
        ];
        array_push($trades, $entry);
    }
    
    echo TemplateUtil::render("/pokemon/exchange", [
        'trades' => $trades
    ]);
	