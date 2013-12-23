<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestController {

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
		return $application['twig']->render('index.html.twig');
	}
	
	/**
	 * Error
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function error (Request $request, Application $application) {
		//return new Response($request->getSchemeAndHttpHost() . $request->getRequestUri(), 404);
		return new Response('ERROR: Not found.', 404);
	}

}