<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class Backend_NewsController extends MiddleController{
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
		public function executeDelete(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('News');
			$id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record))
				$this->httpReponse404();
            
            $manager->delete($id);
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-delete']);
            $this->httpReponse->redirect('index');
        }
       
		public function executeIndex(){
            $this->page->addVar('title', 'News'); 
            //On force l'affichage sous forme de tableau
			$this->httpRequette->addGetVar('change', 'table');
            parent::executeIndex();
        }

		 
		public function executeInsertIndex(){
            $this->page->addVar('title', 'Ajout news');
            parent::executeInsertIndex();
        }
        
		public function executeInsert(){
			ignore_user_abort(true);

            $formBuilder = new Backend_NewsFormBuilder(null, $this->config);
            $formBuilder->buildInsert();
            $formBuilder->setValues(array(
                'app'=>$this->httpRequette->postData('app'),
                'accreditation'=>$this->httpRequette->postData('accreditation'),
                'statut'=>$this->httpRequette->postData('statut'),
                'textBBCode'=>$this->httpRequette->postData('textBBCode')
            ));

            if(!$formBuilder->getForm()->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index');
            }
			$record=$formBuilder->getForm()->getEntity();
            
    
            
            $manager=$this->managers->getManagerOf('News');
            
            
            $record->setTextHtml(String::parseBBCode($record->getTextBBCode()));
            $record->setId_user_createur( $this->app->getUser()->getRecord()->getId() );
            $record->setDateCreation( date('Y-m-d H:i:s') );
            
            if(!$record->isValid())
				$this->httpReponse->redirect('insert-index');
            
            
            $record->setId($manager->save($record));
            $i=$record->getId();   
            if(!is_numeric($i)){
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('insert-index');
            }
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect('index');
        }
        
        
		public function executeUpdateIndex(){
            $this->page->addVar('title', 'Update news');
            //On construit le menu
            $navBuilder=new Backend_NewsNavBuilder(null, $this->config);
            $nav=$navBuilder->buildInsertTop();
            $this->page->addVar('secondaryTopNav', $nav);            
            //On construit les formulaires
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }elseif($this->httpRequette->getExists('id')){
				$id=$this->httpRequette->getData('id');
                
                $formBuilder = new Backend_NewsFormBuilder(null, $this->config);
                $formBuilder->buildUpdate($this->httpRequette->getData('id'));
                $form=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                $this->page->addVar('form', $form);
            }
        } 
       
        public function executeUpdate(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('news');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
            
            $formBuilder = new Backend_NewsFormBuilder(null, $this->config);
            $formBuilder->buildUpdate($id);
            $formBuilder->setValues(array(
                'app'=>$this->httpRequette->postData('app'),
                'accreditation'=>$this->httpRequette->postData('accreditation'),
                'statut'=>$this->httpRequette->postData('statut'),
                'textBBCode'=>$this->httpRequette->postData('textBBCode')
                
            ));

            if(!$formBuilder->getForm()->isValid()){     
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('update-index-'.$id);
            }   
            $record->mergeRecord( $formBuilder->getForm()->getEntity() );
            
            if($record->setId($manager->save($record))){
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('update-index-'.$id);
            }
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect('index');
        }
        
        public function executeSearch(){
		}
  
}
