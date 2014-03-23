<?php
/*
 * name: News
 * @description :  
 */
 
class News extends Objects{
    /*
        Attributs
    */
		protected $createur;
		protected $app;
		protected $accreditation;
		protected $statut;
		protected $textHtml;
		protected $textBBCode;
		protected $dateCreation;
		
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
			public function setCreateur($c){
				$this->createur=$c;
			}
			
			public function setApp($a){
				$this->app=$a;
			}
			
			public function setAccreditation($a){
				$this->accreditation=$a;
			}
			
			public function setStatut($a){
				$this->statut=$a;
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
    /*
        Autres méthodes
    */
		public function toString(){
			$buffer='<div class="news">
				<header>
					Published by '.$this->createur->getLogin().' on '.$this->dateCreation.'
				</header>
				<p>
				'.$this->textBBCode.'
				</p>
			</div>
			';
			return $buffer;
		}
    
		public function getUpdateLink($linkOnly=false, $class='', $value='Modifier'){
			if($linkOnly)
				return '/backend/news/update-'.$this->getRecord()->getId();
			else 
				return '<a class="'.$class.'" href="/backend/news/update-index-'.$this->getRecord()->getId().'">'.$value.'</a>';
		}
		
		public function getDeleteLink($linkOnly=false, $class='', $value='Suppr'){
			if($linkOnly)
				return '/backend/news/update-'.$this->getRecord()->getId();
			else 
				return '<a class="'.$class.'" href="/backend/news/delete-'.$this->getRecord()->getId().'">'.$value.'</a>';
		}

}
