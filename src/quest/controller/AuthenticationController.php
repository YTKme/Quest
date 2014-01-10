<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\DBAL\DBALException;

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
		$host = $request->getSchemeAndHttpHost();
		
		if (!$userModel = $application['quest.orm.manager']->getRepository('UserModel')->findOneBy(array('username' => $username, 'password' => $hashPassword))) {
			// Redirect back to login
			return new RedirectResponse($host);
		}
		
		// Save username to session
		$application['session']->set('_USERNAME', $username);
		
		// Redirect to main
		return new RedirectResponse($host . '/main');
	}
	
	/**
	 * Logout
	 *
	 * @method GET
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function logout (Request $request, Application $application) {
		$host = $request->getSchemeAndHttpHost();
		
		// Clear session
		$application['session']->set('_USERNAME', "");
		$application['session']->clear();
		
		return new RedirectResponse($host);
	}
	
}