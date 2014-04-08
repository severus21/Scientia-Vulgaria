<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class FrontendApplication extends Application{
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
            $this->nom = 'Frontend';
            
            parent::__construct();
            $this->setAccessRules();
        }
                
        /*
            Getters
        */
            public function setAccessRules(){
                    $this->accessRules=array(
					'archive'=>array(
						'download'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE
                    ),'article'=>array(
                        'insert'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insertindex'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insertCommentaire'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_STANDARDE.User::STATUT_VISITEUR,         
                        'update'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE
                    ),'audio'=>array(
                        'gestion'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'index'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insert'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insertIndex'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'update'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'updateIndex'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE 
                    ),'cours'=>array(
                        'insert'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insertindex'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insertCommentaire'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_STANDARDE.User::STATUT_VISITEUR,         
                        'update'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE
                    ),'document'=>array(
                        'gestion'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'index'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insert'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insertIndex'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'download'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'mesimages'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'update'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'updateindex'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE 
                    ),'ebook'=>array(
                        'gestion'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'index'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'insert'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'insertIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'download'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateInfo'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateFile'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateMiniature'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE 
                    ),'episode'=>array(
                        'insert'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'insertIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'download'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateInfo'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateFile'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE 
                    ),'erreur'=>array(
						'404'=>User::ACCREDITATION_STANDARDE.User::STATUT_VISITEUR,
						'507'=>User::ACCREDITATION_STANDARDE.User::STATUT_VISITEUR,
                    ),'film'=>array(
                        'gestion'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'index'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'insert'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'insertIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'download'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateInfo'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateFile'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateMiniature'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE 
                    ),'image'=>array(
                        'gestion'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'index'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insert'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insertIndex'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'update'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'updateIndex'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE 
                    ),'index'=>array(
                        'index'=>User::ACCREDITATION_STANDARDE.User::STATUT_VISITEUR,
                    ),'support'=>array(
                        'insertIndexRapport'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insertRapport'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE
                    ),'saison'=>array(
                        /*'gestion'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'index'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,*/
                        'insert'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'insertIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateInfo'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateMiniature'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE 
                    ),'serie'=>array(
                        'gestion'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'index'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'insert'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'insertIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateInfo'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateMiniature'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE 
                    ),'software'=>array(
                        'gestion'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'index'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'insert'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'insertIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'download'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateInfo'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateFile'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateMiniature'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateTutoriel'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE,
                        'updateIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MEMBRE 
                    ),'tmpfile'=>array(
						'insert'=>User::ACCREDITATION_INITIE.User::STATUT_MODERATEUR,
						'insertIndex'=>User::ACCREDITATION_INITIE.User::STATUT_MODERATEUR
                    ),'user'=>array(
						'insertIndex'=>User::ACCREDITATION_STANDARDE.User::STATUT_VISITEUR,
						'insert'=>User::ACCREDITATION_STANDARDE.User::STATUT_VISITEUR,
						'connexion'=>User::ACCREDITATION_STANDARDE.User::STATUT_VISITEUR,
						'connect'=>User::ACCREDITATION_STANDARDE.User::STATUT_VISITEUR,
						'gestion'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
						'deconnexion'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
						'delete'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
						'passwordIndex'=>User::ACCREDITATION_STANDARDE.User::STATUT_VISITEUR,
						'passwordForgotten'=>User::ACCREDITATION_STANDARDE.User::STATUT_VISITEUR,
						'updateIndex'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
						'updateEmail'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
						'updatePassword'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE
                    ),'video'=>array(
                        'gestion'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'index'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insert'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'insertIndex'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'delete'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'download'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'show'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'update'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE,
                        'updateIndex'=>User::ACCREDITATION_STANDARDE.User::STATUT_MEMBRE 
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
				$log->log('Access', 'frontend', $this->user->getLogin().'|=|access=true: '.$this->httpRequette->getRequetteURI(), Log::GRAN_MONTH);
				$this->httpReponse->send();
            }else{
                $log->log('Access', 'frontend', $this->getUser()->getLogin().'|=|access=false, wrong permition: '.$this->httpRequette->getRequetteURI(), Log::GRAN_MONTH);
                $this->httpReponse->redirect404(); 
            }
        }
}
