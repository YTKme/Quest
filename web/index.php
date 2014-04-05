<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
$application = require __DIR__ . '/../src/quest/quest.php';
$application->run();