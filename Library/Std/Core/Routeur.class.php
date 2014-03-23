<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
    Description :
        Router permet :
            de retourner le contrôleur correspondant au module vers lequel pointe l'URL.
*/

class Routeur extends ApplicationComponent{
    /*
        Attributs
    */
        /*
            Constantes
        */
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
        public function getController(){
            $config=new Config(array('file'=>'../Applications/'.$this->app->getNom().'/Config/routes.xml'));
            $keys=array('module','action','vars');
            $reg=array('att'=>'url', 'opt'=>'i', 'strict'=>true, 'value'=>$this->app->getHttpRequette()->getRequetteURI());

            $route=$config->getByTagName($keys, 'route', $reg);
         //   print_r($route);exit;
            if(!empty($route)){
                $module = ucfirst($route['module']);
                $action = lcfirst($route['action']);
                $appConfig=new AppConfig($this->app->getNom(), $module, $action);
                $classname = $module.'Controller';
                    
                if (!file_exists('../Applications/'.$this->app->getNom().'/Modules/'.$module.'/'.$classname.'.class.php')){
                    throw new RuntimeException('Le module où pointe la route n\'existe pas'.$this->app->getNom().'/Modules/'.$module.'/'.$classname.'.class.php'); 
                }

                $class = $classname;
                $controller = new $class($this->app, $module, $action, $appConfig->load());
                if(!empty($route['vars'])){
					for($a=0; $a<count($route['vars']); $a++){
						if(is_array($route['vars'][$a]))
							$this->app->getHttpRequette()->addGetVar($route['vars'][$a]['var'], $route['vars'][$a]['value']);
                    }
                }
            }
            
            if(!isset($controller)){
                $this->app->getHttpReponse()->redirect404();
            }
            
            //On ajoute l'URI à la pile dèjà visitée
            $this->app->getUser()->addUrl($this->app->getHttpRequette()->getRequetteURI());
            
           
            return $controller;
        }
}
