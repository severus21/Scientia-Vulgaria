<?php
/*
 * name: Content
 * @description :  
 */

class Content extends Objects{
    /*
        Attributs
    */
        protected $content;
        
        /*
            Constantes
        */
        
    /*
        Méthodes générales
    */
  
        /*
            Getters
        */
            public function getContent(){
                return $this->content;
            }
        /*
            Setters
        */
            public function setContent($content){
                $this->content=$content;
            }
    
    /*
        Autres méthodes
    */

}
