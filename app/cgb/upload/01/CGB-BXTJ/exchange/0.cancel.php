<?php
// SPDX-License-Identifier: MIT
require CORE_PATH.'/pokemon/func.php';
require CORE_PATH.'/database.php';

/*if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // HTTP405 Method not Allowed.
    die('405 Method not Allowed'); // Exit but scarier!
};*/

$data = decodeExchange("php://input", false); // This makes a nice array of data.
$db = connectMySQL(); // Connect to DION Database!

$stmt = $db->prepare("DELETE FROM `btxj_pkm_trades` WHERE email = ?;"); // Delete the trade from Database.
$stmt->bind_param("s",$data["email"]);
$stmt->execute();

