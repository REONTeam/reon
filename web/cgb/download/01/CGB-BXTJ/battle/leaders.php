<?php
	require_once(CORE_PATH."/pokemon/battle_tower.php");
	
		print battleTowerGetLeaders("j", intval($_GET['room']), false);
?>