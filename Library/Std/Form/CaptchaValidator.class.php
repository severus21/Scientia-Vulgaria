<?php
/*
 * name: CaptchaValidator
 * @description : 
 */ 
 
class CaptchaValidator extends Validator{
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
        public function isValid($value, $captcha){
            return $value == $captcha->getCode();
        }
}

