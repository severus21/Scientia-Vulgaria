<?php
/*
 * name: OptgroupField
 * @description : 
 */ 
 
 class OptgroupField extends Field{
    /*
        Attributs
    */
        protected $options='';
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
            public function setOptions($options){
                if(is_array($options)){
                    $this->options=array_merge($this->options, $options);
                }elseif($options instanceof OptionField){
                    $this->options[]=$options;
                }else{
                    throw new RuntimeException('options doit être un array ou une instance de OptionField');
                }
            }
    /*
        Autres méthodes
    */  
		public function addOption($opt){
			if($opt instanceof OptionField){
				$this->options[]=$opt;
			}else{
				throw new RuntimeException('options doit être un array ou une instance de OptionField');
			}
		}
        public function mergeOptions(){
            if(isset($this->options)){
                for($a=0; $a<count($this->options); $a++){
                    $option=$this->options[$a];
                    $this->value.=$option->buildWidget();
                }
            }
        }
        
        public function buildWidget(){
            $this->mergeOptions();
            
            $widget='<optgroup  label="'.$this->label.'">
                        '.$this->value.'
                    </optgroup>';
                 
            return $widget;
        }
}
