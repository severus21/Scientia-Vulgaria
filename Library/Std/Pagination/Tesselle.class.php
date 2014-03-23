<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class Tesselle{
    /*
        Attributs
    */
		protected $elements=array();
        protected $class='tesselle'; //class CSS   
        
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
            
            public function getClass(){
                return $this->class;
            }

        /*
            Setters
        */
            public function setElements($e){
				if(is_array($e)){
					$this->elements=$e;
				}else{
					throw new RuntimeException('elements must be an array');
				}
			}
            
            public function setClass($c){
                $this->class=$c;
            }
    /*
        Autres méthodes
    */
		public function addElement($e){
			$this->elements[]=$e;
		}
        
        public function show($obj){
			$content='';
			$class='';
			foreach($this->elements as $element){
				$content.=$element->buildContent($obj);
			}
			($this->class!='') ? $class='class="'.$this->class.'"' : null;
            return '<section class="'.$this->class.'" '.$class.'>'.String::NlToBr($content).'</section>';
        }
       
}
