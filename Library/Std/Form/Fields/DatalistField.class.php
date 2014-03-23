<?php
/*
 * name: DatalistField
 * @description : 
 */ 
 
class DatalistField extends Field{
    /*
        Attributs
    */
        protected $options=array();
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
				if(is_array($options) or $options instanceof OptionField){
					$this->options=array_merge($this->options, (array)$options);
				}else{
					throw new RuntimeException('options doit être un array ou une instance de OptionField');
				}
			}
    /*
        Autres méthodes
    */  
       public function mergeOptions(){
			for($a=0; $a<count($this->options); $a++){
				$this->value.=$options->buildWidget();
			}
		}
		
        public function buildWidget(){
            $this->mergeOptions();
          
            return '<datalist '.$this->id.'>
							'.$this->value.'
					</datalist>';
        }
        
}

