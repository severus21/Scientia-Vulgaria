<?php
/*
 * name: NewsRecord
 * @description :  
 */
 
class NewsRecord extends Record{
    /*
        Attributs
    */
		protected $id_user_createur;
		protected $app;
		protected $accreditation;
		protected $statut;
		protected $textHtml;
		protected $textBBCode;
		protected $dateCreation;
		
        /*
            Constantes
        */
			const APP_INVALID='app';
			const ACCREDITATION_INVALID='accreditation';
			const STATUT_INVALID='statut';
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
			
			public function getApp(){
				return $this->app;
			}
			
			public function getAccreditation(){
				return $this->accreditation;
			}
			
			public function getStatut(){
				return $this->statut;
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
			
			public function setApp($a){
				if(is_string($a) && strlen($a)>0 && strlen($a)<256){
					$this->app=htmlentities($a, ENT_HTML5, 'utf-8', false);
				}else{
					$this->erreurs[]=self::APP_INVALID;
				}
			}
			
			public function setAccreditation($a){
				if(is_numeric($a))
					$this->accreditation=$a;
				else
					$this->erreurs[]=self::ACCREDITATION_INVALID;
			}
			
			public function setStatut($a){
				if(is_numeric($a))
					$this->statut=$a;
				else
					$this->erreurs[]=self::STATUT_INVALID;
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
