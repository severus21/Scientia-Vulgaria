<?php
/*
 * name: FileProcess
 * @description :  traitement des fichiers
 */

class FileProcess{
    /*
        Attributs
    */
        protected $dir;
        protected $file;
        
        /*
            Constantes
        */

    /*
        Méthodes générales
    */
        public function __construct($file=null){
            if(isset($file))
                $this->setFile($file);
        }
        
        /*
            Getters
        */
        /*
            Setters
        */
            public function setDir(){
                if(empty($this->dir)){
                    preg_match('#(.+)/[a-zA-Z0-9]+.[a-zA-Z0-9]+$#i', $this->file->getPath(), $matches);
                    $this->dir=$matches[1];
                }
            }
            
            public function setFile(File $file){
                $this->file=$file;
                $this->setDir();
            }
    /*
        Autres méthodes
    */
        public function create($mod=0777){
            !is_dir($this->dir)?mkdir($this->dir, $mod, true):null;
            $ressource=fopen($this->file->getPath(), $mod);
            return fclose($ressource, 'w');
        }
        
        		
		public function download($name='noname'){
				ob_end_clean(); // la ligne à ajouter 
				header("Content-Type: {$this->file->getType()}"); 
				header("Content-Description: File Transfer");
				header("Content-Transfer-Encoding: binary"); 
				header("Content-Length:{$this->file->getSize()}"); 
				header("Content-Disposition: attachment; filename={$name}.{$this->file->getExt()}"); 
				header("Expires: 0"); 
				header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0"); 
				header("Pragma: no-cache"); 
				@ob_end_flush();
				readfile($this->file->getPath());
				exit;
		}
        
        public function hashFile($algo){
            return hash_file($algo, $this->file->getPath());
        }
        
        public function hydrateFromFile($path){
            if(is_file($path)){
                $this->file->getRecord()->setSize(filesize($path));
                filetype($path)!='file' ? $this->file->getRecord()->setType(filetype($path)) : null; 
                $this->file->getRecord()->setMd5($this->hashFile('md5'));
                $this->file->getRecord()->setSha512($this->hashFile('sha512'));
                return true;
            }else{
                return false;
            }
        }
        
        
        public function move($dest){
            if(copy($this->file->getPath(), $destination)){
                if(unlink($this->file->getPath())){
                    return true;
                }
            }
            return false;
        }
        
        public function rename($newName){
            return rename($this->file->getPath(), $this->dir.'/'.$newName);
        }
        
        public function unlink(){
            if(!unlink($this->file->getPath())){
                return true;
            }else{
                return false;
            }
        }      
}
