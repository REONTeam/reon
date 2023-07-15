<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/pokemon/trade_corner.php");
	
	$uuid = process_trade_request("php://input", "j");
	echo("Pokémon uploaded successfully!\n");
	echo("Trade ID: ".$uuid);
