<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class TextTesselleElement extends TesselleElement{
    /*
        Attributs
    */
        protected $value='';
        protected $maxLength=256;
        protected $prefix='';
        protected $postfix='';
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
			public function setValue($v){
				if(strlen($v)>$this->maxLength)
					$this->value=substr($v, 0, ($this->maxLength-3)).'...';
				else
					$this->value=$v;
            }
            
            public function setMaxLength($m){
                if(is_numeric($m)){
                    $this->maxLength=(int)$m;
                }else{
                    throw new RuntimeException('value must be an integer');
                }
            }
            
            public function setPrefix($p){
				$this->prefix = (is_string($p)) ? $p : $this->prefix;	
			}
			
            public function setPostfix($p){
				$this->postfix = (is_string($p)) ? $p : $this->postfix;	
			}
    /*
        Autres méthodes
    */
		public function buildContent($obj){
			$this->setValue((string)$this->execFunction($obj));
		
			return '<p id="'.$this->id.'" class="'.$this->class.'">
						'.$this->prefix.$this->value.$this->postfix.'
					</p>';
		}
}
