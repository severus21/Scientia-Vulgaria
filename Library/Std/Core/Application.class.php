<?php
/*
 * 
 * name: Application
 * @description permet :
 *           d'exécuter l'application ;
 *           d'obtenir la requête envoyée par le client ;
 *           d'obtenir la réponse que l'on enverra au client ;
 *           de renvoyer son nom.
 * 
 */

abstract class Application{
    /*
        Attributs
    */
        //protected $config;
        protected $httpRequette;
        protected $httpReponse;
        protected $nom;
        protected $user;
        
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        public function __construct(){
           // $this->config = new Config(array('file'=>'../Applications/'.$this->nom.'/Config/app.xml'));
            $this->httpRequette = new HTTPRequette($this);
            $this->httpReponse = new HTTPReponse($this);
            if(!$this->httpRequette->sessionExists('user')){
                $this->user=new User();
            }else{
                $this->user=new User($this->httpRequette->sessionData('user'));
            }
        }
        
        public function __destruct(){
			$this->httpRequette->addSessionVar('user', $this->user->obj2ar());
        }
        /*
            Getters
        */
            public function getHttpRequette()
            {
                return $this->httpRequette;
            }
        
            public function getHttpReponse()
            {
                return $this->httpReponse;
            }
        
            public function getNom(){
                return $this->nom;
            }
            
            public function getConfig(){
                return $this->config;
            }
        
            public function getUser(){
                return $this->user;
            }
        /*
            Setters
        */
            public function setUser(User $user){
                $this->user=$user;
            }
    
    /*
        Autres méthodes
    */
        abstract public function run();
        
		public function loadInclude($module, $action){
			$cache=new Cache();
			$AppConfig=new AppConfig($this->nom,$module, $action);
			$config=$AppConfig->load();
			
			//LoadCss
			$css='';
			if(!empty($config['css'])){
				for($a=0; $a<count($config['css']); $a++){
					$css.='<link rel="stylesheet" type="text/css" href="'.$config['css'][$a].'" />';
				}
			}	
			$this->httpReponse->getPage()->addVar('css', $css);
			
			
			//LoadJs
			$js='';
			if(!empty($config['js'])){
				for($a=0; $a<count($config['js']); $a++){
					$js.='<script src="'.$config['js'][$a].'"></script>';
				}
			}
			$this->httpReponse->getPage()->addVar('js', $js);
			
			$appNavBuilderClass=ucfirst($this->nom).'NavBuilder';
			$appNavBuilder=new $appNavBuilderClass(null, $config);
			
			
			//Menu
			$leftMainNav=$cache->getMemCache($this->nom.'-'.'leftMainNav_s='.$this->user->getStatut().'_a='.$this->user->getAccreditation(), (function($arg){
				return $arg['appNavBuilder']->buildLeft($arg['user']);
			}), array('appNavBuilder'=>$appNavBuilder, 'user'=>$this->user));
			$this->httpReponse->getPage()->addVar('leftMainNav', $leftMainNav);
			
			$topMainNav=$cache->getMemCache($this->nom.'-'.'topMainNav_s='.$this->user->getStatut().'_a='.$this->user->getAccreditation(), (function($arg){
				return $arg['appNavBuilder']->buildTop($arg['user']);
			}), array('appNavBuilder'=>$appNavBuilder, 'user'=>$this->user));
			$this->httpReponse->getPage()->addVar('topMainNav', $topMainNav);
		}
		
		public function getApps(){
			$apps=[];
			$directory='../Applications';
			foreach(scandir($directory) as $key => $element){
				if($element !='.' && $element !='..' && is_dir($directory.'/'.$element)){
                    $apps[]=$element;
                }
			}
			return $apps;
		}
		
		public function getAppsOptions($appDef=""){
			$apps=$this->getApps();
			$optGrp=new OptgroupField();
			foreach($apps as $key=>$app){
				$selected = ($appDef==$app) ? true : false;
				$optGrp->addOption( new OptionField(array('value'=>$app, 'label'=>$app, 'selected'=>$selected)) );  
			}
			return [$optGrp];
		}
        
}
