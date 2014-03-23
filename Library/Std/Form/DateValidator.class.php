<?php
/*
 * name: DateValidator
 * @description : 
 */ 
 
class DateValidator extends Validator{
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
            return preg_match('#^\d{4}-\d{2}-\d{2}$#', $value);
        }
}

