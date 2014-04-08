<?php
	session_start();
  
  
    empty( $_SESSION['lang'] ) ? $_SESSION['lang']='fr' :null;
    require_once '../autoload.php';
    
    $app = new FrontendApplication;
    $app->run();
