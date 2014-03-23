<?php
/*
 * name: Field
 * @description : 
 */
abstract class Field
{
     /*
        Attributs
    */
        protected $error=false;
        protected $id='';
        protected $label='';
        protected $name='';
        protected $required='';
        protected $size='size="15"';
        protected $tooltip='';
        protected $validators=array();
        protected $value='';
        protected $br=true; //indique si on va à la ligne ou non
        protected $brLabel=true; //indique si on va à la ligne ou non apres le label
        protected $readonly='';
        protected $class='';
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        public function __construct(array $options = array()){
            if (!empty($options)){
                $this->hydrate($options);
            }
        }
        
        public function hydrate($options){
            foreach ($options as $type => $value){
                $method = 'set'.ucfirst($type);
                
                if (is_callable(array($this, $method))){
                    $this->$method($value);
                }
            }
            
           
        }
       
        /*
            Getters
        */
            public function getLabel(){
                return $this->label;
            }
            
            public function getName(){
                return $this->name;
            }
            
            public function getRequired(){
                return $this->required;
            }
            
            public function getValidators(){
                return $this->validators;
            }
            
            public function getValue(){
                return $this->value;
            }
            
            public function getBr(){
                return $this->br;
            }
            
            public function getReadonly(){
				return $this->readonly;
			}
			
			public function getClass(){
				return $this->class;
			}
        /*
            Setters
        */
            public function setBr($br){
                if(is_bool($br)){
                    $this->br=$br;
                }else{
                    throw new RuntimeException('br must be a booleen');
                }
            }
            
            public function setBrLabel($br){
                if(is_bool($br)){
                    $this->brLabel=$br;
                }else{
                    throw new RuntimeException('brLabel must be a booleen');
                }
            }
            public function setError($error){
                $this->error=$error;
            }
            
            public function setId($id){
                $this->id='id="'.$id.'"';
            }
            
            public function setLabel($label){
                if (is_string($label)){
                    $this->label = $label;
                }
            }
            
            public function setName($name){
                if (is_string($name)){
                    $this->name = $name;
                }
            }
            
            public function setRequired($required){
                if($required){
                    $this->required='required';
                }
            }
            
            public function setSize($size){
                $size = (int) $size;
                if ($size > 0){
                    $this->size ='size="'.$size.'"';
                }else{
                    throw new RuntimeRuntimeException('Size doit être un nombre supérieur à 0');
                }
            }
            
            public function setTooltip($tooltip){
                $this->tooltip=$tooltip;
            }
            
            public function setValidators(array $validators){
                foreach ($validators as $validator){
                    if ($validator instanceof Validator && !in_array($validator,$this->validators)){
                        $this->validators[] = $validator;
                    }
                }
            }
            
            public function setValue($value){
                if (is_string($value)){
                    $this->value =  html_entity_decode($value, ENT_HTML5, 'utf-8'); 
                }
            }
            
            public function setReadonly($readonly){
                if($readonly){
                    $this->readonly='readonly';
                }
            }
            
			public function setClass($value){
                if (is_string($value)){
                    $this->class =  'class="'.$value.'"'; 
                }
            }
            

    /*
        Autres méthodes
    */  
       
        abstract public function buildWidget();
        
        public function buildBr(){
            if($this->br)
                return '<br/>';
            else
                return '';
        }
        
        public function buildLabel(){
            $label='';
            if($this->label!=''){
                $label.= '<label for="'.$this->name.'">'.$this->label.'</label>';
                $this->brLabel ? $label.='<br/>' : null;
            }    
            return $label;
        }
        
        public function buildTooltip(){
            $tip='';
            $displayError='';
            if ($this->error)
                $displayError='_display';
            if($this->tooltip!='')
                 $tip.='<span class="tooltip'.$displayError.'">'.$this->tooltip.'</span>'; 
            return $tip;
        }
        
        public function isValid(){
            foreach($this->validators as $validator){
                if(!$validator->isValid($this->value)){
                    $this->error=true;
                    return false;
                }
            }
            return true;
        }

}
