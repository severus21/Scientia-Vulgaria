<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class ProjetApplication extends Application{
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
            
            $this->nom = 'Projet';
            $this->setAccessRules();
        }
        
        /*
            Getters
        */
            public function setAccessRules(){
                    $this->accessRules=array(
                    'projet_Thot'=>array(
						'list'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_VISITEUR.''.User::LEVEL_0,
						'index'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_VISITEUR.''.User::LEVEL_0,
						'export'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'insertExemple'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE.''.User::LEVEL_0,
						'insertMultiExemple'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'insertIndexExemple'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE.''.User::LEVEL_0,
						'insertIndexMultiExemple'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'updateExemple'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE.''.User::LEVEL_0,
						'updateIndexExemple'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MEMBRE.''.User::LEVEL_0,
						'deleteExemple'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'insertDatabase'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'insertIndexDatabase'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'updateDatabase'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'updateIndexDatabase'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'deleteDatabase'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0
					));
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
            $module=lcfirst($controller->getModule());
            $action=lcfirst($controller->getAction());
            $log=new Log();
           
			if(array_key_exists($module, $this->accessRules) && array_key_exists($action, $this->accessRules[$module]) &&
$this->accessRules[$module][$action][0]<=$this->user->getAccreditation() && $this->accessRules[$module][$action][1]<=$this->user->getStatut()){
				
				$controller->execute();
				$this->httpReponse->setPage($controller->page());
				
				//On charge les fichiers secondaires : css, js
				$this->loadInclude($module, $action);
				//On charge les msg flash
				$this->httpReponse->getPage()->addVar('flash', $this->getUser()->getFlash(true));
				$log->log('Access', 'projet', $this->user->getLogin().'|=|access=true: '.$this->httpRequette->getRequetteURI(), Log::GRAN_MONTH);
				$this->httpReponse->send();
            }else{
                $log->log('Access', 'projet', $this->getUser()->getLogin().'|=|access=false, wrong permition: '.$this->httpRequette->getRequetteURI(), Log::GRAN_MONTH);
                $this->httpReponse->redirect404(); 
            }
        }
        

}
