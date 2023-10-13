<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/pokemon/trade_corner.php");
	
	process_trade_request("d", "php://input");
	echo("Pokémon uploaded successfully!\n");
