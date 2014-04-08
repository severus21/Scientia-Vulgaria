<?php
	chdir('/var/www/scientiavulgaria/Web');
	include_once('../autoload.php');
	
	
	/*
	 * Sphinx 
	 */
		ob_start();
		exec('indexer film --rotate', $output);
		print_r($output);
		exec('indexer ebook --rotate', $output);
		print_r($output);
		exec('indexer software --rotate', $output);
		print_r($output);
		exec('indexer serie --rotate', $output);
	
	$log= new Log();
	$log->log('Cron', 'hourly', ob_get_clean(), Log::GRAN_MONTH);
	
	/*
	 * Frontend
	 */

		//Video
			$videoController=new VideoController();
			$videoController->setManagers(new Managers(PDOFactory::getMysqlConnexion( 'Frontend' )));
			$videoController->executeUpdateStatut();
