<?php

if (str_starts_with($_SERVER['SCRIPT_NAME'], "/cgb/")) {
	$_SERVER['SCRIPT_NAME'] = $_SERVER['SCRIPT_NAME'] . ".php";
}
require __DIR__ . $_SERVER['SCRIPT_NAME'];
