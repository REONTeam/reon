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

    if (isset($_GET["add_fakes"])) {

        $pkm = $pkm_util->fakePokemon(6);
        $pkm["pokerus"]["cured"] = true;
        array_push($trades,[
            "pokemon" => $pkm,
            "trainer_name" => "REON",
            "game_region" => 'e',
            "request" => [
                "id" => 250,
                "name" => $pkm_util->getSpeciesName(250),
                "gender" => $genders[0],
                "gender_symbol" => $genders_symbols[0],
            ],
            "offer" => [
                "id" => $pkm["species"]["id"],
                "name" => $pkm["species"]["name"],
                "gender" => $genders[1],
                "gender_symbol" => $genders_symbols[1],
            ],
        ]);

        $pkm = $pkm_util->fakePokemon(197);
        array_push($trades,[
            "pokemon" => $pkm,
            "trainer_name" => "REON",
            "game_region" => 'e',
            "request" => [
                "id" => 200,
                "name" => $pkm_util->getSpeciesName(200),
                "gender" => $genders[2],
                "gender_symbol" => $genders_symbols[2],
            ],
            "offer" => [
                "id" => $pkm["species"]["id"],
                "name" => $pkm["species"]["name"],
                "gender" => $genders[2],
                "gender_symbol" => $genders_symbols[2],
            ],
        ]);
        end($trades)["pokemon"]["pokerus"]["cured"] = true;
    }

    echo TemplateUtil::render("/pokemon/exchange", [
        'trades' => $trades
    ]);
	