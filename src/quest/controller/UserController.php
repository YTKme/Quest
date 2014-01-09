<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\DBALException;

class UserController implements ControllerInterface {

	/**
	 * Constructor
	 */
	public function __construct () {

	}
	
	/**
	 * Add user
	 *
	 * @method POST
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function add (Request $request, Application $application) {
		
	}
	
	/**
	 * Retrieve user
	 *
	 * @method GET
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	*/
	public function retrieve (Request $request, Application $application) {
		
	}
	
	/**
	 * Edit user
	 *
	 * @method PUT
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	*/
	public function edit (Request $request, Application $application) {
		
	}
	
	/**
	 * Remove user
	 *
	 * @method DELETE
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	*/
	public function remove (Request $request, Application $application) {
		
	}
	
}