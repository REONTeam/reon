<?php
// SPDX-License-Identifier: MIT
require_once(CORE_PATH."/pokemon/news.php");

print get_ranking("e", file_get_contents("php://input"));