<?php
/*
 * name: ThotDatabaseRecord
 * @description :  
 */
 
class ThotDatabaseRecord extends Record{
    /*
        Attributs
    */
        protected $nom;
        protected $type;
        protected $nbr=0;
 
        /*
            Constantes
        */ 
			const NOM_INVALID='nom';
			const TYPE_INVALID='type';
    /*
        Méthodes générales
    */
        
        /*
            Getters
        */            
            public function getNom(){
				return $this->nom;
			}
            
            public function getType(){
                return $this->type;
            }
            
            public function getNbr(){
                return $this->nbr;
            }
        /*
            Setters
        */
           public function setNom($n){
				if(is_string($n) && strlen($n)<256)
					$this->nom=$n;
				else
					$this->erreurs[]=NOM_INVALID;
			}
            
            public function setType($t){
                if(is_string($t) && strlen($t)<256)
					$this->type=$t;
				else
					$this->erreurs[]=TYPE_INVALID;
            }
            
            public function setNbr($n){
                $this->nbr=$n;
            }      
    /*
        Autres méthodes
    */
		public function incrNbr(){
			$this->nbr++;
		}
		
		public function isValidForm(){
			return ( !empty($this->nom) && !empty($this->type) );
		}
        
        public function isValid(){
            return $this->isValidForm();
        }
 }
