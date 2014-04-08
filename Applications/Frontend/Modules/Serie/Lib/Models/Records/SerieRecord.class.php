<?php
/*
 * name: SerieRecord
 * @description :  
 */
 
class SerieRecord extends MultimediaRecord{
    /*
        Attributs
    */
       
        protected $acteurs;
        protected $id_serieCategorie_categorie; 
        protected $id_image_miniature;
        protected $realisateur;
        protected $resume;
        protected $nbrSaisons;
        protected $nbrEpisodes;
        
        
        /*
            Constantes
        */
            const ACTEURS_INVALID='acteurs';
            const LANGUE_INVALID='langue'; 
            const REALISATEUR_INVALID='realisateur';  
            const RESUME_INVALID='resume';
            const NBR_SAISONS_INVALID='nbrSaisons'; 
            const NBR_EPIDOSES_INVALID='nbrEpisodes'; 
    /*
        Méthodes générales
    */
        
        /*
            Getters
        */
            public function getActeurs(){
                return $this->acteurs;
            }

            public function getId_serieCategorie_categorie(){
                return $this->id_serieCategorie_categorie;
            }
            
            public function getRealisateur(){
				return $this->realisateur;
			}

            public function getId_image_miniature(){
                return $this->id_image_miniature;
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
                if(is_string($a) && strlen($a)<257){
                    $this->acteurs=htmlentities($a, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
					$this->erreurs[]=self::ACTEURS_INVALID;
                    return false;
                }
            } 
            
            public function setId_serieCategorie_categorie($e){
                if(is_numeric($e)){
                    $this->id_serieCategorie_categorie=$e;
                    return true;
                }else{
                    throw new RuntimeException('must be an integer');
                }
            }
            
            public function setRealisateur($gl){
                if(is_string($gl) && strlen($gl)<=256){
                    $this->realisateur=htmlentities($gl, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::REALISATEUR_INVALID;
                    return false;
                }
            }
            
            public function setId_image_miniature($i){             
                if(is_numeric($i)){
                    $this->id_image_miniature=$i;
                }else{
                    throw new RuntimeException('must be an integer');
                }
            }
            
            public function setResume($l){
                if(is_string($l) && strlen($l)<=2000){
                    $this->resume=htmlentities($l, ENT_HTML5, 'utf-8', false);
                }else{
                    $this->erreurs[]=self::RESUME_INVALID;
                }
            } 
            
            public function setNbrEpisodes($n){
				if(is_numeric($n) && $n>=0){
                    $this->nbrEpisodes=$n;
                }else{
                    $this->erreurs[]=self::NBR_EPIDOSES_INVALID;
                }
			}
			
            public function setNbrSaisons($n){
				if(is_numeric($n) && $n>=0){
                    $this->nbrSaisons=$n;
                }else{
                    $this->erreurs[]=self::NBR_SAISONS_INVALID;
                }
			}
            
    /*
        Autres méthodes
    */
        public function isValidForm(){
            return ( isset($this->date) && isset($this->id_serieCategorie_categorie) && isset($this->langue) && isset($this->nbrEpisodes)
            && isset($this->nbrSaisons) );
        }
        
        public function isValid($form=false){
            return ($this->isValidForm() &&
            isset($this->id_image_miniature));
        }
 }
