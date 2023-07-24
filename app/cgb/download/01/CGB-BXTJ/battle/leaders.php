<?php
	require_once(CORE_PATH."/pokemon/battle_tower.php");
	
	print battleTowerGetLeaders(intval($_GET['room']));
?>