<?php
/*
 * name: SubmitField
 * @description : 
 */ 
 
 class SubmitField extends Field{
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
        public function buildWidget(){
            return '<input type="submit" '.$this->id.' value="'.$this->value.'"/>';
        }
}
