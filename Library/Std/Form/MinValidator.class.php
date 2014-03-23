<?php
/*
 * name: MinValidator
 * @description : 
 */ 
 
class MinValidator extends Validator{
    /*
        Attributs
    */
        protected $min;
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        public function __construct($min){
            $this->setMin($min);
        }
        
        /*
            Getters
        */
        
        /*
            Setters
        */
            public function setMin($min){
                if(is_numeric($min)){
                    $this->min= (int)$min;
                    return true;
                }
                return false;
            }
 
    /*
        Autres méthodes
    */  
    
        public function isValid($value){
            return (is_numeric($value) &&  $value>= $this->min);
        }
}
