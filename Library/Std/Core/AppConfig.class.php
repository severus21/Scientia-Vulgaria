<?php
/*
 * 
 * name: AppConfig
 * @description permet :
 *          de gérer la configuration.
 * 
 */

class AppConfig{
    /*
        Attributs
    */
		protected $action;
        protected $app;
        protected $module;
        protected $defaultArray=['define'=>array(),
								'css'=>array(),
								'js'=>array(), 
								'flash'=>array(), 
								'tooltip'=>array(), 
								'field'=>array(),
								'msg'=>array(),
								'view'=>array(),
								'nav'=>array(),
								'include'=>array()
								];
        protected $nodeName=['#text', '#comment', 'string']; //les cas de base de parseNode
        protected $nodeName2=['define', 'css', 'js', 'include'];
		protected $nodeName3=['flash', 'tooltip', 'field', 'msg', 'view','nav'];
		protected $keyAr1=['define'];
		protected $keyAr2=['flash', 'tooltip', 'field', 'msg', 'view', 'nav'];
		protected $keyAr3=['css', 'js'];
			
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        public function __construct($app, $module, $action=null){
			!empty($action) ? $this->action=$action : null;
			$this->app=$app;
			$this->module=$module;
			$this->nodeName=array_merge($this->nodeName, $this->nodeName2, $this->nodeName3);
		}
        /*
            Getters
        */
            
        /*
            Setters
        */
		public function setModule($m){
			is_string($m) ? $this->module=$m :null;
		}
    /*
        Autres méthodes
    */
		/*name: load
         * @param
         * @return
         *      array : ['define'=>['var1'=>'value1',...,'varn'=>'valuen'], 'css'=>['path1',...'pathn'], 'js'=>['path1',...'pathn']] 
         */
		public function load(){
			return $this->merge($this->loadAppConfig(),$this->loadModuleConfig());
		}
		
		public function loadAppConfig(){
			$path='../Applications/'.ucfirst($this->app).'/Config/config.xml';
			if(!is_file($path))
				return $this->defaultArray;
			$cache=new Cache;
			$cacheName='config'.$this->app.$_SESSION['lang'];
			$conf=$cache->getAPCCache($cacheName, (function($arg){
				$appConfig=new AppConfig($arg['app'], $arg['module'], $arg['action']);
				$config=new Config(['file'=>$arg['path']]);
				
				$actionNode=$config->getByTagName(null, 'general')[0];
				return $appConfig->buildArray($appConfig->parseNode($actionNode, $config));
			}), ['app'=>$this->app, 'module'=>$this->module, 'action'=>$this->action, 'path'=>$path]);
			return $conf;
		}
		
		public function loadModuleConfig(){
			$path='../Applications/'.ucfirst($this->app).'/Modules/'.ucfirst($this->module).'/config.xml';
			if(!is_file($path))
				return $this->defaultArray;
				
				
			//On construit la config general au module
			$cache=new Cache;
			$cacheName='config'.$this->app.$this->module.$_SESSION['lang'];
			$configA=$cache->getAPCCache($cacheName, (function($arg){
				$appConfig=new AppConfig($arg['app'], $arg['module'], $arg['action']);
				$config=new Config(['file'=>$arg['path']]);
				
				$tmpActionNodeA=$config->getByTagName(null, 'action', ['att'=>'name', 'strict'=>true, 'value'=>'all']);
				!empty($tmpActionNodeA) ? $actionNodeA=$tmpActionNodeA[0] : $actionNodeA=null;
				return $appConfig->buildArray($appConfig->parseNode($actionNodeA, $config));
			}), ['app'=>$this->app, 'module'=>$this->module, 'action'=>$this->action, 'path'=>$path]);
			
			//On construit la config relative à l'action
			if(isset( $this->action )){
				$config=new Config(['file'=>$path]);
				$tmpActionNodeB=$config->getByTagName(null, 'action', ['att'=>'name', 'strict'=>true, 'value'=>lcfirst($this->action)]);
				!empty($tmpActionNodeB) ? $actionNodeB=$tmpActionNodeB[0] : $actionNodeB=null;
				return $this->merge($configA, $this->buildArray($this->parseNode($actionNodeB, $config)));
			}
			return $configA; 
		}
		
		public function merge($ar1, $ar2){
			$array=$this->defaultArray;
			foreach($array as $key =>$secondaryAr){
				$array[$key]=array_merge($ar1[$key],$ar2[$key]);
			}
			return $array;
		}
		
		/*
		 * nodeType=1 : <balise>..<balise/>
		 * nodeType=2 : <balise/>
		 * 
		 * 
		 */
		public function parseNode($node, $config){
			$array=$this->defaultArray;
			
			if(empty($node))
				return $array;
				
			foreach($node->childNodes as $child){
				if(!in_array($child->nodeName, $this->nodeName)){
					$array=$this->merge($array, $this->parseNode($child, $config));
				}elseif(in_array($child->nodeName,$this->nodeName2)){
					$array[$child->nodeName][]=$config->buildArray('all', $child);		
				}elseif(in_array($child->nodeName,$this->nodeName3)){
					//On construit l'un tableau avec les str le composant : ['lang1'=>str, 'lang2'=>str....
					$var=$child->getAttribute('var');
					foreach($child->childNodes as $child2){
						if($child2->nodeName=='string'){
							$array[$child->nodeName][$var][ $child2->getAttribute('lang') ]=$child2->getAttribute('value');
						}
					}
					null;
				}
			}
			return $array;
		}
		
		public function buildArray($array){
			$newArray=$this->defaultArray;
			if(empty($array))
				return $newArray;
			
			foreach($array as $key =>$secondaryAr){
				//keyAr1
				if(in_array($key,$this->keyAr1)){
					for($a=0; $a<count($secondaryAr); $a++){
						!empty($secondaryAr[$a]['var']) ? $newArray[$key][ $secondaryAr[$a]['var'] ]=$secondaryAr[$a]['value'] : null;
					}
				//keyAr2
				}elseif(in_array($key,$this->keyAr2)){
					foreach($secondaryAr as $var => $strs){
						isset($strs[ $_SESSION['lang'] ]) ? $newArray[$key][$var]=$strs[ $_SESSION['lang'] ] : $newArray[$key][$var]='';
					}
				//keyAr3
				}elseif(in_array($key,$this->keyAr3)){
					for($a=0; $a<count($array[$key]); $a++){
						!empty($array[$key][$a]['src']) ? $newArray[$key][]=$array[$key][$a]['src'] : null;
					}
				}elseif($key=='include'){
					$tmpConfig=new AppConfig($this->app, '');
					for($a=0; $a<count($array[$key]); $a++){
						if(!empty($array[$key][$a]['src']) ){
							$tmpConfig->setModule($array[$key][$a]['src']);
							$this->merge($array, $tmpConfig->loadModuleConfig());
						}
					}
				}
					
			}	
			return $newArray;
		}
}
