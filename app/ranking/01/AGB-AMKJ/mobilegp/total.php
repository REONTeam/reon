<?php
	// SPDX-License-Identifier: MIT
	// Probably total amount of entries in the ranking (Mobile GP)
	
	require_once(CORE_PATH."/mario_kart.php");
	
	echo pack("N", getTotalRankingEntriesMobileGP());
?>