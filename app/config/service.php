<?php

use Silex\Application;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;

return array(
	'routes' => $application->share($application->extend('routes', function (RouteCollection $route, Application $application) {
		$loader = new YamlFileLoader(new FileLocator(__DIR__ . '/../../app/route'));
		$route->addCollection($loader->load('route.yml'));
		return $route;
	})),
	
	'quest.orm.manager' => $application->share(function ($container) {
		$modelDirectory = __DIR__ . '/../../src/quest/model';
		$proxyDirectory = __DIR__ . '/../../src/quest/model/proxy';
		$proxyNamespace = 'Quest\Proxy';
		
		$config = new Configuration();
		
		$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver($modelDirectory));
		$config->setProxyDir($proxyDirectory);
		$config->setProxyNamespace($proxyNamespace);
		
		return EntityManager::create($container['db'], $config);
	}),
	
	'twig' => $application->share($application->extend('twig', function (Twig_Environment $twig, Application $application) {
		$loader = new Twig_Loader_Filesystem(__DIR__ . '/../../src/quest/view');
		$twig = new Twig_Environment($loader);
		$host = $application['request']->getSchemeAndHttpHost();
		$path = $application['request']->getPathInfo();
		
		//$twig->addGlobal('APPLICATION', $application);
		$twig->addGlobal('RESOURCE', '/web/res');
		$twig->addGlobal('HOST', $host);
		$twig->addGlobal('PATH', $path);
		
		return $twig;
	}))
);