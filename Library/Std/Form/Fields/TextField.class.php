<?php
/*
 * name: TextField
 * @description : 
 */ 
 
class TextField extends Field{
    /*
        Attributs
    */
        protected $br=true;
        protected $cols=15;
        protected $maxLength=2000;
        protected $placeholder='';
        protected $rows=5;
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
            public function setCols($cols){
                $cols = (int) $cols;
                if ($cols > 0){
                    $this->cols ='cols="'.$cols.'"';
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
            
            public function setRows($rows){
                $rows = (int) $rows;
                if ($rows > 0){
                    $this->rows ='rows="'.$rows.'"';
                }
            }

    /*
        Autres méthodes
    */  
        public function buildWidget(){
            $widget = $this->buildLabel();
            $widget .= '<textarea type="text" '.$this->id.' name="'.$this->name.'" '.$this->rows.' '.$this->cols.'
                       '.$this->placeholder.' '.$this->maxLength.' '.$this->required.' '.$this->readonly.'>'.$this->value.'</textarea>';
                                 
            return  $widget.$this->buildTooltip();
        }
        
}

