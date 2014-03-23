<?php
/*
 * name: ReferenceRecord
 * @description :  
 */

class ReferenceRecord extends Record{
    /*
        Attributs
    */
		protected $accreditation=1;
		protected $application;
        protected $link;
        protected $statut=1;
        
        //En fct de la langue
        protected $label_fr;
        protected $module_fr;
        protected $categorie_fr;
        
        
        /*
            Constantes
        */
            const INVALID_APPLICATION='<strong>application</strong> : Longueur maximale 256 charactères';
            const INVALID_LIEN='<strong>Link</strong> : Longueur maximale 256 charactères';
            const INVALID_CATEGORIE='<strong>Link</strong> : Longueur maximale 50 charactères, [a-zA-Z0-9_@àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ -]';
            const INVALID_GROUPE='<strong>Link</strong> : Longueur maximale 50 charactères, [a-zA-Z0-9_@àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ -]';
            const INVALID_MODULE='<strong>Link</strong> : Longueur maximale 50 charactères, [a-zA-Z0-9_@àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ -]';
    /*
        Méthodes générales
    */
        
        /*
            Getters
        */
			public function getAccreditation(){
                return $this->accreditation;
            }
            public function getApplication(){
                return $this->application;
            }   
            
            public function getLink(){
                return $this->link;
            }   
            
            public function getStatut(){
                return $this->statut;
            }
            
            
            public function getLabel_fr(){
				return $this->label_fr;
			}
            
            public function getModule_fr(){
                return $this->module_fr;
            }
            
            public function getCategorie_fr(){
                return $this->categorie_fr;
            }    
        
        /*
            Setters
        */
			public function setAccreditation($accreditation){
                if(is_numeric($accreditation)){
                    $this->accreditation=(int)$accreditation;
                    return true;
                }else{
                    return false;
                }
            }
            
			public function setApplication($app){
                if(is_string($app) && strlen($app)<=256){
                    $this->application=$app;
                    return true;
                }else{  
                    $this->erreurs[]=slf::INVALID_APPLICATION;
                    return false;
                }
            }
            
            public function setLink($link){
                if(is_string($link) && strlen($link)<=256){
                    $this->link=$link;
                    return true;
                }else{  
                    $this->erreurs[]=slf::INVALID_LIEN;
                    return false;
                }
            }
            
            public function setStatut($statut){
                if(is_numeric($statut)){
                    $this->statut=(int)$statut;
                    return true;
                }else{
                    return false;
                }
            }
            
            
            public function setLabel_fr($label){
                if(is_string($label) && strlen($label)<=50){
                    $this->label_fr=$label;
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_GROUPE;
                    return false;
                }
            }
            
            public function setModule_fr($module){
                if(is_string($module) && strlen($module)<=50){
                    $this->module_fr=$module;
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_MODULE;
                    return false;
                }
            }
            
            public function setCategorie_fr($categorie){
                if(is_string($categorie) && strlen($categorie)<=50){
                    $this->categorie_fr=$categorie;
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_CATEGORIE;
                    return false;
                }
            }
            
            
    /*
        Autres méthodes
    */
        public function isValidForm(){
            if(isset($this->label_fr) && isset($this->module_fr) && isset($this->categorie_fr) 
            && isset($this->link) && isset($this->application) && isset($this->accreditation) && isset($this->statut)){
				return true;
            }else{
                return false; 
            }   
        }
        public function isValid(){
			return $this->isValidForm();
		}
}
