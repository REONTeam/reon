<?php
	require_once(CORE_PATH."/pokemon/battle_tower.php");
	
		print battleTowerGetLeaders("e", intval($_GET['room']), true);
?>