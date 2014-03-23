<?php
/*
 * name: MultimediaRecord
 * @description :  
 */
 
abstract class MultimediaRecord extends Record{
    /*
        Attributs
    */
        protected $date;
        protected $langue;
        protected $nom;
        protected $login;
        
        /*
            Constantes
        */
			const LANGUE_INVALID='langue';
			const DATE_INVALID='date';
			const NOM_INVALID='nom';
    /*
        Méthodes générales
    */
        
        /*
            Getters
        */
            public function getDate(){
                return $this->date;
            }
                        
            public function getLangue(){
                return $this->langue;
            }
            
            public function getNom(){
                return $this->nom;
            }
            
            public function getLogin(){
				return $this->login;
			}
        /*
            Setters
        */
            public function setDate($d){//vérifier par le form ou donnée par le serveur
                if(isset($d))
                    $this->date=$d;
                else
					$this->erreurs[]=self::DATE_INVALID;
                
            }
            
			public function setLangue($e){
                if(is_string($e) && strlen($e)<=256)
                    $this->langue=htmlentities($e, ENT_NOQUOTES, 'utf-8', false);
                else
                    $this->erreurs[]=self::LANGUE_INVALID;
                
            }
            
            public function setNom($t){
				if(!empty($t) && strlen($t))
					$this->nom=stripslashes(trim(str_replace('\r\n', '<br/>', htmlentities($t, ENT_NOQUOTES, 'utf-8', false))));
				else
					$this->erreurs[]=self::NOM_INVALID;
            }
            
			public function setLogin($l){//pas besoin de traitement supplémentaire car le login provient d'un objet user
                if(isset($l))
                    $this->login=$l;
                
            }           
    /*
        Autres méthodes
    */
 }
