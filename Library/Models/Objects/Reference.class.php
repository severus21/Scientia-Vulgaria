<?php
/*
 * name: Reference
 * @description :  
 */

class Reference extends Objects{
    /*
        Attributs
    */
        protected $application;
        protected $link;
        protected $accreditation=1;
        protected $statut=1;
        
        protected $label;
        protected $module;
        protected $categorie;
       
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        public function hydrate(array $data){
			parent::hydrate($data);
			$this->setLabel($data['label_'.$_SESSION['lang']]);
			$this->setModule($data['module_'.$_SESSION['lang']]);
			$this->setCategorie($data['categorie_'.$_SESSION['lang']]);
			
		}
        /*
            Getters
        */
			public function getApplication(){
				return $this->application;
			}
			
            public function getLink(){
                return $this->link;
            }   
            
            public function getAccreditation(){
                return $this->accreditation;
            }
            
            public function getStatut(){
                return $this->statut;
            }

			public function getLabel(){
				return $this->label;
			}	
            public function getModule(){
                return $this->module;
            }
            
            public function getCategorie(){
                return $this->categorie;
            }
        
        /*
            Setters
        */
            public function setApplication($app){
                $this->application=$app;
            }
            
            public function setLink($link){
                $this->link=$link;
            }
            
            public function setAccreditation($accreditation){
                $this->accreditation=(int)$accreditation;
            }
            
            public function setStatut($statut){
                $this->statut=(int)$statut;
            }
            
            public function setLabel($label){
                $this->label=$label;
            }
            
            public function setModule($module){
                $this->module=$module;
            }
            
            public function setCategorie($categorie){
                $this->categorie=$categorie;
            }
            
            
    /*
        Autres méthodes
    */

}
