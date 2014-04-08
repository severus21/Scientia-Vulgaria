<?php
/*
 * name: EpisodeRecord
 * @description :  
 */
 
class EpisodeRecord extends Record{
    /*
        Attributs
    */
		protected $login;
        protected $idSaison;
        protected $idSerie;
        protected $nom; 
        protected $n;
        protected $resume;
        protected $subtitle;
        protected $id_video_files_ar;
        
        
        /*
            Constantes
        */
			const NOM_INVALID='nom';  
            const N_INVALID='n';
            const SUBTITLE_INVALID='langue';
    /*
        Méthodes générales
    */
        /*
            Getters
        */
			public function getLogin(){
				return $this->login;
			}
			
            public function getIdSaison(){
                return $this->idSaison;
            }

            public function getIdSerie(){
                return $this->idSerie;
            }
            
            public function getNom(){
				return $this->nom;
			}
            
            public function getN(){
                return $this->n;
            }
			
			public function getResume(){
				return $this->resume;
			}
			
            public function getSubtitle(){
                return $this->subtitle;
            }
            
            public function getId_video_files_ar(){
                return $this->id_video_files_ar;
            }

        /*
            Setters
        */
			public function setLogin($l){//pas besoin de traitement supplémentaire car le login provient d'un objet user
                if(isset($l))
                    $this->login=$l;
            }
			
            public function setIdSaison($id){
				if(is_numeric($id))
					$this->idSaison=(int)$id;
				else
					throw new RuntimeException('must be an integer');
            } 
            
            public function setIdSerie($e){
				if(is_numeric($e))
					$this->idSerie=(int)$e;
                else
					throw new RuntimeException('must be an integer');
            }
            
            public function setNom($nom){
				if( isset($nom) && strlen($nom)<257)
					$this->nom=htmlentities($nom, ENT_NOQUOTES, 'utf-8', false);
				else
					$this->erreurs[]=self::NOM_INVALID;
            }   
            
            public function setN($n){
				if(is_numeric($n))
					$this->n=(int)$n;
				else
					$this->erreurs[]=self::N_INVALID;
            }
            
            public function setResume($l){
                if(is_string($l) && strlen($l)<=2000){
                    $this->resume=htmlentities($l, ENT_HTML5, 'utf-8', false);
                }else{
                    $this->erreurs[]=self::RESUME_INVALID;
                }
            } 
            
            public function setSubtitle($s){
				if(is_string($s) && strlen($s)<=256)
                    $this->subtitle=htmlentities($s, ENT_NOQUOTES, 'utf-8', false);
                else
                    $this->erreurs[]=self::SAGA_INVALID;
			}
            
            public function setId_video_files_ar($f){
                if(preg_match('#^[0-9\,]+$#',$f)){
					$n=strlen($f);
					$this->id_video_files_ar= ($f[$n-1] ==',') ? substr($f, 0, $n-1 ) : $f;
					return true;
				}
            } 
    /*
        Autres méthodes
    */
    public function isValidForm(){
            return (  isset($this->n) && isset($this->nom) && isset($this->resume) );
        }
        
        public function isValid($form=false){
            return ( $this->isValidForm() && isset($this->idSerie) && isset($this->idSaison) && isset($this->login) );
        }
 }


