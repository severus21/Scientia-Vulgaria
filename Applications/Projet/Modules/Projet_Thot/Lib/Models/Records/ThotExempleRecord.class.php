<?php
/*
 * name: ThotExempleRecord
 * @description :  
 */
 
class ThotExempleRecord extends Record{
    /*
        Attributs
    */
        protected $login;
        protected $id_image_image;
        protected $c;
        protected $id_thotDatabase_database;
 
        /*
            Constantes
        */
    /*
        Méthodes générales
    */  
        /*
            Getters
        */            
            public function getLogin(){
				return $this->login;
			}
            
            public function getId_image_image(){
                return $this->id_image_image;
            }
            
            public function getC(){
                return $this->c;
            }
            
            public function getId_thotDatabase_database(){
                return $this->id_thotDatabase_database;
            }
        /*
            Setters
        */
           public function setLogin($l){
				$this->login=$l;
			}
            
            public function setId_image_image($i){
                $this->id_image_image=$i;
            }
            
            public function setC($c){
				if(is_string($c) && strlen($c)<256)
					$this->c=$c;	
            }
            
            public function setId_thotDatabase_database($d){
                $this->id_thotDatabase_database=$d;
            }           
    /*
        Autres méthodes
    */
		public function isValidForm(){
			return true;
		}
        
        public function isValid($form=false){
            return $this->isValidForm();
        }
 }
