<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\DBALException;

class GameController implements ControllerInterface {

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
			//return new RedirectResponse($host);
		}
	
		return $application['twig']->render('game.html.twig', array(
			'_USERNAME' => $sessionUsername
		));
	}
	
	/**
	 * Add game
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
				// Create an array to store the game created and read
				$gameArray = array();
			
				foreach ($jsonData as $game) {
					// Check if the game exist
					if (!$gameModel = $application['quest.orm.manager']->getRepository('GameModel')->findOneBy(array('name' => $game['name']))) {
						// Create game
						$gameModel = new GameModel(
							NULL, // ID
							empty($game['name'])
								? NULL
								: $game['name'],
							empty($game['description'])
								? NULL
								: $game['description'],
							empty($game['location'])
								? NULL
								: $game['location']
						);
						
						// Check if the achievements exist
						if (!empty($game['achievements'])) {
							// Loop through each achievement
							foreach ($game['achievements'] as $achievement) {
								// Check if the achievement exist
								if ($achievementModel = $application['quest.orm.manager']->getRepository('AchievementModel')->findOneBy($achievement)) {
									$achievementModel->setGame($gameModel);
								}
							}
						}
							
						// Store game
						$application['quest.orm.manager']->persist($gameModel);
					}
						
					// Push created and or read game into the array
					array_push($gameArray, $gameModel->toArray());
				}
			
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to add game.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return $application->json($gameArray, 201);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Retrieve game
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
				// Check if the game exist
				if ($gameModels = $application['quest.orm.manager']->getRepository('GameModel')->findAll()) {
					// Convert game object to array
					foreach ($gameModels as $key => $value) {
						$gameModels[$key] = $gameModels[$key]->toArray();
					}
						
					return $application->json($gameModels, 200);
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to retrieve game.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return new Response('ERROR: Unable to retrieve game.', 404);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Retrieve game by ID
	 *
	 * @method GET
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function retrieveById (Request $request, Application $application, $id) {
		// JSON and GET
		if (strpos($request->headers->get('Content-Type'), 'application/json') === 0 && strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_GET) === 0) {
			try {
				// Check if the game exist
				if ($gameModel = $application['quest.orm.manager']->getRepository('GameModel')->findOneBy(array('id' => $id))) {
					return $application->json($gameModel->toArray(), 200);
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to retrieve game by ID.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return new Response('ERROR: Unable to retrieve game by ID.', 404);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Edit game
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
				// Create an array to store the game updated
				$gameArray = array();
				
				foreach ($jsonData as $game) {
					// Check if the game exist
					if ($gameModel = $application['quest.orm.manager']->getRepository('GameModel')->findOneBy(array('id' => $game['id']))) {
						// Update game
						$gameModel->setName(
							empty($game['name'])
								? $gameModel->getName()
								: $game['name']
						);
						$gameModel->setDescription(
							empty($game['description'])
								? $gameModel->getDescription()
								: $game['description']
						);
						$gameModel->setLocation(
							empty($game['location'])
								? $gameModel->getLocation()
								: $game['location']
						);
						
						// Update game
						$application['quest.orm.manager']->persist($gameModel);

						// Push updated game into the array
						array_push($gameArray, $gameModel->toArray());
					}
				}
				
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to edit game.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return $application->json($gameArray, 200);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Remove game
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
				// Create an array to store the game deleted
				$gameArray = array();
				
				foreach ($jsonData as $game) {
					// Check if the game exist
					if ($gameModel = $application['quest.orm.manager']->getRepository('GameModel')->findOneBy(array('id' => $game['id']))) {
						// Delete game
						$application['quest.orm.manager']->remove($gameModel);
						
						// Push deleted game into the array
						array_push($gameArray, $gameModel->toArray());
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
			
			return $application->json($gameArray, 200);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}

}