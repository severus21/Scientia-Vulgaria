<?php
/*
 * name: CheckboxField
 * @description : 
 */ 
 
class CheckboxField extends Field{
    /*
        Attributs
    */
        protected $brLabel=false;
        protected $checked;
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
            public function setChecked($checked){
                if($checked){
                    $this->checked='checked="checked"';
                    return true;
                }
            }
    /*
        Autres méthodes
    */  
        public function buildWidget(){
            $widget= '<input type="checkbox" '.$this->id.' name="'.$this->name.'"
                       value="'.htmlspecialchars($this->value).'" '.$this->checked.'
                       '.$this->required.' '.$this->readonly.'/>'.$this->buildLabel();                    
           
            return  $widget.$this->buildTooltip();
        }
}
