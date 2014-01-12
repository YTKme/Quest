<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\DBAL\DBALException;

class TeamAchievementController implements ControllerInterface {

	/**
	 * Constructor
	 */
	public function __construct () {

	}
	
	/**
	 * Add
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
				// Create an array to store the team achievement created and read
				$teamAchievementArray = array();
					
				foreach ($jsonData as $teamAchievement) {
					// Check if the team achievement exist
					if (!$teamAchievementModel = $application['quest.orm.manager']->getRepository('TeamAchievementModel')->findOneBy(array('team' => $teamAchievement['team'], 'achievement' => $teamAchievement['achievement']))) {
						// Create team achievement
						$teamAchievementModel = new TeamAchievementModel(
							NULL, // ID
							NULL, // Team ID
							NULL, // Achievement ID
							empty($teamAchievement['picture'])
								? NULL
								: $teamAchievement['picture']
						);
						
						// Check if the team exist
						if (!empty($teamAchievement['team']) && !empty($teamAchievement['achievement']) &&
							($teamModel = $application['quest.orm.manager']->getRepository('TeamModel')->findOneBy(array('id' => $teamAchievement['team']))) &&
							($achievementModel = $application['quest.orm.manager']->getRepository('AchievementModel')->findOneBy(array('id' => $teamAchievement['achievement'])))) {
							
							$teamAchievementModel->setTeam($teamModel);
							$teamAchievementModel->setAchievement($achievementModel);
						}
			
						// Store team achievement
						$application['quest.orm.manager']->persist($teamAchievementModel);
					}
						
					// Push created and or read team achievement into the array
					array_push($teamAchievementArray, $teamAchievementModel->toArray());
				}
					
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to add team achievement.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return $application->json($teamAchievementArray, 201);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
	/**
	 * Retrieve
	 *
	 * @method GET
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	*/
	public function retrieve (Request $request, Application $application) {
		
	}
	
	/**
	 * Edit
	 *
	 * @method PUT
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	*/
	public function edit (Request $request, Application $application) {
		
	}
	
	/**
	 * Remove
	 *
	 * @method DELETE
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	*/
	public function remove (Request $request, Application $application) {
		
	}
	
}