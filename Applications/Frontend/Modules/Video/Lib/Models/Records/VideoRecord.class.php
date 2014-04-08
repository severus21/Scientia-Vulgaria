<?php
/*
 * name: VideoRecord
 * @description : 
 */

class VideoRecord extends FileRecord{ 
    /*
        Attributs
    */
        protected $acodec;
        protected $bitrate;
        protected $duration;
        protected $framerate;
        protected $height;
        protected $id_video_streaming;
        protected $vcodec;
        protected $width;
        protected $statut='waiting'; // done : convertion effectuée, processing en cours, waiting en attente, rien si video enfant
        protected $lastStatutTime=0;//Timestamp du dernier chagement de statut, 0 default aucun traitement n'a jamais était fait
        
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
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
            
            public function getId_video_streaming(){
                return $this->id_video_streaming;
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
            
        /*
            Setters
        */
            public function setAcodec($a){
                $this->acodec=htmlentities($a, ENT_NOQUOTES, 'utf-8', false);
            }
            
            public function setBitrate($b){
                if(is_numeric($b) || $b=='N/A'){
                    $this->bitrate=(int)$b;
                }else{
                   // throw new RuntimeException('bitrate must be an integer');
                }
            }
            
            public function setDuration($d){
                $this->duration=htmlentities($d, ENT_NOQUOTES, 'utf-8', false);
            }
            
            public function setFramerate($f){           
                if(is_numeric($f)){
                    $this->framerate=(int)$f;
                }else{
                    throw new RuntimeException('framerate must be an integer');
                }
            }
            
            public function setHeight($h){
                if(is_numeric($h)){
                    $this->height=(int)$h;
                }else{
                    throw new RuntimeException('height must be an integer');
                }
            }
            
            public function setId_video_streaming($s){
                if(is_numeric($s)){
                    $this->id_video_streaming=$s;
                }else{
                    throw new RuntimeException('id_video_streaming must be an integer');
                }
                
            }
            
            public function setVcodec($v){
                $this->vcodec=htmlentities($v, ENT_NOQUOTES, 'utf-8', false);
            }
            
            public function setWidth($w){
                if(is_numeric($w)){
                    $this->width=(int)$w;
                }else{
                    throw new RuntimeException('width must be an integer');
                }
            }

			public function setStatut($s=''){
				if($s=='done'  || $s=='processing' || $s=='waiting' || $s==''){
					$this->statut=$s;
				}else{
					throw new RuntimeException('Statut unknow');
				}
			}
			
			public function setLastStatutTime($lt=0){
				if(is_numeric($lt))
					$this->lastStatutTime=$lt;
				else
					$this->lastStatutTime=0;
			}
    /*
        Autres méthodes
    */
        public function isValid(){
            return $this->isValidForm();
        }
}
