<?php
/*
 * name: Topic
 * @description :  
 */
 
class Topic extends Objects{
    /*
        Attributs
    */
		protected $forumId;
		protected $titre;
		protected $createur;
		protected $langue;
		protected $vues;
		protected $dateCreation;
		protected $genre;
		protected $lastPost;
		protected $firstPost;
		protected $posts;
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
		public function __clone(){
			$this->lastPost =clone($this->lastPost );
			$this->firstPost =clone($this->firstPost );
			$this->createur =clone($this->createur );
			parent::__clone();
		}
        /*
            Getters
		*/
			public function getForumId(){
				return $this->forumId;
			}
			
			public function getTitre(){
				return $this->titre;
			}
			
			public function getCreateur(){
				return $this->createur;
			}
			
			public function getLangue(){
				return $this->langue;
			}
			
			public function getVues(){
				return $this->vues;
			}
			
			public function getDateCreation(){
				return $this->dateCreation;
			}
			
			public function getGenre(){
				return $this->genre;
			}
			
			public function getLastPost(){
				return $this->lastPost;
			}
			
			public function getFirstPost(){
				return $this->firstPost;
			}
			
			public function getPosts(){
				return $this->posts;
			}
        /*
            Setters
        */
			public function setForumId($id){
				$this->forumId=(int)$id;
			}
			
			public function setTitre($t){
				$this->titre=$t;
			}
			
			public function setCreateur($c){
				$this->createur=$c;
			}
			
			public function setLangue($l){
				$this->langue=$l;
			}
			
			public function setVues($v){
				$this->vues=$v;
			}
			
			public function setDateCreation($d){
				$this->dateCreation=$d;
			}
			
			public function setGenre($g){
				$this->genre=$g;
			}
			
			public function setLastPost($p){
				$this->lastPost=$p;
			}	
			
			public function setFirstPost($p){
				$this->firstPost=$p;
			}
			
			public function setPosts($p){
				$this->posts=$p;
			}
    /*
        Autres méthodes
    */
		public function getShowLink($linkOnly=false, $class='', $default=''){
			$module=strtolower(get_class($this));
			$default=='' ? $default=$this->titre : null;
			if($linkOnly)
				return '/'.$module.'/show-'.$this->getRecord()->getId();
			else 
				return '<a class="'.$class.'" href="/forum/'.$module.'/show-'.$this->getRecord()->getId().'">'.$default.'</a>';
		}
}
