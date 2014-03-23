<?php
/*
 * name: PaginationBuilder
 * @description : 
 */ 
 
abstract class PaginationBuilder{
    /*
        Attributs
    */
        protected $pagination;
        protected $config;
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        public function __construct($pagination=null, $config=null){
            if(isset($pagination) && $pagination instanceof Pagination){
                $this->setPagination($pagination);
            }
            isset($config) ? $this->config=$config : $this->buildConfig();
        }
        /*
            Getters
        */
            public function getPagination(){
                return $this->pagination;
            }
        
        /*
            Setters
        */
            public function setPagination(Pagination $pagination){
                $this->pagination = $pagination;
            }
    /*
        Autres méthodes
    */    
		//renvoit la fonction a apllique au controler ''  si pas ecahnge et fct is echange
		public function getEchangeFunction(){
			$httpRequette=new HttpRequette();
			$httpData=strtolower($httpRequette->method()).'Data';
			$httpExists=strtolower($httpRequette->method()).'Exists';
			
			if($httpRequette->$httpExists('change')){
				$echange=$httpRequette->$httpData('change');
				$uri=$httpRequette->getRequetteURI();

				preg_match('#/([a-zA-Z0-9-]+)(:?\?.{0,})$#', $uri,$matches);
				$function='build'.ucfirst($matches[1]).ucfirst($echange);
			   return $function;
			}else{
				return '';
			}
		}
            
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
