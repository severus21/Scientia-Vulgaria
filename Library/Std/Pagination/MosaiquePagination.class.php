<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class MosaiquePagination extends Pagination{
    /*
        Attributs
    */
        protected $tesselle=array();
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
            public function setTesselle($tesselle){
                if(is_object($tesselle)){
                    $this->tesselle=$tesselle;
                }else{
                    throw new RuntimeExcetion('tesselle must be an object');
                }
            }
            
    /*
        Autres méthodes
    */
        public function addTesselle($tesselle){
            $this->tesselles[]=$tesselle;
        }
        
        public function buildBody(){
            $body='';
            for($a=0; $a<count($this->objects); $a++){
                    $body.=$this->tesselle->show($this->objects[$a]);
            }
            return $body;
        }
        
        public function buildContent(){
            return '<section>'.$this->buildBody().'</section>';
        }
}
