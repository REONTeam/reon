<?php
// utility
define('CORE_PATH', dirname(dirname(__DIR__)) . '/core');
require_once(CORE_PATH.'/core.php');
require_once(CORE_PATH.'/auth.php');

	doAuth(true);
			
	if (isset($_GET["request"]))
	{
		if ($_GET["request"] == "summary")
		{
			echo "test xd";
		}
	}
?>