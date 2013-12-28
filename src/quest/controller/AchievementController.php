<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
						// Create game
						$achievementModel = new AchievementModel(
								NULL, // ID
								empty($achievement['code'])
								? NULL
								: $achievement['code'],
								empty($achievement['name'])
								? NULL
								: $achievement['name'],
								empty($achievement['description'])
								? NULL
								: $achievement['description'],
								empty($achievement['start'])
								? NULL
								: $achievement['start'],
								empty($achievement['length'])
								? NULL
								: $achievement['length'],
								empty($achievement['location'])
								? NULL
								: $achievement['location']
						);
			
						// Ensure game code is unique
						while (!empty($application['quest.orm.manager']->getRepository('AchievementModel')->findOneBy(array('code' => $achievementModel->getCode())))) {
							$achievementModel->setCode();
						}
							
						// Store game
						$application['quest.orm.manager']->persist($achievementModel);
					}
			
					// Push created and or read game into the array
					array_push($achievementArray, $achievementModel->toArray());
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
		
	}
	
}