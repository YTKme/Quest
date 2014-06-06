<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\DBAL\DBALException;

class AchievementController implements ControllerInterface {
	
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
			//return new RedirectResponse($host);
		}
	
		return $application['twig']->render('achievement.html.twig', array(
			'_USERNAME' => $sessionUsername
		));
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
		// POST
		if (strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_POST) === 0) {
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
							empty($achievement['icon'])
								? NULL
								: $achievement['icon'],
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
					
					// Synchronize with database
					$application['quest.orm.manager']->flush();
					
					// Push created and or read achievement into the array
					array_push($achievementArray, $achievementModel->toArray());
				}
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
		// GET
		if (strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_GET) === 0) {
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
	 * Retrieve achievement by ID
	 *
	 * @method GET
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function retrieveById (Request $request, Application $application, $id) {
		// GET
		if (strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_GET) === 0) {
			try {
				// Check if the achievement exist
				if ($achievementModel = $application['quest.orm.manager']->getRepository('AchievementModel')->findOneBy(array('id' => $id))) {
					return $application->json($achievementModel->toArray(), 200);
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to retrieve achievement by ID.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return new Response('ERROR: Unable to retrieve achievement by ID.', 404);
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
		// PUT
		if (strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_PUT) === 0) {
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
						$achievementModel->setIcon(
							empty($achievement['icon'])
								? $achievementModel->getIcon()
								: $achievement['icon']
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
		// DELETE
		if (strpos($request->getMethod(), 'DELETE') === 0) {
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