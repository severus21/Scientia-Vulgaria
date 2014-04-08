<?php
/*
 * name: File
 * @description : 
 */

class File extends Objects{ 
    /*
        Attributs
    */
        protected $name;
        protected $path;
        protected $ext;
        protected $login;
        protected $type;
        protected $size;
        protected $md5;
        protected $sha512;
        protected $description='';
        
        /*
            Constantes
        */
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
            
            static public function getAllowedTypes(){
				return array();	
			}
        /*
            Setters
        */
            public function setPath($path){
                $this->path=$path;
            }
            public function setExt($ext){
                $this->ext=$ext;
            }

			public function setDescription($desc){
                $this->description=$desc;
            }
            
            public function setLogin($login){
                $this->login=$login;
            }
            
            public function setType($type){
                $this->type=$type;
            }
            
            public function setSize($size){
                $this->size=$size;
            }
            
            public function setMd5($md5){
                $this->md5=$md5;
            }
            
            public function setSha512($sha512){
                $this->sha512=$sha512;
            }
            
            static public function getFobiddenTypes(){
                return array('application/x-bsh', 'application/x-sh', '	application/x-shar', 'text/x-script.sh');
            }
    
    /*
        Autres méthodes
    */
		static public function type2Ext($t){
			return 'tmp';
		}
		
        public function generateHtmlPath(){
			if($this instanceof Image){
				return '/'.substr($this->path, 7);
			}else
				return $this->path;
        }
        
        public function getDownloadLink($module, $idMod, $value='Télécharger'){
			$type=lcfirst(get_class($this));
			return '<a href="../../../'.$module.'/download-'.$idMod.'?f='.$this->getRecord()->getId().'&t='.$type.'">'.$value.'</a>';
		}
		
		public function getShowLink($class='', $value='Voir'){
			$module=strtolower(get_class($this));
			return '<a class="'.$class.'" href="../../../'.$module.'/show-'.$this->getRecord()->getId().'">'.$value.'</a>';
		}

}
