<?php
/*
 * name: Serie
 * @description :  
 */
 
class Serie extends Multimedia{
    /*
        Attributs
    */
       
        protected $acteurs;
        protected $categorie;
        protected $miniature;
        protected $realisateur;
        protected $resume;
        protected $nbrSaisons;
        protected $nbrEpisodes;
        
        
        /*
            Constantes
        */
 
    /*
        Méthodes générales
    */
		public function __clone(){
			$this->categorie =clone($this->categorie );
			$this->miniature =clone($this->miniature );
			parent::__clone();
		}
        
        /*
            Getters
        */
            public function getActeurs(){
                return $this->acteurs;
            }

            public function getCategorie(){
                return $this->categorie;
            }
            
            public function getRealisateur(){
				return $this->realisateur;
			}

            public function getMiniature(){
                return $this->miniature;
            }
            
            public function getResume(){
                return $this->resume;
            }
            
            public function getNbrEpisodes(){
				return $this->nbrEpisodes;
			}
			
            public function getNbrSaisons(){
				return $this->nbrSaisons;
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
            
            public function setMiniature($i){             
                $this->miniature=$i;
            }
            
            public function setResume($l){
                $this->resume=$l;
            } 
            
            public function setNbrEpisodes($n){
				$this->nbrEpisodes=$n;
			}
			
            public function setNbrSaisons($n){
				$this->nbrSaisons=$n;
			}
            
    /*
        Autres méthodes
    */
 }
