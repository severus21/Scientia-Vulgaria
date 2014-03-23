<?php
/*
 * name: CacheElement
 * @description :  
 */
	
class CacheElement{
    /*
        Attributs
    */  
		protected $app="";
		protected $mod="";
		protected $name="";
		protected $size="";
		
        /*
            Constantes
        */

    /*
        Méthodes générales
    */
		public function __construct(Array $donnees=null){
            if(!empty($donnees))
                $this->hydrate($donnees);
        }
        
        public function hydrate(array $donnees){
            foreach ($donnees as $attribut => $valeur){
                $methode = 'set'.ucfirst($attribut);
                
                if (is_callable(array($this, $methode)))
                    $this->$methode($valeur);

            }
        }
        /*
            Getters
        */
			public function getApp(){
				return $this->app;
			}
			
			public function getMod(){
				return $this->mod;
			}
			
			public function getName(){
				return $this->name;
			}
			
			public function getSize(){
				return $this->size;
			}
			
			
        /*
            Setters
        */
			public function setApp($a){
				$this->app=$a;
			}
			
			public function setMod($a){
				$this->mod=$a;
			}	
			
			public function setName($a){
				$this->name=$a;
			}	
			
			public function setSize($a){
				$this->size=$a;
			}		
			
    /*
        Autres méthodes
    */

}
