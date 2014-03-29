<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\DBAL\DBALException;

class TeamController implements ControllerInterface {

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
	
		return $application['twig']->render('team.html.twig', array(
			'_USERNAME' => $sessionUsername
		));
	}
	
	/**
	 * 
	 * @param Request $request
	 * @param Application $application
	 * @param integer $id
	 * @return mixed
	 */
	public function status (Request $request, Application $application, $id) {
		$host = $request->getSchemeAndHttpHost();
		$sessionUsername = $application['session']->get('_USERNAME');
		
		// Validate user login
		if (empty($sessionUsername)) {
			return new RedirectResponse($host);
		}
		
		return $application['twig']->render('team.status.html.twig', array(
			'_USERNAME' => $sessionUsername,
			'id' => $id
		));
	}
	
	/**
	 * Add team
	 *
	 * @method POST
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function add (Request $request, Application $application) {
		// JSON and POST
		if (strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_POST) === 0) {
			// Get JSON data
			if (!$jsonData = json_decode($request->getContent(), true)) {
				return new Response('ERROR: Bad request.', 400);
			}
				
			// Parse JSON data
			$request->request->replace(is_array($jsonData) ? $jsonData : array());
			
			try {
				// Create an array to store the team created and read
				$teamArray = array();
					
				foreach ($jsonData as $team) {
					// Check if the team exist
					if (!$teamModel = $application['quest.orm.manager']->getRepository('TeamModel')->findOneBy(array('name' => $team['name']))) {
						// Create team
						$teamModel = new TeamModel(
							NULL, // ID
							empty($team['name'])
								? NULL
								: $team['name'],
							empty($team['point'])
								? 0
								: $team['point']
						);
						
						// Check if the events exist
						if (!empty($team['events'])) {
							// Loop through each event
							foreach ($team['events'] as $event) {
								// Check if the event exist
								if ($eventModel = $application['quest.orm.manager']->getRepository('EventModel')->findOneBy($event)) {
									$teamModel->addEvent($eventModel);
								}
							}
						}
						
						// Store team
						$application['quest.orm.manager']->persist($teamModel);
					}
					
					// Synchronize with database
					$application['quest.orm.manager']->flush();
					
					// Push created and or read team into the array
					array_push($teamArray, $teamModel->toArray());
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to add team.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return $application->json($teamArray, 201, array(
                        'Access-Control-Allow-Origin' => '*',
                        'Access-Control-Allow-Methods' => ['OPTIONS', 'GET', 'POST'],
                        'Access-Control-Allow-Headers' => 'Content-Type',
                    ));
			
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Retrieve team
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
				// Check if the team exist
				if ($teamModels = $application['quest.orm.manager']->getRepository('TeamModel')->findAll()) {
					// Convert team object to array
					foreach ($teamModels as $key => $value) {
						$teamModels[$key] = $teamModels[$key]->toArray();
					}
		
					return $application->json($teamModels, 200);
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to retrieve team.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
		
			return new Response('ERROR: Unable to retrieve team.', 404);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Retrieve team by ID
	 *
	 * @method GET
	 * @param Request $request
	 * @param Application $application
	 * @param integer $id
	 * @return Response
	 */
	public function retrieveById (Request $request, Application $application, $id) {
		// JSON and GET
		if (strpos($request->headers->get('Content-Type'), 'application/json') === 0 && strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_GET) === 0) {
			try {
				// Check if the team exist
				if ($teamModel = $application['quest.orm.manager']->getRepository('TeamModel')->findOneBy(array('id' => $id))) {
					return $application->json($teamModel->toArray(), 200);
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to retrieve team by ID.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return new Response('ERROR: Unable to retrieve team by ID.', 404);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Retrieve team by name
	 *
	 * @method GET
	 * @param Request $request
	 * @param Application $application
	 * @param string $name
	 * @return Response
	 */
	public function retrieveByName (Request $request, Application $application, $name) {
		// JSON and GET
		if (strpos($request->headers->get('Content-Type'), 'application/json') === 0 && strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_GET) === 0) {
			try {
				// Check if the team exist
				if ($teamModel = $application['quest.orm.manager']->getRepository('TeamModel')->findOneBy(array('name' => $name))) {
					return $application->json($teamModel->toArray(), 200);
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to retrieve team by name.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return new Response('ERROR: Unable to retrieve team by name.', 404);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Edit team
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
				// Create an array to store the team updated
				$teamArray = array();
			
				foreach ($jsonData as $team) {
					// Check if the team exist
					if ($teamModel = $application['quest.orm.manager']->getRepository('TeamModel')->findOneBy(array('id' => $team['id']))) {
						// Update team
						$teamModel->setName(
							empty($team['name'])
								? $teamModel->getName()
								: $team['name']
						);
						$teamModel->setPoint(
							empty($team['point'])
								? $teamModel->getPoint()
								: $team['point']
						);
			
						// Update team
						$application['quest.orm.manager']->persist($teamModel);
			
						// Push updated team into the array
						array_push($teamArray, $teamModel->toArray());
					}
				}
				
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to edit team.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return $application->json($teamArray, 200);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Remove team
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
				// Create an array to store the team deleted
				$teamArray = array();
			
				foreach ($jsonData as $team) {
					// Check if the team exist
					if ($teamModel = $application['quest.orm.manager']->getRepository('TeamModel')->findOneBy(array('id' => $team['id']))) {
						// Delete team
						$application['quest.orm.manager']->remove($teamModel);
			
						// Push deleted team into the array
						array_push($teamArray, $teamModel->toArray());
					}
				}
			
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to delete team.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return $application->json($teamArray, 200);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
}
