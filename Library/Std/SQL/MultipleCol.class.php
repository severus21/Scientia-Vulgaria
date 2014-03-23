<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class MultipleCol extends Col{
    /*
        Attributs
    */
		protected $comparisonOperator='IN';
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
				return trim($this->value);
			}
        /*
            Setters
		*/
    
    /*
        Autres méthodes
    */
		public function genRequete($tableO=''){
			empty($this->table) ? $this->setTable($tableO) : null; //Compatibilité ascendante
			return ' '.$this->logicalOperator.' '.$this->table.'.'.$this->name.' '.$this->comparisonOperator.' ( '.mysql_real_escape_string($this->value).' ) ';
		}
}
