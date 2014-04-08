<?php
/*
 * name: Audio
 * @description : 
 */

class Audio extends File{ 
    /*
        Attributs
    */
        protected $acodec;
        protected $bitrate;
        protected $duration;  
        protected $streaming;
        /*
            Constantes
        */
            const DIR='../Web/Files/Audios';
    /*
        Méthodes générales
    */
		public function __clone(){
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
            
            public function getStreaming(){
                return $this->streaming;
            }
            
            static public function getAllowedTypes(){
                return array('audio/ogg', 'audio/mpeg', 'audio/wav', 'audio/x-flac', 'audio/flac', 'audio/aac');
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
 
            public function setStreaming($s){
                $this->streaming=$s;
            }

    /*
        Autres méthodes
    */
        static public function type2Ext($t){
            $table=array('audio/ogg'=>'ogg', 'audio/mpeg'=>'mp3', 'audio/wav'=>'wav', 'audio/x-flac'=>'flac',
            'audio/flac'=>'flac', 'audio/aac'=>'aac');
            if(array_key_exists($t, $table)){
                return $table[$t];
            }
            throw new RuntimeException('Extension inconnue');
        }
}
