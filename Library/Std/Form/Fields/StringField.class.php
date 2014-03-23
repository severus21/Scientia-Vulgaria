<?php
/*
 * name: StringField
 * @description : 
 */ 
 
class StringField extends Field{
    /*
        Attributs
    */
        protected $autocomplete='autocomplete="off"';
        protected $list;
        protected $maxLength='maxlength="256"';
        protected $placeholder='';
       
        
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
            public function setAutocomplete($autocomplete){
                if($autocomplete){
                    $this->autocomplete='autocomplete="on"';
                    return true;
                }
            }
            
            public function setList($list){
                if($list instanceof DatalistField){
                    $this->list=$list;
                }else{
                    throw new RuntimeException('list doit être une instance de datalistField');
                }
            }
            
            public function setMaxLength($maxLength){
                $maxLength = (int) $maxLength;
                if($maxLength > 0){
                    $this->maxLength='maxlength="'.$maxLength.'"';
                }else{
                    throw new RuntimeException('La longueur maximale doit être un nombre supérieur à 0');
                }
            }
            
            public function setPlaceholder($placeholder){
                if(is_string($placeholder)){
                    $this->placeholder = 'placeholder="'.$placeholder.'"';
                }else{
                    throw new RuntimeRuntimeException('Placeholder doit être une string');
                }
            }
            
            
            
            
            
    /*
        Autres méthodes
    */  
        public function buildWidget(){
            $widget = $this->buildLabel();
            isset($this->list)? $l='list="'.$this->list->getId().'"' : $l='';
            $widget .= '<input type="text" '.$this->autocomplete.' '.$this->id.' name="'.$this->name.'"
                        '.$this->placeholder.' value="'.htmlspecialchars($this->value).'" '.$l.'
                        '.$this->size.' '.$this->maxLength.' '.$this->required.' '.$this->readonly.'/>';
            
            isset($this->list)? $widget.=$this->list->buildWidget() :null;
                        
           
            return  $widget.$this->buildTooltip();
        }
}
