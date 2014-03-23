<?php

class TextCell extends Cell{
    /*
        Attributs
    */
        protected $maxLength=50;
        /*
            Constantes
        */
    /*
        Méthodes générales
    */ 
        /*
            Getters
        */
            public function getMaxLength(){
                return $this->maxLength;
            }
        /*
            Setters
        */
            public function setMaxLength($s){
                if(isset($s) && $s>0){
                    $this->maxLength=$s;
                }else{
                    throw new RuntimeException('maxLength must be a positive integer');
                }
            }
    /*
        Autres méthodes
    */
        public function showContent($obj){
			$tmp=$this->execFunction($obj);
			return substr($tmp,0, ($this->maxLength<strlen($tmp)) ? $this->maxLength : strlen($tmp));
        }
}
