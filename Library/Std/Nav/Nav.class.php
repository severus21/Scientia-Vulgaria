<?php
/*
 * name: Nav
 * @description :  
 */ 
    
class Nav{
    /*
        Attributs
    */  
		protected $id='';
		protected $class='';
		protected $navElements=array(); //Array de NavElement ahahaha
        /*
            Constantes
        */
            
    /*
        Méthodes générales
    */
		public function __construct(array $donnees = array()){
			!empty($donnees) ? $this->hydrate($donnees) : null;
        }
        
        public function hydrate(array $donnees){
            foreach ($donnees as $attribut => $valeur){
				$methode = 'set'.ucfirst($attribut);
				is_callable(array($this, $methode)) ? $this->$methode($valeur) : null;
            }
        }
        /*
            Getters
        */
            
        /*
            Setters
        */
			public function setClass($c){
				isset($c) ? $this->class=' class="'.$c.'" ' :null ;
			}
			
			public function setId($i){
				isset($i) ? $this->id=' id="'.$i.'" ' : null;
			}
			
			public function setNavElements($nE){
				!empty($nE) ? $this->navElements=$nE : null;
			}
    /*
        Autres méthodes
    */
		public function addNavElement($nE){
			if(!empty($nE)){
				$n=$nE->getPosition();
				($n>-1) ? $this->navElements[$n]=$nE : $this->navElements[]=$nE;
			}
		}
		
		public function build(){
			$nav='<nav '.$this->id.' '.$this->class.'><ul>';
			for($a=0; $a<count($this->navElements); $a++){
				$nav.=$this->navElements[$a]->build();
			}
			return $nav.'</ul></nav>';
		}
}
