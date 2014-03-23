<?php
/*
 * name: Cryptographie
 * @description :  gestion de toutes le protocole de de chiffrage de donées
 */

class Cryptographie{
    /*
        Attributs
    */

        /*
            Constantes
        */
            const SHA512='zA6fySZ47d7ePo9x';
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
		public static function randomPassword($length=8){	
			return exec('pwgen -c -n -y '.$length.' 1');
		}
		
        public static function str2SHA512($str, $key=self::SHA512){
            $a='$6$rounds=10000$'.$key.'$';
            return crypt($str, $a);
        }
}
