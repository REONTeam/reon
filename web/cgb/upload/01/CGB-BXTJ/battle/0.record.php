<?php
	require_once(CORE_PATH."/pokemon/battle_tower_legality.php");

	battleTowerSubmitRecord_legality("php://input");
	echo("Record uploaded successfully!");
?>