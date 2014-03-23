<?php
/*
 * name: MaxLengthValidator
 * @description : 
 */ 
 
class MaxLengthValidator extends Validator{
    /*
        Attributs
    */
        protected $maxLength;
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        public function __construct($maxLength){
            $this->setMaxLength($maxLength);
        }
        
        /*
            Getters
        */
        
        /*
            Setters
        */
            public function setMaxLength($maxLength){
                $maxLength = (int) $maxLength;
                if($maxLength>0){
                    $this->maxLength = $maxLength;
                }else{
                    throw new RuntimeRuntimeException('La longueur maximale doit être un nombre supérieur à 0');
                }
            }
 
    /*
        Autres méthodes
    */  
    
        public function isValid($value){
            return strlen($value) <= $this->maxLength;
        }

}
