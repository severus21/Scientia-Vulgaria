<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class SupportController extends BackController{
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
        public function executeInsertIndexRapport(){
            $this->page->addVar('title', 'Rapport');
            //On construit le formulaire
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
				$formBuilder = new SupportFormBuilder(null, $this->config);
				$formBuilder->buildInsert();
                $this->page->addVar('form', $formBuilder->getForm()->createView());
            }
        }
        
        public function executeInsertRapport(){
            $log=new Log();
            
            $formBuilder = new SupportFormBuilder();
            $formBuilder->buildInsert();
            $formBuilder->setValues(array('categorie'=> $this->httpRequette->postData('categorie'),
                                            'description'=>$this->httpRequette->postData('description'),
                                            'url'=>$this->httpRequette->postData('url')));
            
            if(!$formBuilder->getForm()->isValid()){
                $this->app->getUser()->setFlash('Champs invalides');
                $this->httpReponse->redirect('rapport');
            }
            
            $manager=$this->managers->getManagerOf('Support');  
            $record=$formBuilder->getForm()->getEntity();         
            $record->setDate(date('Y-m-d H:i:s'));
            $login=$this->app->getUser()->getLogin();
            $record->setLogin($login);
            $record->setUserAgent($_SERVER['HTTP_USER_AGENT']);
            
            if(is_numeric($manager->save($record))){
                $this->app->getUser()->setFlash('Rapport envoyé');
                $log->log('Support', 'report', $login.'|=|report=send,'.$record->getCategorie(),Log::GRAN_MONTH);
                $this->httpRequette->sessionUnset('form');
                $redirect='/';
                
            }elseif(is_string($ret)){
                $this->app->getUser()->setFlash($ret);
                $redirect='rapport';
            }else{
                $this->app->getUser()->setFlash('Erreur enregistrement!, veuillez réessayer dans quelques instants, si le problème persiste adresser une requête via l\'onglet support.');
                $log->log('Support', 'report', $login.'|=|report=failed, enregistrement failed',Log::GRAN_MONTH);
                $redirect='rapport';              
            }
            
            $this->httpReponse->redirect($redirect);
        }
		
		public function executeDon(){
			
		}
}
