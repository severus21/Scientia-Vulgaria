<?php
/*
 * name: HiddenField
 * @description : 
 */ 
 
class HiddenField extends StringField{
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
            return'<input type="hidden" '.$this->id.' name="'.$this->name.'"
                       value="'.htmlspecialchars($this->value).'"  '.$this->readonly.'/>';
        }
}
