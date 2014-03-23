<?php

abstract class Cell{
    /*
        Attributs
    */
        protected $name;
        protected $functions=array();//array fct a execute dans l'ordre
        protected $class; //class CSS
        protected $order;
		protected $preTraitement=false;//Si on String::NlToBr(String::parseBBCode()) la valeur
        
        
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        public function __construct(array $donnees = array()){
            if (!empty($donnees)){
                $this->hydrate($donnees);
            }
        }
        
        public function hydrate(array $donnees){
            foreach ($donnees as $attribut => $valeur){
                $methode = 'set'.ucfirst($attribut);
                
                if (is_callable(array($this, $methode))){
                    $this->$methode($valeur);
                }
            }
        }
        
        /*
            Getters
        */
            public function getName(){
                return $this->name;
            }
            
            public function getFunction(){
                return $this->functions;
            }
            
            public function getClass(){
                return $this->class;
            }
            
            public function getOrder(){
				return $this->order;
			}

        /*
            Setters
        */
            public function setName($n){
                $this->name=$n;
            }
            
            public function setFunctions($v){
                $this->functions=$v;
            }
            
            public function setClass($c){
                $this->class=$c;
            }
            
            public function setOrder($o){
				$this->order=$o;
			}
			
			public function setPreTraitement($p){
				$this->preTraitement=$p;
			}
    /*
        Autres méthodes
    */
        public function show($obj){
           return '<th class="'.$this->class.'">'.String::NlToBr($this->showContent($obj)).'</th>';
           return '';
        }
        
        public function addFunction($f){
            $this->function[]=$f;
        }
        
        public function execFunction($obj){
            $n=count($this->functions);
            for($a=0; $a<$n ; $a++){ 
                if(isset($obj)){
                    $function=$this->functions[$a];
                    $obj=$obj->$function();
                }
            }
            $n>0 ? $r=$obj : $r='';
            return $r;
        }
}
