<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
    Description :
        gérer les différents managers.
*/

class Managers{
    /*
        Attributs
    */
        protected $dao = null;
        protected $managers = array();
        
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        public function __construct($dao){
            $this->dao = $dao;
        }
        
        /*
            Getters
        */
            public function getManagerOf($module){
                if (!is_string($module) || empty($module)){
                    throw new  InvalidArgumentException('Le module spécifié est invalide :'.$module);
                }
            
                if (!isset($this->managers[$module])){
                    $manager = ucfirst($module).'Manager';
                    $this->managers[$module] = new $manager($this->dao);
                }
            
                return $this->managers[$module];
            }
            
        /*
            Setters
        */
    
    /*
        Autres méthodes
    */
}
