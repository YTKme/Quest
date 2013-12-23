<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface ControllerInterface {
	
	/**
	 * Constant
	 */
	const HTTP_METHOD_GET = 'GET';
	const HTTP_METHOD_HEAD = 'HEAD';
	const HTTP_METHOD_POST = 'POST';
	const HTTP_METHOD_PUT = 'PUT';
	const HTTP_METHOD_DELETE = 'DELETE';
	const HTTP_METHOD_TRACE = 'TRACE';
	const HTTP_METHOD_OPTIONS = 'OPTIONS';
	const HTTP_METHOD_CONNECT = 'CONNECT';
	const HTTP_METHOD_PATCH = 'PATCH';
	
	/**
	 * Add
	 * 
	 * @method POST
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function add (Request $request, Application $application);
	
	/**
	 * Retrieve
	 * 
	 * @method GET
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function retrieve (Request $request, Application $application);
	
	/**
	 * Edit
	 * 
	 * @method PUT
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function edit (Request $request, Application $application);
	
	/**
	 * Remove
	 * 
	 * @method DELETE
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function remove (Request $request, Application $application);
	
}