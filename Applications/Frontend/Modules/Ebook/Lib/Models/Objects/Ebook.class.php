<?php
/*
 * name: Ebook
 * @description :  
 */
 
class Ebook extends Multimedia{
    /*
        Attributs
    */
		protected $isbn;
        protected $auteur;
        protected $editeur;
        protected $etiquette; 
        protected $genre;  
        protected $files=array();
        protected $miniature;
        protected $resume;
        protected $serie;
        
        
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        public function __clone(){
			$this->genre =clone($this->genre );
			$this->etiquette =clone($this->etiquette );
			$this->files =ArrayUtilities::cloneObjects($this->files );
			$this->miniature =clone($this->miniature );
			parent::__clone();
		}
        /*
            Getters
        */
			public function getIsbn(){
				return $this->isbn;
			}
        
            public function getAuteur(){
                return $this->auteur;
            }
            
            public function getEditeur(){
                return $this->editeur;
            }
            
            public function getEtiquette(){
                return $this->etiquette;
            }
            
            public function getGenre(){
				return $this->genre;
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
            
            public function getSerie(){
				return $this->serie;
			}
        /*
            Setters
        */
			public function setIsbn($i){
				$this->isbn=$i;
			}
			
            public function setAuteur($a){
				$this->auteur=$a;
            }
            
            public function setEditeur($e){
				$this->editeur=$e;
            }
            
            public function setEtiquette($e){
                $this->etiquette=$e;
            }
            
            public function setGenre($gl){
				$this->genre=$gl;
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
            
            public function setSerie($s){
				$this->serie=$s;
			}
            
    /*
        Autres méthodes
    */
 }
