<?php
/*
 * name: PasswordField
 * @description : 
 */ 
 
 class PasswordField extends Field{
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
            $widget .= '<input type="password" '.$this->id.' name="'.$this->name.'" '.$this->size.' required '.$this->readonly.'/>';
                   
            return  $widget.$this->buildTooltip();
        }
}
