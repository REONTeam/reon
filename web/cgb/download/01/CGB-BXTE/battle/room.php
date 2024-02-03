<?php
	require_once(CORE_PATH."/pokemon/battle_tower.php");
	
	print battleTowerGetRoom("e", intval($_GET['room']));
?>