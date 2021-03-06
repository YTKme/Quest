<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DocumentationController {

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
		if (empty($sessionUsername)) {
			return new RedirectResponse($host);
		}
		
		return $application['twig']->render('doc/documentation.html.twig', array(
			'_USERNAME' => $sessionUsername
		));
	}

}