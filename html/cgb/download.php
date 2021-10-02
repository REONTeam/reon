<?php
define('CORE_PATH', dirname(dirname(__DIR__)) . '/core');
require_once(CORE_PATH.'/core.php');
require_once(CORE_PATH.'/auth.php');

	doAuth();
    serveFileOrExecScript($_GET["name"], "download");
?>