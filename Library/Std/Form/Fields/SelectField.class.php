<?php
/*
 * name: SelectField
 * @description : 
 */ 
 
class SelectField extends Field{
    /*
        Attributs
    */
        protected $br=true;
        protected $multiple='';
        protected $size='size="1"';
        protected $options=array();
        protected $optgroups=array();
        
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
            public function setMultiple($multiple){
                if(is_numeric($multiple)){
                    $this->multiple='multiple="'.$multiple.'"';
                }else{
                    throw new RuntimeException('multiple doit être un élément numérique');
                }
            }
            
            public function setOptions($options){
                if(is_array($options)){
                    $this->options=array_merge($this->options, $options);
                }elseif($options instanceof OptionField){
                    $this->options[]=$options;
                }else{
                    throw new RuntimeException('options doit être un array ou une instance de OptionField');
                }
            }
            
            public function setOptgroups($grp){
                if(is_array($grp) or $grp instanceof OptgroupField){
                    $this->optgroups=array_merge((array)$this->optgroups, (array)$grp);
                }elseif($grp instanceof cateField){
                    $this->optgroups[]=$grp;
                }else{
                    throw new RuntimeException('optgroups doit être un array ou une instance de Opgroupt');
                }
            }
    /*
        Autres méthodes
    */  
    
        public function mergeOptions(){
			$buffer='';
            if(isset($this->options)){
                for($a=0; $a<count($this->options); $a++){
                    $option=$this->options[$a];
                    $buffer.=$option->buildWidget();
                }
            }
            return $buffer;
        }
        
        public function mergeOptgroups(){
			$buffer='';
            if(isset($this->optgroups)){
                for($a=0; $a<count($this->optgroups); $a++){
                    $option=$this->optgroups[$a];
                    $buffer.=$option->buildWidget();
                }
            }
            return $buffer;
        }
        
        public function buildWidget(){
			$buffer='';
            $widget = $this->buildLabel();
			$buffer=$this->mergeOptions();
            $buffer.=$this->mergeOptgroups();
            
            $widget.='<select '.$this->id.' name="'.$this->name.'" '.$this->multiple.' '.$this->size.' '.$this->required.' '.$this->readonly.'>
                            '.$buffer.'
                    </select>';
                 
            return $widget.$this->buildTooltip();
        }
        
}

