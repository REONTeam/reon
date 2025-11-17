<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/pokemon/trade_corner_legality.php");
	
	process_trade_request("i", "php://input");
	echo("Pokémon uploaded successfully!\n");
