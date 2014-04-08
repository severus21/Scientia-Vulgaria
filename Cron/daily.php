<?php
	chdir('/var/www/scientiavulgaria/Web');
	include_once('../autoload.php');
	
	/*
	 * Frontend 
	 */
		//TmpFile
			$TmpfileController=new TmpfileController();
			$TmpfileController->setManagers(new Managers(PDOFactory::getMysqlConnexion( 'Frontend' )));
			$TmpfileController->executeDelete();
	
	
