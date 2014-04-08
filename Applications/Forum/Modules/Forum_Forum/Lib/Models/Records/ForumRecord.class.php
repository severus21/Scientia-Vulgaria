<?php
/*
 * name: ForumRecord
 * @description :  
 */
 
class ForumRecord extends Record{
    /*
        Attributs
    */
		protected $id_forumCategorie_categorie;
		protected $name;
		protected $description;
		protected $ordre=0; //ordre par defaut 0 et apres ordre lexico-graphique du nom 
		protected $id_post_lastPost;
		protected $topics=0;
		protected $nbrPosts=0;
		protected $accreditation=User::ACCREDITATION_STANDARDE;
		protected $view_statut=User::STATUT_VISITEUR;
		protected $post_statut=User::STATUT_MEMBRE;
        /*
            Constantes
        */
			const NAME_INVALID='name|=|';
			const DESCRIPTION_INVALID='description|=|';
    /*
        Méthodes générales
    */
        /*
            Getters
		*/
			public function getId_forumCategorie_categorie(){
				return $this->id_forumCategorie_categorie;
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
			public function getId_post_lastPost(){
				return $this->id_post_lastPost;
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
			public function setId_forumCategorie_categorie($id){
				if(is_numeric($id)){
					$this->id_forumCategorie_categorie=(int)$id;
				}else{
					throw new RuntimeException('must be an integer');
				}
			}
			public function setName($name){
				if(is_string($name) && $name!="" && $name<121){
					$this->name=htmlentities($name, ENT_NOQUOTES, 'utf-8', false);
				}else{
					$this->erreurs[]=self::NAME_INVALID;
					return false;
				}
			}
			public function setDescription($d){
				if( is_string($d) && strlen($d)<10000 ){
					$this->description=htmlentities($d, ENT_NOQUOTES, 'utf-8', false);
				}else{
					$this->erreurs[]=self::DESCRIPTION_INVALID;
					return false;
				}
			}
			public function setOrdre($o){
				if(is_numeric($o)){
					$this->ordre=(int)$o;
				}else{
					throw new RuntimeException('must be an integer');
				}
			}
			public function setId_post_lastPost($id){
				if(is_numeric($id)){
					$this->id_post_lastPost=(int)$id;
				}else{
					throw new RuntimeException('must be an integer');
				}
			}
			public function setTopics($t){
				if(is_numeric($t)){
					$this->topics=(int)$t;
				}else{
					throw new RuntimeException('must be a  integer');
				}
			}
			public function setNbrPosts($p){
				if(is_numeric($p) ){
					$this->nbrPosts=(int)$p;
				}else{
					throw new RuntimeException('must be a  integer');
				}
			}
			public function setAccreditation($a){
				if(is_numeric($a) && $a>0){
					$this->accreditation=(int)$a;
				}else{	
					throw new RuntimeException('must be a positive integer');
				}
			}
			public function setView_statut($s){
				if(is_numeric($s) && $s>0){
					$this->view_statut=(int)$s;
				}else{
					throw new RuntimeException('must be a positive integer');
				}
			}
			public function setPost_statut($s){
				if(is_numeric($s) && $s>0){
					$this->post_statut=(int)$s;
				}else{
					throw new RuntimeException('must be a positive integer');
				}
			}
    /*
        Autres méthodes
    */
		public function incrTopics(){
			$this->topics++;
		}
		public function decrTopics(){
			$this->topics--;
		}
		
		public function incrPosts(){
			$this->nbrPosts++;
		}
		
		public function decrPosts(){
			$this->nbrPosts--;
		}
		
		public function isValidForm(){
			return ( isset($this->name) && isset($this->description) );
		}
		
		public function isValid(){
			return ( $this->isValidForm() && isset($this->id_forumCategorie_categorie) );
		}
}
