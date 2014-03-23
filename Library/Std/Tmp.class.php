<?php
/*
 * name: Tmp
 * @description :  traitement des chaines de caractères
 */

class Tmp{

    /*
        Attributs
    */
        private $dir;
        private $path;
        private $times;
        /*
            Constantes
        */
           const DIR='../Web/Tmp';
    /*
        Méthodes générales
    */
        public function __construct($dir=self::DIR){
             
            // Si le dossier n'existe pas
            if( !is_dir($dir) ){
                trigger_error("<code>$path</code> n'existe pas", E_USER_WARNING);
                return false;
            }
             
            $this->times=$this->getTimes();
            $this->dir=$dir;
            $this->clearTmp($this->dir);
        }
        /*
            Getters
        */
            public function getTimes(){
                return [realpath(self::DIR.'/Main/Captcha')=>30];
            }
        /*
            Setters
        */
    
    /*
        Autres méthodes
    */
        public function path($app, $mod){
            $this->path=$this->dir.'/'.$app;
            if(!is_dir($this->path)){
                mkdir($this->path);
            }
            $this->path.='/'.$mod;
            if(!is_dir($this->path)){
                mkdir($this->path);
            }
            $this->path.='/'.String::uniqStr();
            return $this->path;
        }
        
        /*
         * name: clearTmp
         * @param : $directory(str)
         * @return : 
         * description: supprime tous les fichiers temporaires expirés.
         */
        public function clearTmp($directory=self::DIR){
            $elements=scandir($directory);
            
            foreach($elements as $key => $element){
                if($element !='.' && $element !='..'){
                    $file=$directory.'/'.$element;
                    if(is_dir($file)){
                        $this->clearTmp($file); 
                    }else{
                        if(array_key_exists($directory, $this->times)){
                            $t=$this->times[$directory];
                        }else{
                            $t=300;
                        }
                        
                        if(filemtime($file)<=(time()-$t)){
                            unlink($file);
                        }
                    }
                }
            }
        }

        


}
