<?php

$application = require __DIR__ . '/../src/quest/quest.php';
$application->run();

// CORS
$application->after(function (Request $request, Response $response) {
	$response->headers->set('Access-Control-Allow-Origin', '*');
});