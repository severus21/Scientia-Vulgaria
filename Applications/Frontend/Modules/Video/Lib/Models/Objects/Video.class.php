<?php
/*
 * name: Video
 * @description : 
 */

class Video extends File{ 
    /*
        Attributs
    */
        protected $acodec;
        protected $bitrate;
        protected $duration;
        protected $framerate;
        protected $height;
        protected $streaming;
        protected $vcodec;
        protected $width;
        
        protected $statut;
        protected $lastStatutTime;
        /*
            Constantes
        */
            const DIR='../Web/Files/Videos';
            const MAX_TIME=83400;
    /*
        Méthodes générales
    */
		public function __clone(){
			if(isset($this->streaming))
				$this->streaming =clone($this->streaming );
			parent::__clone();
		}
        /*
            Getters
        */
            public function getAcodec(){
                return $this->acodec;
            }
            
            public function getBitrate(){
                return $this->bitrate;
            }
            
            public function getDuration(){
                return $this->duration;
            }
            
            public function getFramerate(){
                return $this->framerate;
            }
            
            public function getHeight(){
                return $this->height;
            }
            
            public function getStreaming(){
                return $this->streaming;
            }
            
            public function getVcodec(){
                return $this->vcodec;
            }
            
            public function getWidth(){
                return $this->width;
            }
            
            public function getStatut(){
				return $this->statut;
			}
            
            public function getLastStatutTime(){
				return $this->lastStatutTime;
			}
            
            static public function getAllowedTypes(){
                return array('video/x-matroska', 'video/avi', 'video/msvideo', 'video/mpeg', 
                'video/x-msvideo', 'video/webm', 'video/ogg', 'video/x-flv', 'video/flv');
            }
        /*
            Setters
        */
            public function setAcodec($a){
                $this->acodec=$a;
            }
            
            public function setBitrate($b){
                $this->bitrate=(int)$b;
            }
            
            public function setDuration($d){
                $this->duration=$d;
            }
            
            public function setFramerate($f){
                $this->framerate=(int)$f;
            }
            
            public function setHeight($h){
                $this->height=(int)$h;
            }
            
            public function setStreaming($s){
                $this->streaming=$s;
            }
            
            public function setVcodec($v){
                $this->vcodec=$v;
            }
            
            public function setWidth($w){
                $this->width=(int)$w;
            }

			public function setStatut($s){
				$this->statut=$s;
			}
			
			public function setLastStatutTime($lt){
				$this->lastStatutTime=$lt;
			}
	
    /*
        Autres méthodes
    */
        static public function type2Ext($t){
            $table=array('video/x-matroska'=>'mkv', 'video/avi'=>'avi', 'video/msvideo'=>'avi', 'video/mpeg'=>'mp4', 
            'video/x-msvideo'=>'avi', 'video/webm'=>'webm', 'video/ogg'=>'ogg', 'video/x-flv'=>'flv', 'video/flv'=>'flv');
             if(array_key_exists($t, $table)){
                return $table[$t];
            }
            throw new RuntimeException('Extension inconnue');
        }
        
        public function getStreamHtmlPath(){
			return '/'.substr($this->path, 7);
		}
}
