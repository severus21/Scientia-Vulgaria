<?php
/*
 * name: VideoProcess
 * @description :  
 */

class VideoProcess extends FileProcess{
    /*
        Attributs
    */ 
        /*
            Constantes
        */
			const THREADS =2;
			const MAX_TIME_CONV=86400;
			
			
            private $patternVideoInfo=['Duration: (\d{0,3}:\d{2}:\d{2}\.\d{0,2}),'=>'setDuration',
            'bitrate: (\d{1,10}) kb/s|(N/A)'=>'setBitrate',
            'Stream \#\d\.\d: Video: ([a-zA-Z0-9]{2,9})'=>'setVcodec',
            '(\d{2,4})x'=>'setWidth',
            'x(\d{2,4}), '=>'setHeight',
            '(\d{1,3}(:?\.\d{1,2})?) fps,'=>'setFramerate',
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
        public function vid2strea(){
			$path=$this->file->getStreaming()->getPath();
            if(!($this->file->getStreaming() instanceof Video ) || empty( $path ) ){
                $this->setDir();
                $path=realpath($this->dir).'/'.String::uniqStr().'.webm';
                $this->file->getStreaming()->setPath($path);
                $this->file->getStreaming()->getRecord()->setPath($path);
                
            }
         
			exec('avconv -i "'.realpath($this->file->getPath()).'" -threads '.self::THREADS.' -vcodec libvpx -b 350k -minrate 0 -maxrate 800k -level 310 -qmin 1 -qmax 20 -acodec libvorbis -ab 64k "'.$path.'"', $ar,$b);
        }
        
        //ne supporte qu'un seul flux video et audio
        public function hydrateFromFile($path, $rec=false){//rec si on veut utiliser ensuite la methode parente      
            if(is_file($path)){
                $req='avconv -i '.realpath($path).' 2>&1';
                $info=shell_exec($req);
                
                foreach($this->patternVideoInfo as $pattern =>$setter){
                    preg_match('#'.$pattern.'#', $info, $matches) ? $this->file->getRecord()->$setter($matches[1]) :null;
                }              
                return parent::hydrateFromFile($path);
            }else{
                return false;
            }
        }
}
