<?php
/*
 * name: FilmRecord
 * @description :  
 */
 
class FilmRecord extends MultimediaRecord{
    /*
        Attributs
    */
       
        protected $acteurs;
        protected $id_filmCategorie_categorie;
        protected $id_video_files_ar;
        protected $id_image_miniature;
        protected $realisateur;
        protected $resume;
        protected $saga;
        protected $subtitle;
        
        
        /*
            Constantes
        */
            const ACTEURS_INVALID='acteurs';
            const CATEGORIE_INVALID='categorie';
            const LANGUE_INVALID='langue'; 
            const REALISATEUR_INVALID='realisateur';  
            const RESUME_INVALID='resume';
            const SAGA_INVALID='saga'; 
            const SUBTITLE_INVALID='subtitle';
    /*
        Méthodes générales
    */
        
        /*
            Getters
        */
            public function getActeurs(){
                return $this->acteurs;
            }

            public function getId_filmCategorie_categorie(){
                return $this->id_filmCategorie_categorie;
            }
            
            public function getRealisateur(){
				return $this->realisateur;
			}
            
            public function getId_video_files_ar(){
                return $this->id_video_files_ar;
            }
            
            public function getId_image_miniature(){
                return $this->id_image_miniature;
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
                if(is_string($a) && strlen($a)<257){
                    $this->acteurs=htmlentities($a, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
					$this->erreurs[]=self::ACTEURS_INVALID;
                    return false;
                }
            } 
            
            public function setId_filmCategorie_categorie($e){
                if(is_numeric($e)){
                    $this->id_filmCategorie_categorie=$e;
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
            
            public function setId_video_files_ar($f){
                if(preg_match('#^[0-9\,]+$#',$f)){
					$n=strlen($f);
					$this->id_video_files_ar= ($f[$n-1] ==',') ? substr($f, 0, $n-1 ) : $f;
				}
            }
            
            public function setId_image_miniature($i){             
                if(is_numeric($i)){
                    $this->id_image_miniature=$i;
                    return true;
                }else{
                    throw new RuntimeException('must be an integer');
                }
            }
            
            public function setResume($l){
                if(is_string($l) && strlen($l)<=2000){
                    $this->resume=htmlentities($l, ENT_HTML5, 'utf-8', false);
                    //$this->resume=htmlentities($l, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::RESUME_INVALID;
                    return false;
                }
            } 
            
            public function setSaga($s){
				if(is_string($s) && strlen($s)<=256){
                    $this->saga=htmlentities($s, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::SAGA_INVALID;
                    return false;
                }
			}
			
			public function setSubtitle($s){
				if(is_string($s) && strlen($s)<=256)
                    $this->subtitle=htmlentities($s, ENT_NOQUOTES, 'utf-8', false);
                else
                    $this->erreurs[]=self::SAGA_INVALID;
			}
            
    /*
        Autres méthodes
    */
        public function isValidForm(){
            return (isset($this->date) && isset($this->id_filmCategorie_categorie) && isset($this->langue) && isset($this->nom));
        }
        
        public function isValid($form=false){
            return ($this->isValidForm() && isset($this->id_image_miniature));
        }
 }
