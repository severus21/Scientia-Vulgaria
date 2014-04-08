<?php
/*
 * name: Support
 * @description : 
 */
class Support extends Objects{
	 /*
        Attributs
    */
		protected $description;
		protected $categorie;
		protected $url;
		protected $userAgent;
		protected $login;
		protected $date;
		
		/*
            Constantes
        */
    /*
        Méthodes générales
    */    
        /*
            Getters
        */
			public function getDescription(){
				return $this->description;
			}
			
			public function getCategorie(){
				return $this->categorie;
			}
			
			public function getUrl(){
				return $this->url;
			}
			
			public function getUserAgent(){
				return $this->userAgent;
			}
			
			public function getLogin(){
				return $this->login;
			}
			
			public function getDate(){
				return $this->date;
			}
			static public function getCategoriesAllowed(){
				return array('Grammaire & Orthographe', 'Graphisme', 'Lien invalide', 'Ressource inacessible', 'Autre');
			}
        /*
            Setters
        */
			public function setDescription($d){
				$this->description=$d;
			}
			
			public function setCategorie($d){
				$this->description=$d;
			}
			
			public function setUrl($u){
				$this->url=$u;
			}
			
			public function setUserAgent($u){
				$this->userAgent=$u;
			}
			
			public function setLogin($l){
				$this->login=$l;
			}
  
			public function setDate($d){
				$this->date=$d;

			}//date('Y-m-d');
   /*
        Autres méthodes
    */  
}
