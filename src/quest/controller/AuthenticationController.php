<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\DBALException;

class AuthenticationController {
	
	public function __construct () {
		
	}
	
	/**
	 * Login
	 *
	 * @method POST
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function login (Request $request, Application $application) {
		
	}
	
}