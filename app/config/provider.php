<?php

return array(
	new \Silex\Provider\DoctrineServiceProvider(),
	new \Silex\Provider\TwigServiceProvider(),
	new \Silex\Provider\SessionServiceProvider(),
    new \JDesrosiers\Silex\Provider\CorsServiceProvider(),
);