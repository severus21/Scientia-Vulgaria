<?php
/*
 * name: AudioRecord
 * @description : 
 */

class AudioRecord extends FileRecord{ 
    /*
        Attributs
    */
        protected $acodec;
        protected $bitrate;
        protected $duration;       
        protected $id_audio_streaming;
        
        
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
            
            public function getId_audio_streaming(){
                return $this->id_audio_streaming;
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
            
            public function setId_audio_streaming($s){
                if(is_numeric($s)){
                    $this->id_audio_streaming=$s;
                }else{
                    throw new RuntimeException('id_audio_streaming must be an integer');
                }
                
            }

    /*
        Autres méthodes
    */
        public function isValidForm(){
            return true;
        }
        
        public function isValid(){
            return $this->isValidForm();
        }
}
