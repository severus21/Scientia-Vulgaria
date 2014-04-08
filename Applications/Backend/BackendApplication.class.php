<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class BackendApplication extends Application{
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
			$this->nom = 'Backend';
            parent::__construct();
            $this->setAccessRules();
        }
        
        /*
            Getters
        */
            public function setAccessRules(){
                    $this->accessRules=array(
                    'backend_Auth'=>array(
						'auth1Index'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'auth1'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'auth2Index'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_1,
						'auth2'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_1
					),'backend_Cache'=>array(
						'index'=>User::ACCREDITATION_INITIE.''.User::STATUT_ADMINISTRATEUR.''.User::LEVEL_1,
						'drop'=>User::ACCREDITATION_INITIE.''.User::STATUT_ADMINISTRATEUR.''.User::LEVEL_1,
						'dropMem'=>User::ACCREDITATION_INITIE.''.User::STATUT_ADMINISTRATEUR.''.User::LEVEL_1,
						'unset'=>User::ACCREDITATION_INITIE.''.User::STATUT_ADMINISTRATEUR.''.User::LEVEL_1,
						'list'=>User::ACCREDITATION_INITIE.''.User::STATUT_ADMINISTRATEUR.''.User::LEVEL_1
					),'backend_Categorie'=>array(
						'index'=>User::ACCREDITATION_INITIE.''.User::STATUT_ADMINISTRATEUR.''.User::LEVEL_1,
						'insertIndex'=>User::ACCREDITATION_INITIE.''.User::STATUT_ADMINISTRATEUR.''.User::LEVEL_1,
						'updateIndex'=>User::ACCREDITATION_INITIE.''.User::STATUT_ADMINISTRATEUR.''.User::LEVEL_1,
						'insert'=>User::ACCREDITATION_INITIE.''.User::STATUT_ADMINISTRATEUR.''.User::LEVEL_1,
						'update'=>User::ACCREDITATION_INITIE.''.User::STATUT_ADMINISTRATEUR.''.User::LEVEL_1
					),'backend_Index'=>array(
						'index'=>User::ACCREDITATION_STANDARDE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'dashboard'=>User::ACCREDITATION_INITIE.''.User::STATUT_ADMINISTRATEUR.''.User::LEVEL_1
                    ),'backend_Tmpfile'=>array(
						'insert'=>User::ACCREDITATION_INITIE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'insertIndex'=>User::ACCREDITATION_INITIE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0
                    ),'backend_News'=>array(
						'delete'=>User::ACCREDITATION_INITIE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'insert'=>User::ACCREDITATION_INITIE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'insertIndex'=>User::ACCREDITATION_INITIE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'index'=>User::ACCREDITATION_INITIE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'updateIndex'=>User::ACCREDITATION_INITIE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0,
						'update'=>User::ACCREDITATION_INITIE.''.User::STATUT_MODERATEUR.''.User::LEVEL_0
                    ));
            }
        /*
            Setters
        */
    
    /*
        Autres méthodes
    */
		public function auth($neededLevel){
			switch($neededLevel){
				case User::LEVEL_1 :
					$this->httpReponse->redirect('/backend/auth/auth1-index'); 
				break;
				case User::LEVEL_2 :
					$this->httpReponse->redirect('/backend/auth/auth2-index'); 
				break;
			}
		}
		
        public function run(){
            $router = new Routeur($this);
            $controller = $router->getController();
            $module=lcfirst($controller->getModule());
            $action=lcfirst($controller->getAction());
            $log=new Log();
           
			if(array_key_exists($module, $this->accessRules) && array_key_exists($action, $this->accessRules[$module]) &&
$this->accessRules[$module][$action][0]<=$this->user->getAccreditation() && $this->accessRules[$module][$action][1]<=$this->user->getStatut()){
				if($this->user->getTtlAccess()<time()-User::TTL_ACCESS)
					$this->user->setLevelAccess(0);
					
				$neededLevel=$this->accessRules[$module][$action][2];
				if( $neededLevel>$this->user->getLevelAccess() )
					$this->auth($neededLevel); 
				$this->user->setTtlAccess(time()+User::TTL_ACCESS);
				
				$controller->execute();
				$this->httpReponse->setPage($controller->page());
				
				//On charge les fichiers secondaires : css, js
				$this->loadInclude($module, $action);
				//On charge les msg flash
				$this->httpReponse->getPage()->addVar('flash', $this->getUser()->getFlash(true));
				$log->log('Access', 'backend', $this->user->getLogin().'|=|access=true: '.$this->httpRequette->getRequetteURI(), Log::GRAN_MONTH);
				$this->httpReponse->send();
            }else{
                $log->log('Access', 'backend', $this->getUser()->getLogin().'|=|access=false, wrong permition: '.$this->httpRequette->getRequetteURI(), Log::GRAN_MONTH);
                $this->httpReponse->redirect404(); 
            }
        }
}
