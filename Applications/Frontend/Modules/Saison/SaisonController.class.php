<?php
/*
 * name: SaisonController
 * @description : 
 */

class SaisonController extends MiddleController{ 
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
         /*
         * 
         * name: executeDelete
         * @param
         * @return
         * @description
         * 
         */
		public function executeDelete($idServ=null, $forceDelete=false){// $forceDelete=true si le créateur de la serie la supprime
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('Saison');
            if(!empty($idServ))
				$id=$idServ;
			else
				$id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record))
				$this->httpReponse404();
            
            if( ($record->getLogin()!=$this->app->getUser()->getLogin() || $forceDelete)  && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
                if(empty($idServ))
					$this->httpReponse->redirect('../serie/show-'.$record->getIdSerie() );
				return false;
            }
            
            $imageController = new ImageController($this->app, null, 'delete');
            $episodeController = new EpisodeController($this->app, null, 'delete');
            
            $episodeController->executeMultiDelete( $record->getId() );
            $imageController->executeDelete( $record->getId_image_miniature() );
            
            $manager->delete($id);
  
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-delete']);
            if(empty($idServ))
				$this->httpReponse->redirect('../serie/show-'.$record->getIdSerie());
        }
        
        public function executeMultiDelete($idSerie){//Traitement déjà effectué dans serie delete
			$manager=$this->managers->getManagerOf('Saison');
			$requete=new Requete();
			$requete->addCol( new IntCol( ['name'=>'idSerie', 'comparisonOperator'=>'=', 'value'=>$idSerie ]));
			$records=$manager->getList($requete);
			
			for($a=0 ; $a<count($records) ; $a++)
				$this->executeDelete( $records[$a]->getId(), true);
			return true;
		}
        
        /*
         * 
         * name: executeIndexInsert
         * @param
         * @return
         * @description
         * 
         */
        public function executeInsertIndex(){
            $this->page->addVar('title', 'Ajout saison');
            parent::executeInsertIndex();
        }
        
        public function executeInsert(){
			ignore_user_abort(true);
            $f_image=$this->httpRequette->fileData('miniature');
            $id=$this->httpRequette->getData('id');
			$serieManager=$this->managers->getManagerOf('serie');
			if(!$serieManager->get($id))
                $this->httpReponse->redirect404();

            $formBuilder = new SaisonFormBuilder(null, $this->config);
            $formBuilder->buildInsert();
            $formBuilder->setValues(array(
                'n'=>$this->httpRequette->postData('n'),
                'date'=>$this->httpRequette->postData('date'),
                'resume'=>$this->httpRequette->postData('resume'),
                'nbrEpisodes'=>$this->httpRequette->postData('nbrEpisodes')
                
            ));

            if(!$formBuilder->getForm()->isValid() || empty($f_image)){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index-'.$id);
            }
            $record=$formBuilder->getForm()->getEntity();
           
            $imageController = new ImageController($this->app, 'Image', 'insert');
            
            $manager=$this->managers->getManagerOf('Saison');
            
			//Ajout de la miniature
            $image=$imageController->executeInsert(['file'=>$f_image, 
													'description'=>$this->config['define']['default-description-image'], 
													'redimention'=>true, 
													'x'=>$this->config['define']['miniature-x'],
													'y'=>$this->config['define']['miniature-y']]);
													
			if(!$image){
				//On supprime tous ce qui a été créé
				$contentController->executeDelete($nom->getRecord()->getId());				
                $this->httpReponse->redirect('insert-index-'.$id);
            }
						
			
            $record->setId_image_miniature($image->getRecord()->getId());
            $record->setLogin($this->app->getUser()->getLogin());
            $record->setIdSerie($id);
            
            if(!$record->isValid()){
				//On supprime tous ce qui a été créé
				$imageController->executeDelete($image->getRecord()->getId());
                $this->httpReponse->redirect('insert-index-'.$id);
            }
             
             
            $record->setId($manager->save($record));
            $i=$record->getId();   
            if(!is_numeric($i)){
				//On supprime tous ce qui a été créé
				$imageController->executeDelete($image->getRecord()->getId());
				
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('insert-index-'.$id);
            }
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect( 'show-'.$record->getId() );
        }
        
        public function executeUpdateIndex(){
            $this->page->addVar('title', 'Update saison');
            $id=$this->httpRequette->getData('id');
            //On construit le menu
            $navBuilder=new SaisonNavBuilder(null, $this->config);
            $nav=$navBuilder->buildUpdateTop($id);
            $this->page->addVar('secondaryTopNav', $nav);
            
            
            //On construit les formulaires
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
				$id=$this->httpRequette->getData('id');
				$manager=$this->managers->getManagerOf('saison');
				$record=$manager->get($id);
				$objFact=new ObjectFactory();
				$saison=$objFact->buildObject($record);
				
                $formBuilder = new SaisonFormBuilder(null, $this->config);
                $form='';
                
                $formBuilder->buildUpdateMiniature( $id );
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                //Attention position de buildInfo fait que c'est cet objet qui serat sauvegarder dans la session
                $formBuilder->buildUpdateInfo( $id );
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                $this->page->addVar('form', $form);
            }
        } 
       
        public function executeUpdateInfo(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('saison');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
            
            $formBuilder = new SaisonFormBuilder(null, $this->config);
            $formBuilder->buildUpdateInfo($id);
            $formBuilder->setValues(array(
                'n'=>$this->httpRequette->postData('numero'),
                'date'=>$this->httpRequette->postData('date'),
                'resume'=>$this->httpRequette->postData('resume'),
                'nbrEpisodes'=>$this->httpRequette->postData('nbrEpisodes')
                
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
            $this->httpReponse->redirect('show-'.$id);
        }
 
        public function executeUpdateMiniature(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('saison');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id); 
            if(!is_object($record)){
				$this->httpReponse->redirect404();
            }
               
			if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
			
			
            $f_image=$this->httpRequette->fileData('miniature', null, 'update');
            $imageController = new ImageController($this->app);
            $flag=$imageController->executeUpdate(array('id'=>$record->getId_image_miniature(),
													'file'=>$f_image, 
													'description'=>$this->config['define']['default-description-image'],
													'redimention'=>true,
													'x'=>$this->config['define']['miniature-x'],
													'y'=>$this->config['define']['miniature-y']));
            if($flag){
                $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
                $this->httpRequette->sessionUnset('form');
                $this->httpReponse->redirect('show-'.$id);
            }else{
                $this->httpReponse->redirect('update-index-'.$id);
            }
        }
        
        public function executeShow($flagPag=true, $flagNav=true){
			parent::executeShow(false,true);
			$episodePagination=new EpisodePaginationBuilder(null, $this->config);
			$episodePagination->buildShow($this->httpRequette->getData('id'));
			
			$cache= new Cache();
			$content=$cache->getCache('Frontend', 'Saison', 'showPagination', (function($arg){
				return $arg['paginationBuilder']->getPagination()->build();
			}), array('paginationBuilder'=>$episodePagination));
			
			$this->page->addVar('episodes', $content);
		}
}
