<?php
/*
 * name: Form
 * @description : 
 */ 
 
 class FileField extends Field{
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
            $widget = $this->buildLabel();
            $widget .= '<input type="file" '.$this->id.' name="'.$this->name.'" '.$this->size.' '.$this->required.' '.$this->readonly.'/>';       
            return  $widget.$this->buildTooltip();
        }
}
