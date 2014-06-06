<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UploadController {

	/**
	 * Constructor
	 */
	public function __construct () {

	}

	/**
	 * Main
	 * 
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function main (Request $request, Application $application) {
		$host = $request->getSchemeAndHttpHost();
		$sessionUsername = $application['session']->get('_USERNAME');
		
		// Validate user login
// 		if (empty($sessionUsername)) {
// 			return new RedirectResponse($host);
// 		}
		
		return $application['twig']->render('upload.html.twig', array(
			'_USERNAME' => $sessionUsername
		));
	}
	
	/**
	 * Process
	 * 
	 * @param Request $request
	 * @param Application $application
	 * @return Response
	 */
	public function process (Request $request, Application $application) {
		$uploadDirectory = __DIR__ . '/../../../web/dat';
		$uploadUrl = $request->getSchemeAndHttpHost() . '/upload';
		
		set_time_limit(7200);
		
		// POST
		if (strpos($request->getMethod(), ControllerInterface::HTTP_METHOD_POST) === 0) {
			
			try {
				
				// Get the uploaded file
				$file = $request->files->get('datPicture');
				
				// Validate file
				if ($file === NULL) {
					new Response('ERROR: No file.', 500);
				}
				
				// Get extension
				$fileExtension = strrchr($file->getClientOriginalName(), '.');
				
				// Validate extension
				if ($fileExtension !== '.jpg' && $fileExtension !== '.png' && $fileExtension !== '.gif') {
					new Response('ERROR: Not an image.', 500);
				}
				
				// Move the file to directory (will overwrite existing file)
				$file->move($uploadDirectory, $file->getClientOriginalName());
				
				// File location
				$fileLocation = $uploadDirectory . '/' . $file->getClientOriginalName();
				
			} catch (FileException $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			} catch (Exception $exception) {
				return
					$application['debug']
						? new Response('Exception: ' . $exception->getMessage(), 500)
						: new Response('ERROR: Failure.', 500);
			}
			
			return new Response('SUCCESS: Upload complete.', 200);
			
		}
		
		return new Response('ERROR: Bad request.', 400);
	}

}