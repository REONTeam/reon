<?php
// SPDX-License-Identifier: MIT
require_once(CORE_PATH . "/database.php");
require_once(CORE_PATH . "/pokemon/func.php");
require_once(__DIR__ . "/../../scripts/bxt_legality_policy.php");

function get_sram_structure($region) {
	return array(
		"trainer_id" => array(
			"bank" => 1,
			"address" => 0xA009,
			"size" => 2
		),
		"secret_id" => array(
			"bank" => 1,
			"address" => $region == "j" ? 0xA3BA : 0xA3D8,
			"size" => 2
		),
		"name" => array(
			"bank" => 1,
			"address" => 0xA00B,
			"size" => $region == "j" ? 5 : 7
		),
		"gender" => array(
			"bank" => $region == "j" ? 4 : 1,
			"address" => $region == "j" ? 0xA000 : 0xBE3D,
			"size" => 1
		),
		"age" => array(
			"bank" => $region == "j" ? 4 : 1,
			"address" => $region == "j" ? 0xA001 : 0xBE3E,
			"size" => 1
		),
		"region" => array(
			"bank" => $region == "j" ? 4 : 1,
			"address" => $region == "j" ? 0xA002 : 0xBE3F,
			"size" => 1
		),
		"zip" => array(
			"bank" => $region == "j" ? 4 : 1,
			"address" => $region == "j" ? 0xA003 : 0xBE40,
			"size" => $region == "j" ? 2 : 3 // we only need to know the first few digits
		),
		"message" => array(
			"bank" => 4,
			"address" => 0xA007,
			"size" => $region == "j" ? 12 : 8
		),
	);
}

function get_news_parameters($region) {
    $db = connectMySQL();
    $stmt = $db->prepare(
        "select id,
                ranking_category_1,
                ranking_category_2,
                ranking_category_3,
                message,
                md5(news_binary) as md5_news_binary,
                octet_length(news_binary) as octet_length_news_binary
           from bxt_news
          where game_region = ?
          order by id desc
          limit 1"
    );
    $stmt->bind_param("s", $region);
    $stmt->execute();
    $rows = fancy_get_result($stmt);
    if (!$rows || count($rows) === 0) {
        return null;
    }
    $row = $rows[0];

    // Backwards compatibility: other code still expects region-suffixed keys.
    $row["message_" . $region] = $row["message"];
    $row["md5(news_binary_" . $region . ")"] = $row["md5_news_binary"];
    $row["octet_length(news_binary_" . $region . ")"] = $row["octet_length_news_binary"];

    return $row;
}


function get_news_parameters_bin($region) {
    $news_param    = get_news_parameters($region);
    $category_info = get_ranking_category_info($news_param);

    // news id, for now a hash of the news binary (first 12 bytes of MD5)
    $out = hex2bin(substr($news_param["md5(news_binary_" . $region . ")"], 0, 24));

    // message displayed in the lower text box before actually downloading the news
    if ($news_param["message_" . $region] != "") {
        $out .= $news_param["message_" . $region];
    } else {
        // minimal placeholder to avoid glitches when no message is set
        $out .= "\x50\x50";
    }
    // terminator
    $out .= "\x50";

    // address to store rankings data at
    $out .= pack("v", 0xA000 + $news_param["octet_length(news_binary_" . $region . ")"]);

    // size of records for each rankings table
    $out .= pack("v", sizeof($category_info) * 2 * 3);
    foreach ($category_info as $category) {
        $base = ($region == "j" ? 24 : 20) + $category["size"];
        $out .= pack("v", $base);
        $out .= pack("v", $base);
        $out .= pack("v", $base);
    }

    // rankings submit configuration
    $sram = get_sram_structure($region);
    foreach (["trainer_id", "secret_id", "name", "gender", "age", "region", "zip", "message"] as $param) {
        $out .= pack("C", $sram[$param]["bank"])
              . pack("v", $sram[$param]["address"])
              . pack("C", $sram[$param]["size"]);
    }
    foreach ($category_info as $category) {
        $out .= pack("C", 5)
              . $category["ram_address"]
              . pack("C", $category["size"]);
    }
    $out .= "\xFF";

    // rankings query configuration
    foreach (["region", "zip"] as $param) {
        $out .= $param
              . "\x50"
              . pack("C", $sram[$param]["bank"])
              . pack("v", $sram[$param]["address"])
              . pack("C", $sram[$param]["size"]);
    }
    foreach (["trainer_id", "secret_id"] as $param) {
        $out .= "my_" . $param
              . "\x50"
              . pack("C", $sram[$param]["bank"])
              . pack("v", $sram[$param]["address"])
              . pack("C", $sram[$param]["size"]);
    }
    $out .= "my_account_id"
          . "\x50"
          . pack("C", 0)
          . pack("v", 0xDE02)
          . pack("C", 4);
    $out .= "\x50";

    return $out;
}


function get_news_file($region) {
    $db = connectMySQL();
    $stmt = $db->prepare(
        "select news_binary
           from bxt_news
          where game_region = ?
          order by id desc
          limit 1"
    );
    $stmt->bind_param("s", $region);
    $stmt->execute();
    $rows = fancy_get_result($stmt);
    if (!$rows || count($rows) === 0) {
        return null;
    }
    return $rows[0]["news_binary"];
}


function get_ranking_category_info($news_param) {
	$category_ids = [$news_param["ranking_category_1"], $news_param["ranking_category_2"], $news_param["ranking_category_3"]];
	$arr = join(",", array_fill(0, count($category_ids), "?"));
	$db = connectMySQL();
	$stmt = $db->prepare("select id, name, ram_address, size from bxt_ranking_categories where id in (".$arr.") order by field (id, ".$arr.")");
	$stmt->bind_param(str_repeat("i", count($category_ids) * 2), ...array_merge($category_ids, $category_ids));
	$stmt->execute();
	return fancy_get_result($stmt);
}

function set_ranking($region, $content, $length) {
    $sram          = get_sram_structure($region);
    $news_param    = get_news_parameters($region);
    $category_info = get_ranking_category_info($news_param);

    // sanity check
    $expected_data_size = 0;
    foreach (["trainer_id", "secret_id", "name", "gender", "age", "region", "zip", "message"] as $param) {
        $expected_data_size += $sram[$param]["size"];
    }
    foreach ($category_info as $category) {
        $expected_data_size += $category["size"];
    }
    if ($length != $expected_data_size) {
        return;
    }

    $post_data = fopen($content, "rb");
    $trainer_id = unpack("n", fread($post_data, $sram["trainer_id"]["size"]))[1];
    $secret_id  = unpack("n", fread($post_data, $sram["secret_id"]["size"]))[1];
    $name       = fread($post_data, $sram["name"]["size"]);
    $gender     = unpack("C", fread($post_data, $sram["gender"]["size"]))[1];
    $age        = unpack("C", fread($post_data, $sram["age"]["size"]))[1];
    $pregion    = unpack("C", fread($post_data, $sram["region"]["size"]))[1];

    if ($region == "j") {
        // J: zip is numeric; convert to packed bytes (will be stored as BINARY)
        $zip_num = unpack("n", fread($post_data, $sram["zip"]["size"]))[1];
        $zip     = pack("n", $zip_num);
    } else {
        // non-J: already binary bytes
        $zip = fread($post_data, $sram["zip"]["size"]);
    }

    $message    = fread($post_data, $sram["message"]["size"]);
if (!isset($_SESSION["userId"])) {
        return;
    }

    $db = connectMySQL();
    foreach ($category_info as $category) {
        $score_raw = fread($post_data, $category["size"]);
        if ($category["size"] == 1) {
            $score = unpack("C", $score_raw)[1];
        } else if ($category["size"] == 2) {
            $score = unpack("n", $score_raw)[1];
        } else if ($category["size"] == 3) {
            $score = unpack("N", "\x00" . $score_raw)[1];
        } else {
            $score = unpack("N", $score_raw)[1];
        }

        // Look up any existing score for this account/trainer/secret in this category and region
        $stmt = $db->prepare(
            "select score
               from bxt_ranking
              where game_region = ?
                and news_id = ?
                and category_id = ?
                and account_id = ?
                and trainer_id = ?
                and secret_id = ?"
        );
        // 1 string + 5 ints  => "siiiii"
        $stmt->bind_param(
            "siiiii",
            $region,
            $news_param["id"],
            $category["id"],
            $_SESSION["userId"],
            $trainer_id,
            $secret_id
        );
        $stmt->execute();
        $result = fancy_get_result($stmt);

        if (count($result) > 0 && $result[0]["score"] >= $score) {
            // Existing score is better or equal; do not replace
            continue;
        }

        if (count($result) > 0) {
            // Update existing row with new better score and metadata
            $stmt = $db->prepare(
                "update bxt_ranking
                    set player_name    = ?,
                        player_gender  = ?,
                        player_age     = ?,
                        player_region  = ?,
                        player_zip     = ?,
                        player_message = ?,
                        score          = ?
                  where game_region = ?
                    and news_id      = ?
                    and category_id  = ?
                    and account_id   = ?
                    and trainer_id   = ?
                    and secret_id    = ?"
            );
            // params:
            //  name(s), gender(i), age(i), pregion(i), zip(s), message(s), score(i),
            //  region(s), news_id(i), category_id(i), account_id(i), trainer_id(i), secret_id(i)
            //  => 13 args, types "siiissisiiiii"
            $stmt->bind_param(
                "siiissisiiiii",
                $name,
                $gender,
                $age,
                $pregion,
                $zip,
                $message,
                $score,
                $region,
                $news_param["id"],
                $category["id"],
                $_SESSION["userId"],
                $trainer_id,
                $secret_id
            );
            $stmt->execute();
        } else {
            // Insert new row
            $stmt = $db->prepare(
                "insert into bxt_ranking
                 (game_region,
                  news_id, category_id, account_id, trainer_id, secret_id,
                  player_name, player_gender, player_age, player_region, player_zip,
                  player_message, score)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)"
            );
            // params:
            //  region(s),
            //  news_id(i), category_id(i), account_id(i), trainer_id(i), secret_id(i),
            //  name(s), gender(i), age(i), pregion(i), zip(s), message(s), score(i)
            //  => 13 args, types "siiiiisiiissi"
            $stmt->bind_param(
                "siiiiisiiissi",
                $region,
                $news_param["id"],
                $category["id"],
                $_SESSION["userId"],
                $trainer_id,
                $secret_id,
                $name,
                $gender,
                $age,
                $pregion,
                $zip,
                $message,
                $score
            );
            $stmt->execute();
        }
    }

    return pack("N", $_SESSION["userId"]);
}



function get_ranking($region, $post_string) {
    parse_str($post_string, $post_data);
    $news_param    = get_news_parameters($region);
    $category_info = get_ranking_category_info($news_param);

    $out = "";
    $db  = connectMySQL();

    foreach ($category_info as $key => $category) {
        // national ranking (all records in this game_region for this news+category)
        $stmt = $db->prepare(
            "select count(*)
               from bxt_ranking
              where game_region = ?
                and news_id     = ?
                and category_id = ?"
        );
        // region(s), news_id(i), category_id(i) => "sii"
        $stmt->bind_param("sii", $region, $news_param["id"], $category["id"]);
        $stmt->execute();
        $total_ranked = fancy_get_result($stmt)[0]["count(*)"];

        $my_score = 0xFFFFFFFF;
        $my_rank  = 0xFFFFFFFF;

        if (isset($post_data["my_trainer_id"]) &&
            isset($post_data["my_secret_id"])  &&
            isset($post_data["my_account_id"])) {

            $my_trainer_id = hexdec($post_data["my_trainer_id"]);
            $my_secret_id  = hexdec($post_data["my_secret_id"]);
            $my_account_id = hexdec($post_data["my_account_id"]);

            $stmt = $db->prepare(
                "select score, timestamp
                   from bxt_ranking
                  where game_region = ?
                    and news_id     = ?
                    and category_id = ?
                    and account_id  = ?
                    and trainer_id  = ?
                    and secret_id   = ?"
            );
            // region(s), news_id(i), category_id(i), account_id(i), trainer_id(i), secret_id(i)
            // => "siiiii"
            $stmt->bind_param(
                "siiiii",
                $region,
                $news_param["id"],
                $category["id"],
                $my_account_id,
                $my_trainer_id,
                $my_secret_id
            );
            $stmt->execute();
            $result = fancy_get_result($stmt);

            if (count($result) > 0) {
                $my_score     = $result[0]["score"];
                $my_timestamp = $result[0]["timestamp"];

                $stmt = $db->prepare(
                    "select count(*)
                       from bxt_ranking
                      where game_region = ?
                        and news_id     = ?
                        and category_id = ?
                        and (score > ? or (score = ? and timestamp < ?))"
                );
                // region(s), news_id(i), category_id(i), score(i), score(i), timestamp(s)
                // => "siiiis"
                $stmt->bind_param(
                    "siiiis",
                    $region,
                    $news_param["id"],
                    $category["id"],
                    $my_score,
                    $my_score,
                    $my_timestamp
                );
                $stmt->execute();
                $my_rank = fancy_get_result($stmt)[0]["count(*)"] + 1;
            }
        }

        // national top 10 for this region
        $stmt = $db->prepare(
            "select player_name,
                    player_region,
                    player_gender,
                    player_age,
                    player_zip,
                    player_message,
                    score
               from bxt_ranking
              where game_region = ?
                and news_id     = ?
                and category_id = ?
              order by score desc, timestamp
              limit 10"
        );
        // region(s), news_id(i), category_id(i) => "sii"
        $stmt->bind_param("sii", $region, $news_param["id"], $category["id"]);
        $stmt->execute();
        $top10 = fancy_get_result($stmt);

        $out .= make_ranking_table($region, $category, $top10, $total_ranked, $my_rank);

        // regional ranking (within same game_region but filtered by player_region)
        if (isset($post_data["region"])) {
            $pregion = hexdec($post_data["region"]);

            $stmt = $db->prepare(
                "select count(*)
                   from bxt_ranking
                  where game_region  = ?
                    and news_id      = ?
                    and category_id  = ?
                    and player_region = ?"
            );
            // region(s), news_id(i), category_id(i), pregion(i) => "siii"
            $stmt->bind_param("siii", $region, $news_param["id"], $category["id"], $pregion);
            $stmt->execute();
            $total_ranked = fancy_get_result($stmt)[0]["count(*)"];

            $stmt = $db->prepare(
                "select player_name,
                        player_region,
                        player_gender,
                        player_age,
                        player_zip,
                        player_message,
                        score
                   from bxt_ranking
                  where game_region  = ?
                    and news_id      = ?
                    and category_id  = ?
                    and player_region = ?
                  order by score desc, timestamp
                  limit 10"
            );
            // region(s), news_id(i), category_id(i), pregion(i) => "siii"
            $stmt->bind_param("siii", $region, $news_param["id"], $category["id"], $pregion);
            $stmt->execute();
            $top10 = fancy_get_result($stmt);

            $out .= make_ranking_table($region, $category, $top10, $total_ranked, $my_rank);
        }
    }

    // return bin2hex($out);
    return $out;
}


function make_ranking_table($region, $category, $top10, $total_ranked, $my_rank) {
	$out = pack("N", $total_ranked);
	$out .= pack("n", 0); // currently unknown
	$out .= pack("N", $my_rank);
	$out .= pack("n", sizeof($top10));
	foreach ($top10 as $player) {
		$out .= $player["player_name"];
		if ($region == "j") $out .= hex2bin("5000"); // japanese version has a 6th byte for the name plus a completely unused byte
		$out .= pack("C", $player["player_region"]);
		$out .= pack("n", 0); // unused 2 bytes
		$out .= pack("C", $player["player_age"]);
		$out .= pack("C", $player["player_gender"]);
		$out .= $player["player_message"];
		if ($category["size"] == 1) {
			$score = pack("C", $player["score"]);
		} else if ($category["size"] == 2) {
			$score = pack("n", $player["score"]);
		} else if ($category["size"] == 3) {
			$score = pack("N", $player["score"]);
			$score = substr($score, 1);
			//$score = unpack("ca/ab/cc", hex2bin($post_data["category_".$key]));
			//$score = $score["a"] + ($score["b"] << 8) + ($score["c"] << 16);
		} else {
			$score = pack("N", $player["score"]);
		}
		$out .= $score;
	}
	return $out;
}