<?php
/*
 * name: TopicRecord
 * @description :  
 */
 
class TopicRecord extends Record{
    /*
        Attributs
    */
		protected $forumId;
		protected $titre;
		protected $id_user_createur;
		protected $langue;
		protected $vues;
		protected $dateCreation;
		protected $genre;
		protected $id_post_lastPost;
		protected $id_post_firstPost;
		protected $posts=0;
        /*
            Constantes
        */
			const TITRE_INVALID='titre|=|30 caractères max';
    /*
        Méthodes générales
    */
        /*
            Getters
		*/
			public function getForumId(){
				return $this->forumId;
			}
			
			public function getTitre(){
				return $this->titre;
			}
			
			public function getId_user_createur(){
				return $this->id_user_createur;
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
			
			public function getId_post_lastPost(){
				return $this->id_post_lastPost;
			}
			
			public function getId_post_firstPost(){
				return $this->id_post_firstPost;
			}
			
			public function getPosts(){
				return $this->posts;
			}
        /*
            Setters
        */
			public function setForumId($id){
				if(is_numeric($id)){
					$this->forumId=(int)$id;
				}else{
					throw new RuntimeException('must be an integer');
				}
			}
			
			public function setTitre($t){
				if(is_string($t) && $t!="" && strlen($t)<61){
					$this->titre=htmlentities($t, ENT_NOQUOTES, 'utf-8', false);
				}else{
					$this->erreurs[]=self::TITRE_INVALID;
					return false;
				}
			}
			
			public function setId_user_createur($id){
				if(is_numeric($id)){
					$this->id_user_createur=(int)$id;
				}else{
					throw new RuntimeException('must be an integer');
				}
			}
			
			public function setLangue($e){
				if(is_string($e) && strlen($e)<=256){
                    $this->langue=htmlentities($e, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::LANGUE_INVALID;
                    return false;
                }
			}
			
			public function setVues($v){
				if(is_numeric($v) && $v>=0){
					$this->vues=(int)$v;
				}else{
					throw new RuntimeException('must be a positive integer');
				}
			}
			
			public function setPosts($p){
				if(is_numeric($p) && $p>=0){
					$this->posts=(int)$p;
				}else{
					throw new RuntimeException('must be a positive integer');
				}
			}
			
			public function setDateCreation($d){
				$this->dateCreation=$d;
			}
			
			public function setGenre($g){
				$this->genre=$g;
			}
			
			public function setId_post_lastPost($id){
				if(is_numeric($id)){
					$this->id_post_lastPost=(int)$id;
				}else{
					throw new RuntimeException('must be an integer');
				}
			}	
			
			public function setId_post_firstPost($id){
				if(is_numeric($id)){
					$this->id_post_firstPost=(int)$id;
				}else{
					throw new RuntimeException('must be an integer');
				}
			}
    /*
        Autres méthodes
    */
		public function incrVues(){
			$this->vues++;
		}
		
		public function incrPosts(){
			$this->posts++;
		}
		public function decrPosts(){
			$this->posts--;
		}
		
		public function isValidForm(){
			return ( isset($this->titre) );
		}
		
		public function isValid(){
			return ( $this->isValidForm() && isset($this->id_user_createur) && isset($this->langue) );
		}
}
