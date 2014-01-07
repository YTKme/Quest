<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\DBALException;

class EventController implements ControllerInterface {

	/**
	 * Constructor
	 */
	public function __construct () {

	}
	
	/**
	 * Add event
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
				// Create an array to store the event created and read
				$eventArray = array();
			
				foreach ($jsonData as $event) {
					// Check if the event exist
					if (!$eventModel = $application['quest.orm.manager']->getRepository('EventModel')->findOneBy(array('name' => $event['name']))) {
						// Create event
						$eventModel = new EventModel(
							NULL, // ID
							empty($event['code'])
								? NULL
								: $event['code'],
							empty($event['name'])
								? NULL
								: $event['name'],
							empty($event['description'])
								? NULL
								: $event['description'],
							empty($event['start'])
								? NULL
								: $event['start'],
							empty($event['length'])
								? NULL
								: $event['length']
						);
						
						// Ensure event code is unique
						while (!empty($application['quest.orm.manager']->getRepository('EventModel')->findOneBy(array('code' => $eventModel->getCode())))) {
							$eventModel->setCode();
						}
						
						// Check if the game exist
						if (!empty($event['game']) && $gameModel = $application['quest.orm.manager']->getRepository('GameModel')->findOneBy(array('id' => $event['game']))) {
							$eventModel->setGame($gameModel);
						}
						
						// Check if the teams exist
						if (!empty($event['teams'])) {
							// Loop through each team
							foreach ($event['teams'] as $team) {
								// Check if the team exist
								if ($teamModel = $application['quest.orm.manager']->getRepository('TeamModel')->findOneBy($team)) {
									$eventModel->addTeam($teamModel);
								}
							}
						}
							
						// Store event
						$application['quest.orm.manager']->persist($eventModel);
					}
						
					// Push created and or read event into the array
					array_push($eventArray, $eventModel->toArray());
				}
			
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to add event.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return $application->json($eventArray, 201);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Retrieve event
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
				// Check if the event exist
				if ($eventModels = $application['quest.orm.manager']->getRepository('EventModel')->findAll()) {
					// Convert event object to array
					foreach ($eventModels as $key => $value) {
						$eventModels[$key] = $eventModels[$key]->toArray();
					}
	
					return $application->json($eventModels, 200);
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to retrieve event.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
	
			return new Response('ERROR: Unable to retrieve event.', 404);
		}
	
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Retrieve event by code
	 *
	 * @method GET
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function retrieveByCode (Request $request, Application $application, $code) {
		// JSON and GET
		if (strpos($request->headers->get('Content-Type'), 'application/json') === 0 && strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_GET) === 0) {
			try {
				// Check if the event exist
				if ($eventModel = $application['quest.orm.manager']->getRepository('EventModel')->findOneBy(array('code' => $code))) {
					return $application->json($eventModel->toArray(), 200);
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to retrieve event.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return new Response('ERROR: Unable to retrieve event.', 404);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Edit event
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
				// Create an array to store the event updated
				$eventArray = array();
	
				foreach ($jsonData as $event) {
					// Check if the event exist
					if ($eventModel = $application['quest.orm.manager']->getRepository('EventModel')->findOneBy(array('id' => $event['id']))) {
						// Update event
						$eventModel->setCode(
							empty($event['code'])
								? $eventModel->getCode()
								: $event['code']
						);
						$eventModel->setName(
							empty($event['name'])
								? $eventModel->getName()
								: $event['name']
						);
						$eventModel->setDescription(
							empty($event['description'])
								? $eventModel->getDescription()
								: $event['description']
						);
						$eventModel->setStart(
							empty($event['start'])
								? $eventModel->getStart()
								: $event['start']
						);
						$eventModel->setLength(
							empty($event['length'])
								? $eventModel->getLength()
								: $event['length']
						);
						
						// Check if the game exist
						if ($gameModel = $application['quest.orm.manager']->getRepository('GameModel')->findOneBy(array('id' => $event['game']))) {
							$eventModel->setGame($gameModel);
						}
	
						// Update event
						$application['quest.orm.manager']->persist($eventModel);

						// Push updated event into the array
						array_push($eventArray, $eventModel->toArray());
					}
				}
	
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to edit event.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return $application->json($eventArray, 200);
		}
	
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Remove event
	 *
	 * @method DELETE
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function remove(Request $request, Application $application) {
		// JSON and DELETE
		if (strpos($request->headers->get('Content-Type'), 'application/json') === 0 && strpos($request->getMethod(), 'DELETE') === 0) {
			// Get JSON data
			if (!$jsonData = json_decode($request->getContent(), true)) {
				return new Response('ERROR: Bad request.', 400);
			}
				
			// Parse JSON data
			$request->request->replace(is_array($jsonData) ? $jsonData : array());
				
			try {
				// Create an array to store the event deleted
				$eventArray = array();
	
				foreach ($jsonData as $event) {
					// Check if the event exist
					if ($eventModel = $application['quest.orm.manager']->getRepository('EventModel')->findOneBy(array('code' => $event['code']))) {
						// Delete event
						$application['quest.orm.manager']->remove($eventModel);

						// Push deleted event into the array
						array_push($eventArray, $eventModel->toArray());
					}
				}
	
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to delete event.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return $application->json($eventArray, 200);
		}
	
		return new Response('ERROR: Bad request.', 400);
	}
	
}