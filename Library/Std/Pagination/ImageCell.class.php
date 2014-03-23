<?php

class ImageCell extends Cell{
    /*
        Attributs
    */
		protected $height;
		protected $width;
		protected $alt;
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
			public function setAlt($a){
				$this->alt=$a;
			}
        
			public function setHeight($h){
				if(is_numeric($h)){
					$this->height=(int)$h;
				}else{
					throw new RuntimeException('height must be an integer');
				}
			}
			
			public function setWidth($w){
				if(is_numeric($w)){
					$this->width=(int)$w;
				}else{
					throw new RuntimeException('width must be an integer');
				}
			}
    /*
        Autres méthodes
    */
		protected function showContent($obj){
			return '<img src="'.$this->execFunction($obj).'" alt="'.$this->alt.'" height="'.$this->height.'" width="'.$this->width.'"/>
			';
		}
}
