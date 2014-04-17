<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MainController {

	/**
	 * Constructor
	 */
	public function __construct () {

	}

	/**
	 * Main
	 * @param Request $request
	 * @param Application $application
	 * @return mixed
	 */
	public function main (Request $request, Application $application) {
		$host = $request->getSchemeAndHttpHost();
		$sessionUsername = $application['session']->get('_USERNAME');
		
		// Validate user login
		if (empty($application['session']->get('_USERNAME'))) {
			return new RedirectResponse($host);
		}
		
		return $application['twig']->render('main.html.twig', array(
			'_USERNAME' => $sessionUsername
		));
	}

}