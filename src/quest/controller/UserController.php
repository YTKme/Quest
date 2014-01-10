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
		// JSON and POST
		if (strpos($request->headers->get('Content-Type'), 'application/json') === 0 && strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_POST) === 0) {
			// Get JSON data
			if (!$jsonData = json_decode($request->getContent(), true)) {
				return new Response('ERROR: Bad request.', 400);
			}
		
			// Parse JSON data
			$request->request->replace(is_array($jsonData) ? $jsonData : array());
			
			try {
				// Create an array to store the user created and read
				$userArray = array();
					
				foreach ($jsonData as $user) {
					// Check if the user exist
					if (!$userModel = $application['quest.orm.manager']->getRepository('UserModel')->findOneBy(array('username' => $user['username']))) {
						// Create user
						$userModel = new UserModel(
							NULL, // ID
							empty($user['username'])
								? NULL
								: $user['username'],
							empty($user['password'])
								? NULL
								: $user['password'],
							empty($user['role'])
								? NULL
								: $user['role']
						);
			
						// Store user
						$application['quest.orm.manager']->persist($userModel);
					}
						
					// Push created and or read user into the array
					array_push($userArray, $userModel->toArray());
				}
					
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to add user.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return $application->json($userArray, 201);
		}
		
		return new Response('ERROR: Bad request.', 400);
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
		// JSON and GET
		if (strpos($request->headers->get('Content-Type'), 'application/json') === 0 && strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_GET) === 0) {
			try {
				// Check if the user exist
				if ($userModels = $application['quest.orm.manager']->getRepository('UserModel')->findAll()) {
					// Convert user object to array
					foreach ($userModels as $key => $value) {
						$userModels[$key] = $userModels[$key]->toArray();
					}
			
					return $application->json($userModels, 200);
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to retrieve user.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return new Response('ERROR: Unable to retrieve user.', 404);
		}
		
		return new Response('ERROR: Bad request.', 400);
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
		// JSON and PUT
		if (strpos($request->headers->get('Content-Type'), 'application/json') === 0 && strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_PUT) === 0) {
			// Get JSON data
			if (!$jsonData = json_decode($request->getContent(), true)) {
				return new Response('ERROR: Bad request.', 400);
			}
		
			// Parse JSON data
			$request->request->replace(is_array($jsonData) ? $jsonData : array());
			
			try {
				// Create an array to store the user updated
				$userArray = array();
					
				foreach ($jsonData as $user) {
					// Check if the user exist
					if ($userModel = $application['quest.orm.manager']->getRepository('UserModel')->findOneBy(array('id' => $user['id']))) {
						// Update user
						$userModel->setUsername(
							empty($user['username'])
								? $userModel->getUsername()
								: $user['username']
						);
						$userModel->setPassword(
							empty($user['password'])
								? $userModel->getPassword()
								: $user['password']
						);
						$userModel->setRole(
							empty($user['role'])
								? $userModel->getRole()
								: $user['role']
						);
							
						// Update user
						$application['quest.orm.manager']->persist($userModel);
							
						// Push updated user into the array
						array_push($userArray, $userModel->toArray());
					}
				}
					
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to edit user.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return $application->json($userArray, 200);
		}
		
		return new Response('ERROR: Bad request.', 400);
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
		// JSON and DELETE
		if (strpos($request->headers->get('Content-Type'), 'application/json') === 0 && strpos($request->getMethod(), 'DELETE') === 0) {
			// Get JSON data
			if (!$jsonData = json_decode($request->getContent(), true)) {
				return new Response('ERROR: Bad request.', 400);
			}
		
			// Parse JSON data
			$request->request->replace(is_array($jsonData) ? $jsonData : array());
			
			try {
				// Create an array to store the user deleted
				$userArray = array();
					
				foreach ($jsonData as $user) {
					// Check if the user exist
					if ($userModel = $application['quest.orm.manager']->getRepository('UserModel')->findOneBy(array('id' => $user['id']))) {
						// Delete user
						$application['quest.orm.manager']->remove($userModel);
							
						// Push deleted user into the array
						array_push($userArray, $userModel->toArray());
					}
				}
					
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to delete user.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return $application->json($userArray, 200);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
}