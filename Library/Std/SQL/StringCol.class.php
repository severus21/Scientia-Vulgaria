<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class StringCol extends Col{
    /*
        Attributs
    */
		protected $comparisonOperator='LIKE';
        protected $strict=false;
        /*
            Constantes
        */
        
    /*
        Méthodes générales
    */
        /*
            Getters
        */
            public function getValue(){
                if($this->strict)
                    return trim($this->value);
                else
                    return '%'.trim($this->value).'%';
            }
        
            public function getStrict(){
                return $this->strict;
            }
        /*
            Setters
        */
            public function setStrict($s){
                if(is_bool($s)){
                    $this->strict=$s;
                }else{
                    throw new RuntimeException('strict must be a booleen');
                }
            }
    /*
        Autres méthodes
    */
        
}
