<?php
/*
 * name: EbookRecord
 * @description :  
 */
 
class EbookRecord extends MultimediaRecord{
    /*
        Attributs
    */
		protected $isbn;
        protected $auteur;
        protected $editeur;
        protected $id_etiquette_etiquette; 
        protected $id_ebookCategorie_genre;  
        protected $id_document_files_ar='';
        protected $id_image_miniature;
        protected $resume;
        protected $serie;
        
        
        /*
            Constantes
        */
            const ISBN_INVALID='auteur';
            const AUTEUR_INVALID='auteur';
            const DATE_INVALID='date';
            const EDITEUR_INVALID='editeur';
            const ETIQUETTE_INVALID='etiquette'; 
            const GENRE_INVALID='genre'; 
            const RESUME_INVALID='resume';
    /*
        Méthodes générales
    */
        
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
            
            public function getId_etiquette_etiquette(){
                return $this->id_etiquette_etiquette;
            }
            
            public function getId_ebookCategorie_genre(){
				return $this->id_ebookCategorie_genre;
			}
            
            public function getId_document_files_ar(){
                return $this->id_document_files_ar;
            }
            
            public function getId_image_miniature(){
                return $this->id_image_miniature;
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
				if(is_numeric($i) && (strlen($i)==10 || strlen($i)==13) )
					$this->isbn=$i;
				else
					$this->erreurs[]=self::ISBN_INVALID;
			}
        
            public function setAuteur($a){
                if(is_string($a) && strlen($a)<257){
                    $this->auteur=htmlentities($a, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
					$this->erreurs[]=self::AUTEUR_INVALID;
                    return false;
                }
            } 
            
            public function setEditeur($e){
                if(is_string($e) && strlen($e)<=256){
                    $this->editeur=htmlentities($e, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::EDITEUR_INVALID;
                    return false;
                }
            }
            
            public function setId_etiquette_etiquette($e){
                if(is_numeric($e)){
                    $this->id_etiquette_etiquette=$e;
                    return true;
                }else{
                    throw new RuntimeException('must be an integer');
                }
            }
            
            public function setId_ebookCategorie_genre($gl){
                if(is_numeric($gl)){
                    $this->id_ebookCategorie_genre=$gl;
                    return true;
                }else{
                    throw new RuntimeException('must be an integer');
                }
            } 
            
            public function setId_document_files_ar($f){
                if(preg_match('#^[0-9\,]+$#',$f)){
					$n=strlen($f);
					$this->id_document_files_ar= ($f[$n-1] ==',') ? substr($f, 0, $n-1 ) : $f;
					return true;
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
                    $this->resume=htmlentities($l, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::RESUME_INVALID;
                    return false;
                }
            } 
            
            public function setSerie($s){
				if(is_string($s) && strlen($s)<=256){
                    $this->serie=htmlentities($s, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::SERIE_INVALID;
                    return false;
                }
			}
            
    /*
        Autres méthodes
    */
        public function isValidForm(){
            return (isset($this->auteur) && isset($this->date) && isset($this->editeur) && isset($this->id_ebookCategorie_genre) &&
            isset($this->id_etiquette_etiquette)&& isset($this->langue));
        }
        
        public function isValid($form=false){
            return ($this->isValidForm() && isset($this->id_image_miniature));
        }
 }
