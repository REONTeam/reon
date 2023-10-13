<?php
	require_once(CORE_PATH."/pokemon/battle_tower.php");
	
	print battleTowerGetRoom("d", intval($_GET['room']));
?>