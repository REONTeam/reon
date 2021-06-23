<?php
	require_once(CORE_PATH."/pokemon/battle_tower.php");

	battleTowerSubmitRecord("php://input");
	echo("Record uploaded successfully!");
?>