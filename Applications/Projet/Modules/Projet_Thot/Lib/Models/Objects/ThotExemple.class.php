<?php
/*
 * name: ThotExemple
 * @description :  
 */
 
class ThotExemple extends Objects{
    /*
        Attributs
    */
        protected $login;
        protected $image;
        protected $c;
        protected $database;
 
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
		public function __clone(){
			$this->image =clone( $this->image );
			$this->database =clone($this->database );
			parent::__clone();
		}
        
        /*
            Getters
        */            
            public function getLogin(){
				return $this->login;
			}
            
            public function getImage(){
                return $this->image;
            }
            
            public function getC(){
                return $this->c;
            }
            
            public function getDatabase(){
                return $this->database;
            }
        /*
            Setters
        */
           public function setLogin($l){
				$this->login=$l;
			}
            
            public function setImage($i){
                $this->image=$i;
            }
            
            public function setC($c){
                $this->c=$c;
            }
            
            public function setDatabase($d){
                $this->database=$d;
            }           
    /*
        Autres méthodes
    */
		public function getShowLink($linkOnly=false, $class='', $value='Voir'){
			if($linkOnly)
				return '/projet/thot/update-index-exemple-'.$this->getRecord()->getId();
			else 
				return '<a class="'.$class.'" href="/projet/thot/update-index-exemple-'.$this->getRecord()->getId().'">'.$value.'</a>';
		}
		
		public function getNumero(){
			return 'N°'.$this->id;
		}
 }
