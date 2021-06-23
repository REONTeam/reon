<?php
	require_once(CORE_PATH."/pokemon/battle_tower.php");
	
	battleTowerSubmitRecord("php://input", true);
	echo("Record uploaded successfully!");
?>