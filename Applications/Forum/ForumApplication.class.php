<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class ForumApplication extends Application{
    /*
        Attributs
    */
        private $accessRules;
        /*
            Constantes
        */
        
    /*
        Méthodes générales
    */
        public function __construct() {
            parent::__construct();
            
            $this->nom = 'Forum';
            $this->setAccessRules();
        }
        
        /*
            Getters
        */
            public function setAccessRules(){
                    $this->accessRules=array(
						'index'=>[
							'index'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_VISITEUR
						],
						'forum'=>[
							'index'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_VISITEUR
						],
						'post'=>[
							'insert'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE,
							'insertIndex'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE,
							'update'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE,
							'updateIndex'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE,
							'delete'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE
						],'topic'=>[
							'insert'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE,
							'insertIndex'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE,
							'update'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE,
							'updateIndex'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE,
							'delete'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE,
							'show'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE
						]);
            }
        /*
            Setters
        */
    
    /*
        Autres méthodes
    */
        public function run(){
            $router = new Routeur($this);
            $controller = $router->getController();
            $module=lcfirst( substr($controller->getModule(), 6	) );//On enlève le Forum_
            $action=lcfirst($controller->getAction());
            $log=new Log();
          
			if(array_key_exists($module, $this->accessRules) && array_key_exists($action, $this->accessRules[$module]) &&
$this->accessRules[$module][$action][0]<=$this->user->getAccreditation() && $this->accessRules[$module][$action][1]<=$this->user->getStatut()){
				$controller->execute();
				$this->httpReponse->setPage($controller->page());
				
				//On charge les fichiers secondaires : css, js, menu application
				$this->loadInclude($module, $action);
				//On charge les msg flash
				$this->httpReponse->getPage()->addVar('flash', $this->getUser()->getFlash(true));
				$log->log('Access', 'forum', $this->user->getLogin().'|=|access=true: '.$this->httpRequette->getRequetteURI(), Log::GRAN_MONTH);
				$this->httpReponse->send();
            }else{
                $log->log('Access', 'forum', $this->getUser()->getLogin().'|=|access=false, wrong permition: '.$this->httpRequette->getRequetteURI(), Log::GRAN_MONTH);
                $this->httpReponse->redirect404(); 
            }
        }
}
