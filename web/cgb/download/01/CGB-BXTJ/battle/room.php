<?php
	require_once(CORE_PATH."/pokemon/battle_tower.php");
	
	print battleTowerGetRoom("j", intval($_GET['room']));
?>