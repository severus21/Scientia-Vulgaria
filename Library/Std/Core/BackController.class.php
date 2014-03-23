<?php
/*
 * 
 * name: BackController
 * @description permet :
 *          D'executer une action (donc une méthode) ;
 *          D'obtenir la page associée au contrôleur ;
 *          De modifier le module, l'action et la vue associés au contrôleur.
 * 
 */

abstract class BackController extends ApplicationComponent{
    /*
        Attributs
    */
        protected $action = '';
        protected $config=array(); // ['definition'=>['var1'=>'val1', ....], 'css'=>['src1', ..'srcn'], 'js'=>['src1', ..'srcn']
        protected $managers = null;
        protected $objectFactory = null;
        protected $module = '';
        protected $page = null;
        protected $view = '';
        protected $httpRequette=null;
        protected $httpReponse=null;
        
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        public function __construct($app=null, $module=null, $action=null, $config=null){
			if(empty($app)){//Si pas d'app ie php en ligne de commande
				return;
			}
			parent::__construct($app);
           
			//Obsoloete, mais compatibilité assendante
            $this->managers = new Managers(PDOFactory::getMysqlConnexion( $app->getNom() ));
           
            $this->objectFactory = new ObjectFactory( $app->getNom() );
            $this->page = new Page($app);
            
			isset($module) ? $this->setModule($module) : null;
			isset($action) ? $this->setAction($action) : null;
			isset($action) ? $this->setView($action) : null;
			isset($config) ? $this->setConfig($config) :null;
			if(empty($this->config) && isset($this->action) && isset($this->module)){
				empty($module) ? $module=get_class($this) :null;
				$appConfig=new AppConfig($this->app->getNom(), $this->module, $this->action);
				$this->setConfig($appConfig->load());
			}
			
            $this->httpRequette=$this->app->getHttpRequette();
            $this->httpReponse=$this->app->getHttpReponse();
        }
        
        /*
            Getters
        */
            public function getAction(){
                return $this->action;
            }
            
            public function getModule(){
                return $this->module;
            }
        /*
            Setters
        */
            public function setModule($module){
				if (!is_string($module) || empty($module)){
					throw new InvalidArgumentException('Le module doit étre une chaine de caractères valide');
				}
				
				$this->module = $module;
			}
        
			public function setAction($action){
				if (!is_string($action) || empty($action)){
					throw new InvalidArgumentException('L\'action doit étre une chaine de caractères valide');
				}
				$this->action = $action;
			}
        
			public function setView($view){
				if (!is_string($view) || empty($view)){
					throw new InvalidArgumentException('La vue doit étre une chaine de caractères valide');
				}
				
				$this->view = $view;
				$this->page->setContentFile('../Applications/'.$this->app->getNom().'/Modules/'.$this->module.'/Views/'.$this->view.'.php');
			}
    
			public function setConfig($config){
				if(empty($config)){
					throw new InvalidArgumentException('config doit étre un array');
				}
				
				$this->config = $config;
			}
			
			public function setManagers($managers){
				if(isset($managers))
					$this->managers=$managers;
			}
    
    /*
        Autres méthodes
    */
        public function execute(){
            $method = 'execute'.ucfirst($this->action);
            $this->$method();
        }
        
        public function page(){
            return $this->page;
        }
}
