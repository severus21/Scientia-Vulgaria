<?php
/*
 * name: ButtonField
 * @description : 
 */ 
 
 class ButtonField extends Field{
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
            return '<input type="button" '.$this->id.' '.$this->class.' name="'.$this->name.'" value="'.$this->value.'"/>';
        }
}
