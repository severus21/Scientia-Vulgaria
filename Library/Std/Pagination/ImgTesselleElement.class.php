<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class ImgTesselleElement extends TesselleElement{
    /*
        Attributs
    */
        protected $height='';
        protected $width='';
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
		public function buildContent($obj){
			$this->link ? $linkA='<a href="'.$obj->getShowLink(true).'">' : $linkA='';
			$this->link ? $linkB='</a>' : $linkB='';
			return $linkA.'<img src="'.$this->execFunction($obj).'" id="'.$this->id.'" class="'.$this->class.'" width="'.$this->width.'" height="'.$this->height.'"/>'.$linkB;
		}
}
