<?php
/*
 * name: OptionField
 * @description : 
 */ 
 
 class OptionField extends Field{
    /*
        Attributs
    */
        protected $selected='';
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
			public function setSelected($selected){
				if($selected){
					$this->selected='selected';
				}
			}
    /*
        Autres méthodes
    */  
        public function buildWidget(){
            return '<option value="'.$this->value.'" '.$this->selected.'>'.$this->label.'</option>';
        }
}
