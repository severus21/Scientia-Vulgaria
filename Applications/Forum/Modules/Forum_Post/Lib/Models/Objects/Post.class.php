<?php
/*
 * name: Post
 * @description :  
 */
 
class Post extends Objects{
    /*
        Attributs
    */
		protected $createur;
		protected $textHtml;
		protected $textBBCode;
		protected $dateCreation;
		protected $topicId;
		protected $forumId;
		
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
		public function __clone(){
			$this->createur=clone($this->createur);
			parent::__clone();
		}
        /*
            Getters
		*/
			public function getCreateur(){
				return $this->createur;
			}
			
			public function getTextHtml(){
				return $this->textHtml;
			}
			
			public function getTextBBCode(){
				return $this->textBBCode;
			}
			
			public function getDateCreation(){
				return $this->dateCreation;
			}
			
			public function getTopicId(){
				return $this->topicId;
			}
			
			public function getForumId(){
				return $this->forumId;
			}
        /*
            Setters
        */
			public function setCreateur($c){
				$this->createur=$c;
			}
			
			public function setTextHtml($t){
				$this->textHtml=$t;
			}
			
			public function setTextBBcode($t){
				$this->textBBCode=$t;
			}
			
			public function setDateCreation($d){
				$this->dateCreation=$d;
			}
			
			public function setTopicId($id){
				$this->topicId=(int)$id;
			}
			
			public function setForumId($id){
				$this->forumId=(int)$id;
			}
    /*
        Autres méthodes
    */
		public function buildShowHeader($args){
			if($this->createur->getLogin()==$args['currentUser']->getLogin()){
				$nav=new Forum_PostNavBuilder();
				$content=$nav->buildShowHeader($this->record->getId(), $args['topicRecord']);
			}else{
				$content='';
			}
			return $content;
		}
}
