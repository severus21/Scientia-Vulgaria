<?php
/*
 * name: SupportRecord
 * @description : 
 */
class SupportRecord extends Record{
     /*
        Attributs
    */
        protected $description;
        protected $categorie;
        protected $url;
        protected $userAgent;
        protected $login;
        protected $date;
        
        /*
            Constantes
        */
            const INVALID_DESCRIPTION='description|=|Doit être une chaîne de 2000 caractères au plus';
            const INVALID_CATEGORIE='categorie|=|Catégorie non référencée';
            const INVALID_URL='url|=|Doit être une chaine de caractère de 256 caractères au plus';
    /*
        Méthodes générales
    */    
        /*
            Getters
        */
            public function getDescription(){
                return $this->description;
            }
            
            public function getCategorie(){
                return $this->categorie;
            }
            
            public function getUserAgent(){
                return $this->userAgent;
            }
            
            public function getLogin(){
                return $this->login;
            }
            
            public function getDate(){
                return $this->date;
            }
            
            public function getUrl(){
                return $this->url;
            }
            
        /*
            Setters
        */
            public function setDescription($d){
                if(is_string($d) && strlen($d)<2001){
                    $this->description=htmlentities($d, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_DESCRIPTION;
                    return false;
                }
            }
            
            public function setCategorie($c){
                $categories=Support::getCategoriesAllowed();
                if(is_numeric($c) && array_key_exists($c, $categories)){
                    $this->categorie=$categories[$c];
                    return true;
                }elseif(is_string($c) && in_array($c, $categories)){
                    $this->categorie=$c;
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_CATEGORIE;
                    return false;
                }
            }
            
            public function setUrl($u){
                if(is_string($u) && strlen($u)<257){
                    $this->url=htmlentities($u, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_URL;
                    return false;
                }
            }
            
            public function setUserAgent($u){
                $this->userAgent=$u;
                return true;
            }
            
            public function setLogin($l){//pas besoin de traitement supplémentaire car le login provient d'un objet user
                if(isset($l)){
                    $this->login=$l;
                    return true;
                }
                return false;
            }
  
            public function setDate($d){//de même c'est le serveur qui donne la date
                if(isset($d)){
                    $this->date=$d;
                    return true;
                }
                return false;
            }
   /*
        Autres méthodes
    */  
        public function isValidForm(){
            return (isset($this->description) && isset($this->categorie) && isset($this->url));
        }
        
        public function isValid(){
            return ($this->isValidForm() && isset($this->login) && isset($this->userAgent) && isset($this->date));
        }
}
