<?php
/*
 * name: Forum
 * @description :  
 */
 
class Forum extends Objects{
    /*
        Attributs
    */
		protected $categorie;
		protected $name;
		protected $description;
		protected $ordre; 
		protected $lastPost;
		protected $topics;
		protected $nbrPosts;
		protected $accreditation;
		protected $view_statut;
		protected $post_statut;
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
		public function __clone(){
			$this->categorie =clone($this->categorie );
			parent::__clone();
		}
        /*
            Getters
		*/
			public function getCategorie(){
				return $this->categorie;
			}
			public function getName(){
				return $this->name;
			}
			public function getDescription(){
				return $this->description;
			}
			public function getOrdre(){
				return $this->ordre;
			}
			public function getLastPost(){
				return $this->lastPost;
			}
			public function getTopics(){
				return $this->topics;
			}
			public function getNbrPosts(){
				return $this->nbrPosts;
			}
			public function getAccreditation(){
				return $this->accreditation;
			}
			public function getView_statut(){
				return $this->view_statut;
			}
			public function getPost_statut(){
				return $this->post_statut;
			}
        /*
            Setters
        */
			public function setCategorie($c){
				$this->categorie=$c;
			}
			public function setName($name){
				$this->name=$name;
			}
			public function setDescription($d){
				$this->description=$d;
			}
			public function setOrdre($o){
				$this->ordre=(int)$o;
			}
			public function setLastPost($p){
				$this->lastPost=$p;
			}
			public function setTopics($t){
				$this->topics=(int)$t;
			}
			public function setNbrPosts($p){
				$this->nbrPosts=(int)$p;
			}
			public function setAccreditation($a){
				$this->accreditation=(int)$a;
			}
			public function setView_statut($s){
				$this->view_statut=(int)$s;
			}
			public function setPost_statut($s){
				$this->post_statut=(int)$s;
			}
    /*
        Autres méthodes
    */
}
