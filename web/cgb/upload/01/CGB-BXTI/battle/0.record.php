<?php
// SPDX-License-Identifier: MIT
	require_once(CORE_PATH."/pokemon/battle_tower_legality.php");

	battleTowerSubmitRecord_legality("php://input", 'i');
	echo("Record uploaded successfully!");
?>
