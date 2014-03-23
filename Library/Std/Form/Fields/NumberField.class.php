<?php
/*
 * name: NumberField
 * @description : 
 */ 
 
class NumberField extends Field{
    /*
        Attributs
    */
        protected $min;
        protected $max;
       
        
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
            public function setMax($max){
                if(is_numeric($max)){
                    $this->max='max="'.$max.'"';
                    return true;
                }
            }
        
            public function setMin($min){
                if(is_numeric($min)){
                    $this->min='min="'.$min.'"';
                    return true;
                }
            }   
    /*
        Autres méthodes
    */  
        public function buildWidget(){
            $widget = $this->buildLabel();
            $widget .= '<input type="number"  '.$this->id.' name="'.$this->name.'"
                        value="'.htmlspecialchars($this->value).'" 
                        '.$this->size.' '.$this->max.' '.$this->min.' '.$this->required.' '.$this->readonly.'/>';
                        
            return  $widget.$this->buildTooltip();
        }
}
