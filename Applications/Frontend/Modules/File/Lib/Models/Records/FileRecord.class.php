<?php
/*
 * name: FileRecord
 * @description : 
 */

class FileRecord extends Record{ 
    /*
        Attributs
    */
        protected $path;
        protected $ext;
        protected $type;
        protected $login;
        protected $size;
        protected $md5;
        protected $sha512;
        protected $description;
        
        /*
            Constantes
        */
            const INVALID_PATH='path|=|Caractères invalides';
            const INVALID_TYPE='type|=|Doit être une chaine de caractère';
            const INVALID_SIZE='size|=|Doit être un nombre';
            const INVALID_MD5='md5|=|Erreur inconnu';
            const INVALID_SHA512='sha512|=|Erreur inconnu';
            const INVALID_DESCRIPTION='description|=|Doit être une chaine de caractère';
    /*
        Méthodes générales
    */
        /*
            Getters
        */
            public function getPath(){
                return $this->path;
            }
            
            public function getDescription(){
                return $this->description;
            }
            
            public function getExt(){
                return $this->ext;
            }
            
            public function getLogin(){
                return $this->login;
            }
            
            public function getType(){
                return $this->type;
            }
            
            public function getSize(){
                return $this->size;
            }
            
            public function getMd5(){
                return $this->md5;
            }
            
            public function getSha512(){
                return $this->sha512;
            }
            
        /*
            Setters
        */
            public function setDescription($desc){
                 if(is_string($desc) && strlen($desc)<257){
                    $this->description=htmlentities($desc, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_DESCRIPTION;
                    return false;
                }
            }
            
            public function setPath($path){
                if(is_string($path) && preg_match('#^[./a-zA-Z0-9-]+$#', $path)){
                    $this->path=$path;
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_PATH;
                    return false;
                }
            }
                      
            
            public function setExt($ext){
                if(is_string($ext)){
                    $this->ext=$ext;
                    return true;
                }else{
                    return false;
                }
            }
            
              
            public function setLogin($l){
                if(is_string($l)){
                    $this->login=$l;
                    return true;
                }else{
                    return false;
                }
            }
                
            
            public function setType($type){
                if(is_string($type)){
                    $this->type=htmlentities($type, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_TYPE;
                    return false;
                }
            }
            
            public function setSize($size){
                if(is_numeric($size)){
                    $this->size=$size;
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_SIZE;
                    return false;
                }
                
            }
            
            public function setMd5($md5){
                if(isset($md5)){
                    $this->md5=$md5;
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_MD5;
                    return false;
                }
            }
            
            public function setSha512($sha512){
                if(isset($sha512)){
                    $this->sha512=$sha512;
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_SHA512;
                    return false;
                }
            }
            
    /*
        Autres méthodes
    */
        public function isValidForm(){
            return true;
        }
        
        public function isValid(){
            return ($this->isValidForm() && isset($this->path) && isset($this->type) && isset($this->size) &&
            isset($this->md5) && isset($this->sha512));
        }
}
