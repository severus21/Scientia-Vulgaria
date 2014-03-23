<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class BlockTesselleElement extends TesselleElement{
    /*
        Attributs
    */
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
			return '<div id="'.$this->id.'" class="'.$this->class.'"></div>';
		}
}
