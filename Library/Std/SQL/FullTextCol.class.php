<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class FullTextCol extends Col{
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
        public function genRequete($tableO=''){
            empty($this->table) ? $this->setTable($tableO) : null; //Compatibilité ascendante
            return ' '.$this->logicalOperator.' MATCH('.$this->table.'.'.$this->name.') AGAINST ( ? ) ';
        }
}
