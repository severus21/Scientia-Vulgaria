<?php
/*
 * 
 * name: HTTPRequette
 * @description permet d'obtenir :
 *          une variable POST,
 *          une variable GET,
 *          l'URL entrèe,
 *          un cookie.
 * 
 */

class HttpRequette extends ApplicationComponent{
    /*
        Attributs
    */
        protected $sessionVerified=false;
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
		public function __construct( $app=null){
            parent::__construct($app);
            SessionHandler2::verifySession();
        }
        /*
            Getters
        */
    
        /*
            Setters
        */
    
    /*
        Autres méthodes
    */ 
        public function addGetVar($key, $value){
            $_GET[$key] = $value;
        }
        
        public function addPostVar($key, $value){
            $_POST[$key] = $value;
        }
        
        public function addSessionVar($key, $value){
            $_SESSION[$key] = $value;
        }
        
        public function cookieData($key){
            return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
        }
        
        public function cookieExists($key){
            return isset($_COOKIE[$key]);
        }
        
        public function fileExists($key){
            return isset($_FILES[$key]);
        }
        
        public function fileData($key){
            return isset($_FILES[$key]) ? $_FILES[$key] : null;
        }
        
        public function getData($key){
            return isset($_GET[$key]) ? $_GET[$key] : null;
        }
        
        public function getExists($key){
            return isset($_GET[$key]);
        }
        
        public function method(){
            return $_SERVER['REQUEST_METHOD'];
        }
        
        public function postData($key){
            
            return isset($_POST[$key]) ? $_POST[$key] : null;
        }
        
        public function postExists($key){
            return isset($_POST[$key]);
        }
        
        public function sessionExists($key){
            return isset($_SESSION[$key]);
        }
        
        public function sessionData($key){
            return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
        }
        
        public function sessionUnset($key){
            unset($_SESSION[$key]);
        }
        
        public function getRequetteURI(){
            return $_SERVER['REQUEST_URI'];
            
        }
}
