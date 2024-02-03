<?php
// SPDX-License-Identifier: MIT
require_once(CORE_PATH."/pokemon/news.php");

print set_ranking("u", "php://input", $_SERVER['CONTENT_LENGTH']);