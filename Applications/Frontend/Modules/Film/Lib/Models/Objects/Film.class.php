<?php
/*
 * name: Film
 * @description :  
 */
 
class Film extends Multimedia{
    /*
        Attributs
    */
       
        protected $acteurs;
        protected $categorie;
        protected $files;
        protected $miniature;
        protected $realisateur;
        protected $resume;
        protected $saga;
        protected $subtitle;
        
        
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
		public function __clone(){
			$this->categorie =clone($this->categorie );
			$this->files =ArrayUtilities::cloneObjects($this->files );
			$this->miniature =clone($this->miniature );
			parent::__clone();
		}
       
        /*
            Getters
        */
            public function getActeurs(){
                return $this->acteurs ;
            }

            public function getCategorie(){
                return $this->categorie;
            }
            
            public function getRealisateur(){
				return $this->realisateur;
			}
            
            public function getFiles(){
                return $this->files;
            }
            
            public function getMiniature(){
                return $this->miniature;
            }
            
            public function getResume(){
                return $this->resume;
            }
                    
            public function getSaga(){
				return $this->saga;
			}
			
			public function getSubtitle(){
				return $this->subtitle;
			}
        /*
            Setters
        */
            public function setActeurs($a){
				$this->acteurs=$a;
            } 
            
            public function setCategorie($e){
				$this->categorie=$e;
            }
            
            public function setRealisateur($gl){
				$this->realisateur=$gl;
            }   
            
            public function setFiles($f){
				$this->files=$f;
            }
            
            public function setMiniature($i){   
				$this->miniature=$i;
            }
            
            public function setResume($l){
				$this->resume=$l;
            } 
            
            public function setSaga($s){
				$this->saga=$s;
			}
			
			public function setSubtitle($s){
				$this->subtitle=$s;
			}
            
    /*
        Autres méthodes
    */
 }
