<?php

class TokenRecord extends Record{
	/*
        Attributs
    */
		protected $value;
		protected $time;
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
				return $this->value;
			}
			
			public function getTime(){
				return $this->time; 
			}
        /*
            Setters
        */
			public function setValue($v){
				$this->value=$v;
			}
			
			public function setTime($t){
				$this->time=$t;
			}
    /*
        Autres méthodes
    */
		public function isValid(){
			return !empty($this->value);
		}
}
