<?php
/*
 * name: MaxValidator
 * @description : 
 */ 
 
class MaxValidator extends Validator{
    /*
        Attributs
    */
        protected $max;
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        public function __construct($max){
            $this->setMax($max);
        }
        
        /*
            Getters
        */
        
        /*
            Setters
        */
            public function setMax($max){
                if(is_numeric($max)){
                    $this->max= (int)$max;
                    return true;
                }
                return false;
            }
 
    /*
        Autres méthodes
    */  
    
        public function isValid($value){
            return (is_numeric($value) &&  $value<= $this->max);
        }
}
