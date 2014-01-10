<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\DBALException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthenticationController {
	
	/**
	 * Constructor
	 */
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
		$username = $request->request->get("txtUsername");
		$password = $request->request->get("txtPassword");
		$hashPassword = hash('sha256', hash('sha256', $password) . $username);
		$host = $application['request']->getSchemeAndHttpHost();
		
		if (!$userModel = $application['quest.orm.manager']->getRepository('UserModel')->findOneBy(array('username' => $username, 'password' => $hashPassword))) {
			return new RedirectResponse($host);
		}
		
		return new Response("Logged In", 200);
	}
	
}