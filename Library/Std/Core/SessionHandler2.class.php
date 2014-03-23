<?php


class SessionHandler2 {
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
    
        /*
            Setters
        */
    
    /*
        Autres méthodes
    */ 
        public static function verifySession(){
			if(!array_key_exists('REMOTE_ADDR', $_SESSION))
				$_SESSION['REMOTE_ADDR']=$_SERVER['REMOTE_ADDR'];
			elseif($_SESSION['REMOTE_ADDR']!=$_SERVER['REMOTE_ADDR'])
				HTTPReponse::redirect700();
			
			return true;
		}
}
