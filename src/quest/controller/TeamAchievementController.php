<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
		if (strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_POST) === 0) {
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
					
					// Synchronize with database
					$application['quest.orm.manager']->flush();
					
					// Push created and or read team achievement into the array
					array_push($teamAchievementArray, $teamAchievementModel->toArray());
				}
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
			
			return $application->json($teamAchievementArray, 201, array(
                        'Access-Control-Allow-Origin' => '*',
                        'Access-Control-Allow-Methods' => ['OPTIONS', 'GET', 'POST'],
                        'Access-Control-Allow-Headers' => 'Content-Type',
                    ));
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
		// JSON and GET
		if (strpos($request->headers->get('Content-Type'), 'application/json') === 0 && strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_GET) === 0) {
			try {
				// Check if the team achievement exist
				if ($teamAchievementModels = $application['quest.orm.manager']->getRepository('TeamAchievementModel')->findAll()) {
					// Convert team achievement object to array
					foreach ($teamAchievementModels as $key => $value) {
						$teamAchievementModels[$key] = $teamAchievementModels[$key]->toArray();
					}
			
					return $application->json($teamAchievementModels, 200);
				}
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to retrieve team achievement.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return new Response('ERROR: Unable to retrieve team achievement.', 404);
		}
		
		return new Response('ERROR: Bad request.', 400);
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
		// JSON and PUT
		if (strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_PUT) === 0) {
			// Get JSON data
			if (!$jsonData = json_decode($request->getContent(), true)) {
				return new Response('ERROR: Bad request.', 400, array(
                    'Message' => 'Foo'
                ));
			}
		
			// Parse JSON data
			$request->request->replace(is_array($jsonData) ? $jsonData : array());
			
			try {
				// Create an array to store the team achievement updated
				$teamAchievementArray = array();
					
				foreach ($jsonData as $teamAchievement) {
					// Check if the team achievement exist
					if ($teamAchievementModel = $application['quest.orm.manager']->getRepository('TeamAchievementModel')->findOneBy(array('id' => $teamAchievement['id']))) {
						// Update team achievement
						$teamAchievementModel->setPicture(
							empty($teamAchievement['picture'])
								? $teamAchievementModel->getPicture()
								: $teamAchievement['picture']
						);
						
						// Check if the team exist
						if (!empty($teamAchievement['team']) && !empty($teamAchievement['achievement']) &&
							($teamModel = $application['quest.orm.manager']->getRepository('TeamModel')->findOneBy(array('id' => $teamAchievement['team']))) &&
							($achievementModel = $application['quest.orm.manager']->getRepository('AchievementModel')->findOneBy(array('id' => $teamAchievement['achievement'])))) {
							$teamAchievementModel->setTeam($teamModel);
							$teamAchievementModel->setAchievement($achievementModel);
						}
							
						// Update team achievement
						$application['quest.orm.manager']->persist($teamAchievementModel);
							
						// Push updated team achievement into the array
						array_push($teamAchievementArray, $teamAchievementModel->toArray());
					}
				}
				
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to edit team achievement.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return $application->json($teamAchievementArray, 200, array(
                    'Access-Control-Allow-Origin' => '*',
                    'Access-Control-Allow-Methods' => ['OPTIONS', 'PUT'],
                    'Access-Control-Allow-Headers' => ['Accept', 'Content-Type'],
            ));
		}
		
		return new Response('ERROR: Bad request.', 400);
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
				$teamAchievementArray = array();
					
				foreach ($jsonData as $teamAchievement) {
					// Check if the team achievement exist
					if ($teamAchievementModel = $application['quest.orm.manager']->getRepository('TeamAchievementModel')->findOneBy(array('id' => $teamAchievement['id']))) {
						// Delete team achievement
						$application['quest.orm.manager']->remove($teamAchievementModel);
							
						// Push deleted team achievement into the array
						array_push($teamAchievementArray, $teamAchievementModel->toArray());
					}
				}
					
				// Synchronize with database
				$application['quest.orm.manager']->flush();
			} catch (DBALException $exception) {
				return
					$application['debug']
						? new Response('DBAL Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Unable to delete team achievement.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
				
			return $application->json($teamAchievementArray, 200);
		}
		
		return new Response('ERROR: Bad request.', 400);
	}
	
}