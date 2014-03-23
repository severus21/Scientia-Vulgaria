<?php
/*
 * name: EmailValidator
 * @description : 
 */ 
 
class EmailValidator extends Validator{
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
        public function isValid($value){
            if(isset($value) && preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $value)){
                return true;
            }else{
                return false;
            }
        }
}

