<?php
	require_once(CORE_PATH."/pokemon/battle_tower.php");
	
	print battleTowerGetRoom("f", intval($_GET['room']));
?>