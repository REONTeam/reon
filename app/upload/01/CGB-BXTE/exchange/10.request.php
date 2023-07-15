<?php
	// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/pokemon/trade_corner.php");
	
	process_trade_request("php://input", "e");
	echo("Pokémon uploaded successfully!\n");
