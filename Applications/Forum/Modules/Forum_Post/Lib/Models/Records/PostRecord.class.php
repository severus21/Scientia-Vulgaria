<?php
/*
 * name: PostRecord
 * @description :  
 */
 
class PostRecord extends Record{
    /*
        Attributs
    */
		protected $id_user_createur;
		protected $textHtml;
		protected $textBBCode;
		protected $dateCreation;
		protected $topicId;
		protected $forumId;
		
        /*
            Constantes
        */
			const TEXTBBCODE_INVALID='textHtml';
			const TEXTHTML_INVALID='textBBCode';
    /*
        Méthodes générales
    */
        /*
            Getters
		*/
			public function getId_user_createur(){
				return $this->id_user_createur;
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
			public function setId_user_createur($id){
				if(is_numeric($id)){
					$this->id_user_createur=(int)$id;
				}else{
					throw new RuntimeEwception('must be an integer');
				}
			}
			
			//htmlentities inutile vue que le html provient de l'attribut textBBCode
			public function setTextHtml($t){
				if(is_string($t) && $t!==""){
					$this->textHtml=$t;
				}else{
					$this->erreurs[]=self::TEXTHTML_INVALID;
				}
			}
			
			public function setTextBBCode($t){
				if(is_string($t) && $t!=="" && strlen($t)<10001){
					$this->textBBCode=htmlentities($t, ENT_HTML5, 'utf-8', false);
				}else{
					$this->erreurs[]=self::TEXTBBCODE_INVALID;
				}
			}
			
			public function setDateCreation($d){
				if(is_string($d) && $d!==""){
					$this->dateCreation=$d;
				}else{
					throw new RuntimeException('must be a string');
				}
			}
			
			public function setTopicId($id){
				if(is_numeric($id)){
					$this->topicId=(int)$id;
				}else{
					throw new RuntimeException('must be an integer');
				}
			}
			
			public function setForumId($id){
				if(is_numeric($id)){
					$this->forumId=(int)$id;
				}else{
					throw new RuntimeException('must be an integer');
				}
			}
    /*
        Autres méthodes
    */
		public function isValidForm(){
			return isset($this->textBBCode);
		}
		
		public function isValid(){
			return ( $this->isValidForm() && !empty($this->textHtml));
		}
}
