<?php
/*
 * name: RequiredValidator
 * @description : 
 */ 
 
class RequiredValidator extends Validator{
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
            return $value != '';
        }
}

