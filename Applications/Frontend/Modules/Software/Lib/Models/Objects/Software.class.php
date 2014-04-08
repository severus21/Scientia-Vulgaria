<?php
/*
 * name: Software
 * @description :  
 */
 
class Software extends Multimedia{
    /*
        Attributs
    */
       
        public $categorie;
        public $description;
        public $developpeur;
        public $file;
        public $tutoriel;
        public $miniature;
        public $license;
        public $os;
        public $version;
        
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        
	public function __clone(){
			$this->file = clone($this->file );
			$this->tutoriel = clone($this->tutoriel );
			$this->miniature = clone($this->miniature );
			$this->categorie = clone($this->categorie );
			$this->os = clone($this->os );
			parent::__clone();
		}
	/*
            Getters
        */
            public function getCategorie(){
                return $this->categorie;
            }
            
            public function getDescription(){
                return $this->description;
            }
            
            public function getDeveloppeur(){
                return $this->developpeur;
            }
            
            public function getFile(){
                return $this->file;
            }
            
            public function getTutoriel(){
                return $this->tutoriel;
            }
            
            public function getMiniature(){
                return $this->miniature;
            }
            
            public function getLicense(){
                return $this->license;
            }
            
            public function getOs(){
                return $this->os;
            }
            
            public function getVersion(){
                return $this->version;
            }
        /*
            Setters
        */
            public function setCategorie($c){
                $this->categorie=$c;
                return true;
            }
            
            public function setDescription($d){
                $this->description=$d;
                return true;
            }
            
            public function setDeveloppeur($d){
                $this->developpeur=$d;
                return true;
            }
            
            public function setFile($i){
                $this->file=$i;
                return true;
            }
            
            public function setTutoriel($i){
                $this->tutoriel=$i;
                return true;
            }
            
            public function setMiniature($i){
                $this->miniature=$i;
                return true;
            }
            
            public function setLicense($l){
                $this->license=$l;
                return true;
            }
            
            public function setOs($o){
                $this->os=$o;
                return true;
            }
            
            public function setVersion($v){
                $this->version=$v;
                return true;
            }
    /*
        Autres méthodes
    */
 }
