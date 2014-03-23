<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class TesselleElement{
    /*
        Attributs
    */
		protected $args=array();//array associatif n°de la fct=>[associatif nomarg=>value]
        protected $functions=array();//array fct a execute dans l'ordre suivant un index alphanumerique ['img'=>array, 'aside'=>array]
		protected $id='';
		protected $class='';
		protected $link=false;
		//protected $preTraitement=false;//Si on String::NlToBr(String::parseBBCode()) la valeur
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

        /*
            Setters
        */
			public function setArgs($a){
                $this->args=$a;
            }
            
			public function setFunctions($v){
                $this->functions=$v;
            }
            public function setClass($c){
                $this->class=$c;
            }
            public function setId($i){
                $this->id=$i;
            }
            public function setLink($l){
				$this->link=$l;
			}
			/*ublic function setPreTraitement($p){
				$this->preTraitement=$p;
			}*/
    /*
        Autres méthodes
    */
		public function addArg($f, $name, $value){
            if(is_numeric($f) && !empty($name) && !empty($value)){
                $this->args[$f][$name]=$value;
            }else{
                throw new RuntimeException('');
            }
        }
		
		public function addFunction($f){
            if(is_array($f)){
                $this->functions[]=$f;
            }else{
                throw new RuntimeException('');
            }
        }
        
        public function execFunction($obj){
			$functions=$this->functions;
			$n=count($functions);
			for($a=0; $a<$n ; $a++){ 
				if(isset($obj)){
					$function=$functions[$a];
					(array_key_exists($a, $this->args)) ? $args=$this->args[$a] : $args=null;
					$obj=$obj->$function($args);
				}
			}
			$n>0 ? $r=$obj : $r='';
            return $r;
        }
}
