<?php
/*
 *
 * name: DateField
 * @description : 
 */ 
 
 
class DateField extends Field{
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
            $widget =$this->buildLabel();
            
            $widget .= '<input type="date" '.$this->id.' name="'.$this->name.'"  value="'.htmlspecialchars($this->value).'" 
                        '.$this->size.' '.$this->required.' '.$this->readonly.'/>';  
                               
            return  $widget.$this->buildTooltip();
        }
}
