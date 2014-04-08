<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class Backend_AuthController extends BackController{
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

		
		public function executeAuth1Index(){
			$formBuilder = new Backend_AuthFormBuilder(null, $this->config);
            $formBuilder->buildAuth1();

            $this->page->addVar('title', 'Auth1');
            $this->page->addVar('form', $formBuilder->getForm()->createView());
		}
		
		public function executeAuth1(){
			$manager=$this->managers->getManagerOf('User');
			
			$password=$this->httpRequette->postData('password');
            
            $formBuilder = new Backend_AuthFormBuilder(null, $this->config);
            $formBuilder->buildAuth1();
            $formBuilder->setValues(array('password'=>$password
                                            ));
            
            if(!$formBuilder->getForm()->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('auth1-index');
            }
			if(Cryptographie::str2SHA512($password)!==$this->app->getUser()->getPassword()){
				$this->app->getUser()->setFlash($this->config['flash']['invalid-password']);
				$this->httpReponse->redirect('auth1-index');
			}
			
			$this->app->getUser()->setTtlAccess( time() );
			$this->app->getUser()->setLevelAccess( User::LEVEL_1 );
			
			
			$this->app->getUser()->setFlash($this->config['flash']['sucessful-auth1']);
			$this->httpRequette->sessionUnset('form');
			
			//On dépile la page courrante
			$this->app->getUser()->getLastUrl();
			//On depile la page de formulaire
			$this->app->getUser()->getLastUrl();

			$this->httpReponse->redirect( $this->app->getUser()->getLastUrl() );
				
		}
}
