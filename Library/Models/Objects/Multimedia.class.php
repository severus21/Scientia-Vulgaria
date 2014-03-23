<?php
/*
 * name: Multimedia
 * @description :  
 */
 
abstract class Multimedia extends Objects{
    /*
        Attributs
    */
        protected $date;
        protected $login;
        protected $nom;
        protected $langue;
        
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        
        /*
            Getters
        */
            public function getDate(){
                return $this->date;
            }
            
            public function getLogin(){
				return $this->login;
			}
            
            public function getNom(){
                return $this->nom;
            }
            
            public function getLangue(){
                return $this->langue;
            }
        /*
            Setters
        */
            public function setDate($d){
                $this->date=$d;
                return true;
            }
            
            public function setLangue($l){
				$this->langue=$l;
			}
            
            public function setNom($t){
				$this->nom=$t;
            }
            
            public function setLogin($l){
                $this->login=$l;
                return true;
            }           
    /*
        Autres méthodes
    */
		public function getShowLink($linkOnly=false, $class='', $value='Voir'){
			$module=strtolower(get_class($this));
			if($linkOnly)
				return '/'.$module.'/show-'.$this->getRecord()->getId();
			else 
				return '<a class="'.$class.'" href="/'.$module.'/show-'.$this->getRecord()->getId().'">'.$value.'</a>';
		}
 }
