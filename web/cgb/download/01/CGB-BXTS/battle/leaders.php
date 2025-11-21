<?php
	require_once(CORE_PATH."/pokemon/battle_tower.php");
	
		print battleTowerGetLeaders("s", intval($_GET['room']), true);
?>