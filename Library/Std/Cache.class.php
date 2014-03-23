<?php
/*
 * name: Cache
 * @description :  
 */
	
class Cache{
    /*
        Attributs
    */  
		protected $mc=null; //Memcached instance
		protected $cacheStructure=array(); 
		protected $size=0;
        /*
            Constantes
        */
			const DIR='../Cache/';
			const DEFAULT_TIME=300;
    /*
        Méthodes générales
    */
		public function __construct(){
			$this->initServers();
		}
        /*
            Getters
        */
			public function getStructure(){
				return $this->cacheStructure;
			}
        /*
            Setters
        */

    /*
        Autres méthodes
    */
		protected  function getDefaultTime($app, $mod, $name){
			$app=strtolower($app);
			$mod=strtolower($mod);
			$name=strtolower($name);
			
			$times=['frontend'=>[
						'ebook'=>['insertIndex'=>300]
						]
					];
			if(in_array($app, $times) && in_array($mod, $time[$app]) && in_array($name, $time[$app][$mod])){
				return $time[$app][$mod][$name];
			}else{
				return self::DEFAULT_TIME;
			}
		}
		
		public  function save($app, $mod, $name, $buf){
			if(!is_dir(self::DIR.$app.'/'.$mod)){
				mkdir(self::DIR.$app.'/'.$mod,  $mode = 0711, true);
			}
			file_put_contents(self::DIR.$app.'/'.$mod.'/'.$name, $buf);
		}
		
		/*
		 * 
		 * name: getCache
		 * @param fct fonction anoarg array [0=>arg1 .. n=>argn] permet de passer des arguments à la fct annonymes
		 * @return
		 * 
		 */
		public  function getCache($app, $mod, $name, $fn, $arg=null, $time=null ){
			//On prend la langue en compte
			$name.='_'.$_SESSION['lang'].'.cache';
			$file=self::DIR.$app.'/'.$mod.'/'.$name;
			empty($time) ? $time=self::getDefaultTime($app, $mod, $name) :null;
			if(is_file($file) && filemtime($file)>(time()-$time)){
				return file_get_contents($file);
			}else{
				$buffer= $fn($arg);
				self::save($app, $mod, $name, $buffer);
				return $buffer;
			}
		}
		
		//Ie trier par app et module puis par ordre alphabétique;
		//App et mod sont des paramêtre utilisé uniquement par la fct
		public function buildCacheStructure($directory=null, $app="", $mod=""){
			$directory=empty($directory) ? self::DIR : $directory;
			empty($app) ? $this->size=0 : null;
			foreach(scandir($directory) as $key => $element){
				if($element !='.' && $element !='..'){
					if(is_dir($directory.'/'.$element)){
						$mod = ( !empty($app) ) ? $element : "";
						
						if(empty($mod))						
							$this->buildCacheStructure($directory.'/'.$element, $element);
						else
							$this->buildCacheStructure($directory.'/'.$element, $app, $mod);
						
					}else{
						$size=filesize($directory.'/'.$element);
						$this->cacheStructure[]=new CacheElement(['app'=>$app,
											'mod'=>$mod,
											'name'=>$element,
											'size'=>$size]);
						$this->size+=$size;
					}
				}
			}
		}
		
		public function dropCache($directory=null){
			$directory=empty($directory) ? self::DIR : $directory;
		
			foreach(scandir($directory) as $key => $element){
				if($element !='.' && $element !='..'){
					if(is_dir($directory.'/'.$element)){
						$this->dropCache($directory.'/'.$element);
						rmdir($directory.'/'.$element);
					}else
						unlink($directory.'/'.$element);
					
				}
			}
 
		}

		public function unlink($app, $mod, $name){
			return unlink(self::DIR.'/'.$app.'/'.$mod.'/'.$name);
		}
		/*
		 * Cache en mémoire vive
		 */
		public  function initServers(){
			$this->mc = new Memcached(); 
			$this->mc->addServer("localhost", 11211);
		}
		
		//Compatibilité assendante
		public function getAPCCache($name, $fn, $arg=null){
			return $this->getMemCache($name, $fn, $arg);
		}
		
		public function getMemCache($name, $fn, $arg=null){			
			if(! ($buffer = $this->mc->get($name)) ){
					if($this->mc->getResultCode() == Memcached::RES_NOTFOUND){
						if(is_array($fn))
							$buffer=$fn['obj']->$fn['methode']($arg);
						else
							$buffer=$fn($arg);
							
						$this->mc->set($name, $buffer);
					}else
						throw new Exception('Erreur memcached inconnue');
			}
			return $buffer;
		}
		
		public function dropMemCache(){
			return $this->mc->deleteMulti( $this->mc->getAllKeys() );
		}

		public function getMemStats(){
			return $this->mc->getStats();
		}
		
		public function getStats(){
			$this->buildCacheStructure();	
			$stats=[
				'nbr'=>count($this->cacheStructure),
				'size'=>$this->size,
				'freeSpace'=>disk_free_space(self::DIR)
			];
		
			return $stats;
		}
}
