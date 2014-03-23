<?php

class Token extends Objects{
	/*
        Attributs
    */
		protected $value;
		protected $ttl;
        /*
            Constantes
        */
			const TTL_FORM=36400;

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
		public function isOutdated($lifetime=1800){
			return $this->time<(time()-$lifetime);
		}
		
		public function generateValue(){
			$this->value=String::uniqStr(12);
		}
}
