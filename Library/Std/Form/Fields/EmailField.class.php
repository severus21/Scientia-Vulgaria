<?php
/*
 * name: EmailField
 * @description : 
 */ 
 
class EmailField extends StringField{
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
            
            isset($this->list)? $l='list="'.$this->list->getId().'"' : $l='';
            
            $widget .= '<input type="email" '.$this->autocomplete.' '.$this->id.' name="'.$this->name.'"
                        '.$this->placeholder.' value="'.htmlspecialchars($this->value).'" '.$l.'
                        '.$this->size.' '.$this->maxLength.' '.$this->required.' '.$this->readonly.'/>';
            
            isset($this->list)? $widget.=$this->list->buildWidget() :null;
                               
            return  $widget.$this->buildTooltip();
        }
}
