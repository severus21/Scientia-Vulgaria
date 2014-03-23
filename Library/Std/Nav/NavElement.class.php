<?php
/*
 * name: NavElement
 * @description :  
 * 	objet recursif 
 */ 
    
class NavElement{
    /*
        Attributs
    */  
		protected $id='';
		protected $class='';
		protected $link='';
		protected $position=-1;
		protected $label='';
		protected $childs=array();
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
            public function getPosition(){
				return $this->position;
			}
        /*
            Setters
        */
			public function setClass($c){
				isset($c) ? $this->class=' class="'.$c.'" ' : null;
			}
			
			public function setId($i){
				isset($i) ? $this->id=' id="'.$i.'" ' : null;
			}
			
			public function setLabel($l){
				!empty($l) ? $this->label=$l : null;
			}
			
			public function setLink($l){
				!empty($l) ? $this->link='<a href="'.$l.'">' : null;
			}
			
			public function setPosition($p){
				(is_int($p) && $p>-1) ? $this->position=$p : null;
			}
			
			public function setChilds($nE){
				!empty($nE) ? $this->childs=$nE : null;
			}
    /*
        Autres méthodes
    */
		public function addChild($nE){
			if(!empty($nE)){
				$n=$nE->getPosition();
				($n>-1) ? $this->childs[$n]=$nE : $this->childs[]=$nE;
			}
		}
		
		//retun le premier enfant ayant ce label
		protected function getChildByLabel($l){
			for($a=0; $a<count($this->childs); $a++){
				if($this->childs[$a]->getLabel==$l)
					return $this->childs[$a];
			}
			return null;
		}
		
		protected function getLastChild(){
			if(!empty($this->childs))
				return $this->childs[count($this->childs)-1];
			else 
				return null;
		}
		protected function getFirstChild(){
			if(!empty($this->childs))
				return $this->childs[0];
			else 
				return null;
		}
		protected function buildChilds(){
			$tmp='<ul>';
			for($a=0; $a<count($this->childs); $a++){
				$tmp.=$this->childs[$a]->build();
			}
			return $tmp.'</ul>';
		}
		
		public function build(){
			!empty($this->link) ? $endLink='</a>' : $endLink='';
			!empty($this->childs) ? $recuriveContent=$this->buildChilds() : $recuriveContent='';
			return $this->link.'<li'.$this->id.$this->class.'>'.$this->label.$recuriveContent.'</li>'.$endLink;
		}
}
