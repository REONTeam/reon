<?php
	require_once("../../classes/TemplateUtil.php");
	session_start();
	
	echo TemplateUtil::render("pokemon/index", [
		
	]);