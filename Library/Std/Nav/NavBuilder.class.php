<?php
/*
 * name: NavBuilder
 * @description :  
 */ 
    
abstract class NavBuilder{
    /*
        Attributs
    */  
		protected $nav;
		protected $config;
        /*
            Constantes
        */
            
    /*
        Méthodes générales
    */
		public function __construct($nav=null, $config=array()){
			!empty($nav) ? $this->nav=$nav : $this->nav=new Nav();
			!empty($config) ? $this->config=$config : $this->buildConfig();
        }

        /*
            Getters
        */       
        /*
            Setters
        */
    /*
        Autres méthodes
    */
		public function buildConfig(){
			$class=get_class($this);
			$classAr=explode('_', $class);
			
			//On recupère l'application
			if(count($classAr)==1){
				$app='Frontend';
			}else{
				$app=$classAr[0];
			}
			
			$module=substr($class, 0, strlen($class)-10);
			
			//On charge la config générale du module

			$appConfig=new AppConfig($app, $module);
			$this->config=$appConfig->load();
		}
}
