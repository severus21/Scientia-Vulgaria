<?php
/*
 * name: SoftwareRecord
 * @description :  
 */
 
class SoftwareRecord extends MultimediaRecord{
    /*
        Attributs
    */
       
        protected $id_softwareCategorie_categorie;
        protected $date;
        protected $description;
        protected $developpeur;
        protected $id_archive_file;
        protected $id_document_tutoriel;
        protected $id_image_miniature;
        protected $license;
        protected $login;
        protected $id_operatingSystem_os;
        protected $version;
        
        /*
            Constantes
        */
            const CATEGORIE_INVALID='categorie';
            const DATE_INVALID='date';
            const DESCRIPTION_INVALID='description';
            const DEVELLOPER_INVALID='developpeur';
            const ID_ARCHIVE_INVALID='id_archive_file';
            const ID_CONTENT_INVALID='id_content_nom';
            const ID_DOCUMENT_INVALID='id_document_tutoriel';
            const ID_IMAGE_INVALID='id_image_miniature';
            const LICENSE_INVALID='license';
            const OS_INVALID='os';
            const VERSION_INVALID='version';
    /*
        Méthodes générales
    */
        
        /*
            Getters
        */
            public function getId_softwareCategorie_categorie(){
                return $this->id_softwareCategorie_categorie;
            }
            
            public function getDate(){
                return $this->date;
            }
            
            public function getDescription(){
                return $this->description;
            }
            
            public function getDeveloppeur(){
                return $this->developpeur;
            }
            
            public function getId_archive_file(){
                return $this->id_archive_file;
            }
            
            public function getId_document_tutoriel(){
                return $this->id_document_tutoriel;
            }
            
            public function getId_image_miniature(){
                return $this->id_image_miniature;
            }
            
            public function getLicense(){
                return $this->license;
            }
            
            public function getLogin(){
                return $this->login;
            }
            
            public function getId_operatingSystem_os(){
                return $this->id_operatingSystem_os;
            }
            
            public function getVersion(){
                return $this->version;
            }
        /*
            Setters
        */
            public function setId_softwareCategorie_categorie($c){
                if(is_numeric($c)){
                    $this->id_softwareCategorie_categorie=$c;
                    return true;
                }else{
                    $this->erreurs[]=self::CATEGORIE_INVALID;
                    return false;
                }
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
            
            public function setDescription($d){
                if(is_string($d) && strlen($d)<=2000){
                    $this->description=htmlentities($d, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::DESCRIPTION_INVALID;
                    return false;
                }
            }
            
            public function setDeveloppeur($d){
                if(is_string($d) && strlen($d)<=256){
                    $this->developpeur=htmlentities($d, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::DEVELLOPER_INVALID;
                    return false;
                }
            }
            
            public function setId_archive_file($i){
                if(is_numeric($i)){
                    $this->id_archive_file=$i;
                    return true;
                }
            }
            
            public function setId_document_tutoriel($i){
                if(is_numeric($i)){
                    $this->id_document_tutoriel=$i;
                    return true;
                }else{
                    throw new RuntimeException('must be an integer');
                }
            }
            
            public function setId_image_miniature($i){             
                if(is_numeric($i)){
                    $this->id_image_miniature=$i;
                    return true;
                }else{
                    throw new RuntimeException('must be an integer');
                }
            }
            
            public function setLicense($l){
                if(isset($l) && is_string($l) && strlen($l)<=256){
                    $this->license=htmlentities($l, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::LICENSE_INVALID;
                    return false;
                }
            }
            
            public function setId_operatingSystem_os($o){
                if(is_numeric($o)){
                    $this->id_operatingSystem_os=$o;
                    return true;
                }else{
                    $this->erreurs[]=self::OS_INVALID;
                    return false;
                }
            }
            
            public function setVersion($v){
                if(isset($v) && is_string($v) && strlen($v)<256){
                    $this->version=$v;
                    return true;
                }else{exit;
                    $this->erreurs[]=self::VERSION_INVALID;
                    return false;
                }
            }
    /*
        Autres méthodes
    */
        public function isValidForm(){
            return (isset($this->id_softwareCategorie_categorie) && isset($this->date) && isset($this->description) && isset($this->license) &&
            isset($this->id_operatingSystem_os) && isset($this->version) && isset($this->langue));
        }
        
        public function isValid($form=false){
            return ($this->isValidForm() &&
            isset($this->id_image_miniature));
        }
 }
