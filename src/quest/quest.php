<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Silex\Application;

/*
 * SILEX APPLICATION
 */
$application = new Application(array('ROOT_PATH' => realpath(__DIR__ . '/..')));

/*
 * CONFIGURATION
 */
$provider = require __DIR__ . '/../../app/config/provider.php';
foreach ($provider as $instance) {
	if($instance instanceof \Silex\ServiceProviderInterface) {
		$application->register($instance);
	}
}

$parameter = require __DIR__ . '/../../app/config/parameter.php';
$service = require __DIR__ . '/../../app/config/service.php';

$config = array_merge($parameter, $service);
foreach ($config as $key => $value) {
	$application[$key] = $value;
}

/*
 * BOOT
 */
$application->boot();

return $application;