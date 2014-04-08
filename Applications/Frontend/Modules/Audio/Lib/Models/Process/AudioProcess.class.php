<?php
/*
 * name: AudioProcess
 * @description :  
 */

class AudioProcess extends FileProcess{
    /*
        Attributs
    */ 
        /*
            Constantes
        */
            private $patternAudioInfo=['Duration: (\d{0,3}:\d{2}:\d{2}\.\d{0,2}),'=>'setDuration',
            'bitrate: (\d{1,10}) kb/s|(N/A)'=>'setBitrate',
            'Stream \#\d\.\d: Audio: ([a-zA-Z0-9]{2,9})'=>'setAcodec'
            ];                                                                                                                                                                                
    /*
        Méthodes générales
    */
        /*
            Getters
        */
        
        /*
            Setters
        */
    /*
        Autres méthodes
    */
        public function aud2strea($path=null){
            if(empty($path)){
                $this->setDir();
                $path=realpath($this->dir).'/'.String::uniqStr().'.ogg';
                $this->file->setPath($path);
                
            }
            exec('avconv -i "'.realpath($this->file->getPath()).'" -threads 4 -f ogg -acodec libvorbis -ab 64k -ar 44100 -ac 2 "'.$path.'"', $ar,$b);
        }
        
        //ne supporte qu'un seul flux audio et audio
        public function hydrateFromFile($path, $rec=false){//rec si on veut utiliser ensuite la methode parente      
            if(is_file($path)){
                $req='avconv -i '.realpath($path).' 2>&1';
                $info=shell_exec($req);
                
                foreach($this->patternAudioInfo as $pattern =>$setter){
                    preg_match('#'.$pattern.'#', $info, $matches) ? $this->file->getRecord()->$setter($matches[1]) :null;
                }              
                return parent::hydrateFromFile($path);
            }else{
                return false;
            }
        }
}
