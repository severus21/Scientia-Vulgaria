<?php
	require '../Library/Extras/sphinxapi.php';

class SphinxFactory{
    /*
        Attributs
    */

        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        /*
            Getters
        */
            public static function getSphinxClient(){
				$sphinx = new SphinxClient;
    
				$sphinx->setServer('localhost', 9312);	
				$sphinx->setMatchMode(SPH_MATCH_EXTENDED2);
				$sphinx->setConnectTimeout(1);
				
                return $sphinx;
            }
        /*
            Setters
        */
    
    /*
        Autres méthodes
    */
} 
