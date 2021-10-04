<?php
// SPDX-License-Identifier: MIT
require CORE_PATH.'/pokemon/func.php';
require CORE_PATH.'/database.php';

/*if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // HTTP405 Method not Allowed.
    die("405 Method not Allowed"); // Exit but scarier
};*/

$data = decodeExchange("php://input", true); // This makes a nice array of data.
$uuid = generate_UUID(); //11 character UUID, for a unique key.
$db = connectMySQL(); // Connect to DION Database!

// First, delete any existing trades for that user.
$stmt = $db->prepare("DELETE IGNORE FROM `pkm_trades` WHERE email = ?;");
$stmt->bind_param("s",$data["email"]);
$stmt->execute();

// Now, begin adding the new trade data...
$stmt = $db->prepare("INSERT INTO `pkm_trades` (tradeid, email, trainer_id, secret_id, offer_gender, offer_species, request_gender, request_species, file) VALUES (?,?,?,?,?,?,?,?,?)");

// Bind the parameters. REMEMBER: Pokémon Species are the DECIMAL index, not hex!
$stmt->bind_param("sssssisis",$uuid,$data["email"],$data["trainer_id"],$data["secret_id"],$data["offer_gender"],$data["offer_species"],$data["req_gender"],$data["req_species"],$data["b64_pokemon"]);
$stmt->execute();

http_response_code(200);
echo("Pokémon uploaded successfully!\n");
echo("Trade ID: ".$uuid);
