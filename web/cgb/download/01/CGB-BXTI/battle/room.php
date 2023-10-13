<?php
	require_once(CORE_PATH."/pokemon/battle_tower.php");
	
	print battleTowerGetRoom("i", intval($_GET['room']));
?>