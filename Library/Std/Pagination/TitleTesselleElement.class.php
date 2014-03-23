<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class TitleTesselleElement extends TextTesselleElement{
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

        /*
            Setters
        */
    /*
        Autres méthodes
    */
		public function buildContent($obj){
			$this->setValue((string)$this->execFunction($obj));
			$this->link ? $linkA='<a href="'.$obj->getShowLink(true).'">' : $linkA='';
			$this->link ? $linkB='</a>' : $linkB='';
			return $linkA.'<h4 id="'.$this->id.'" class="'.$this->class.'">'.$this->prefix.$this->value.$this->postfix.'</h4>'.$linkB;
		}
}
