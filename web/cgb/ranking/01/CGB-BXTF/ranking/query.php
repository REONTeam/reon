<?php
// SPDX-License-Identifier: MIT
require_once(CORE_PATH."/pokemon/news.php");

print get_ranking("f", file_get_contents("php://input"));