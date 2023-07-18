<?php
// SPDX-License-Identifier: MIT
// utility
define('CORE_PATH', dirname(dirname(__DIR__)) . '/core');
require_once(CORE_PATH.'/core.php');
require_once(CORE_PATH.'/auth.php');

	$userId = doAuth(2);
			
	if (isset($_GET["request"]))
	{
		if ($_GET["request"] == "summary")
		{
			echo "Total amount of money<br>spent: ";
			echo getMoneySpent($userId);
			echo "\\";
		}
	}
?>