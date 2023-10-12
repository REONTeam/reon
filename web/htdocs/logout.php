<?php
	require_once("../classes/SessionUtil.php");
	session_start();
	
	SessionUtil::getInstance()->destroySession();
	header("Location: index.php");