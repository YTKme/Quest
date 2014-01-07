<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\DBALException;

class AchievementController implements ControllerInterface {
	
	/**
	 * Constructor
	 */
	public function __construct () {
		
	}
	
	/**
	 * Add achievement
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
				// Create an array to store the achievement created and read
				$achievementArray = array();
					
				foreach ($jsonData as $achievement) {
					// Check if the achievement exist
					if (!$achievementModel = $application['quest.orm.manager']->getRepository('AchievementModel')->findOneBy(array('name' => $achievement['name']))) {
						// Create achievement
						$achievementModel = new AchievementModel(
							NULL, // ID
							empty($achievement['name'])
								? NULL
								: $achievement['name'],
							empty($achievement['description'])
								? NULL
								: $achievement['description'],
							empty($achievement['picture'])
								? NULL
								: $achievement['picture'],
							empty($achievement['latitude'])
								? NULL
								: $achievement['latitude'],
							empty($achievement['longitude'])
								? NULL
								: $achievement['longitude'],
							empty($achievement['point'])
								? 0
								: $achievement['point']
						);
						
						// Check if the game exist
						if (!empty($achievement['game']) && $gameModel = $application['quest.orm.manager']->getRepository('GameModel')->findOneBy(array('id' => $achievement['game']))) {
							$achievementModel->setGame($gameModel);
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
						
						// Store achievement
						$application['quest.orm.manager']->persist($achievementModel);
					}
			
					// Push created and or read achievement into the array
					array_push($achievementArray, $achievementModel->toArray());
				}
					
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to add achievement.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return $application->json($achievementArray, 201);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Retrieve achievement
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
				// Check if the achievement exist
				if ($achievementModels = $application['quest.orm.manager']->getRepository('AchievementModel')->findAll()) {
					// Convert achievement object to array
					foreach ($achievementModels as $key => $value) {
						$achievementModels[$key] = $achievementModels[$key]->toArray();
					}
		
					return $application->json($achievementModels, 200);
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to retrieve achievement.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
		
			return new Response('ERROR: Unable to retrieve achievement.', 404);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Edit achievement
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
				// Create an array to store the achievement updated
				$achievementArray = array();
		
				foreach ($jsonData as $achievement) {
					// Check if the achievement exist
					if ($achievementModel = $application['quest.orm.manager']->getRepository('AchievementModel')->findOneBy(array('id' => $achievement['id']))) {
						// Update achievement
						$achievementModel->setName(
							empty($achievement['name'])
								? $achievementModel->getName()
								: $achievement['name']
						);
						$achievementModel->setDescription(
							empty($achievement['description'])
								? $achievementModel->getDescription()
								: $achievement['description']
						);
						$achievementModel->setPicture(
							empty($achievement['picture'])
								? $achievementModel->getPicture()
								: $achievement['picture']
						);
						$achievementModel->setLatitude(
							empty($achievement['latitude'])
								? $achievementModel->getLatitude()
								: $achievement['latitude']
						);
						$achievementModel->setLongitude(
							empty($achievement['longitude'])
								? $achievementModel->getLongitude()
								: $achievement['longitude']
						);
						$achievementModel->setPoint(
							empty($achievement['point'])
								? $achievementModel->getPoint()
								: $achievement['point']
						);
		
						// Update achievement
						$application['quest.orm.manager']->persist($achievementModel);
		
						// Push updated achievement into the array
						array_push($achievementArray, $achievementModel->toArray());
					}
				}
		
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to edit achievement.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return $application->json($achievementArray, 200);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Remove achievement
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
				// Create an array to store the achievement deleted
				$achievementArray = array();
		
				foreach ($jsonData as $achievement) {
					// Check if the achievement exist
					if ($achievementModel = $application['quest.orm.manager']->getRepository('AchievementModel')->findOneBy(array('id' => $achievement['id']))) {
						// Delete achievement
						$application['quest.orm.manager']->remove($achievementModel);
		
						// Push deleted achievement into the array
						array_push($achievementArray, $achievementModel->toArray());
					}
				}
		
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to delete game.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return $application->json($achievementArray, 200);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
}