<?php
/*
 * 
 * name: Application
 * @description permet :
 *           d'obtenir l'application à laquelle l'objet appartient.
 * 
 */

abstract class ApplicationComponent{
    /*
        Attributs
    */
        protected $app;
        
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        public function __construct( $app=null){
            if(isset($app) & $app instanceof Application){
                $this->app = $app;
            }
        }
        
        /*
            Getters
        */
            public function getApp(){
                return $this->app;
            }
        /*
            Setters
        */
    
    /*
        Autres méthodes
    */
}
