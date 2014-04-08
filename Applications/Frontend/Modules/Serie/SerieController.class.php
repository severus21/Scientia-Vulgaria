<?php
/*
 * name: SerieController
 * @description : 
 */

class SerieController extends MiddleController{ 
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
		public function executeDelete(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('Serie');
			$id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record))
				$this->httpReponse404();
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
                $this->httpReponse->redirect('index');
            }
            
            $imageController = new ImageController($this->app, null, 'delete');
            $saisonController = new SaisonController($this->app, null, 'delete');
            
            $saisonController->executeMultiDelete( $record->getId() );
            $imageController->executeDelete( $record->getId_image_miniature() );
            
            $manager->delete($id);
  
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-delete']);
            $this->httpReponse->redirect('index');
        }
        
        /*
         * 
         * name: executeIndex
         * @param
         * @return
         * @description 
         * 
         */
        public function executeIndex(){
            $this->page->addVar('title', 'Series');    
            parent::executeIndex();
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
            $this->page->addVar('title', 'Ajout serie');
            parent::executeInsertIndex();
        }
        
        public function executeInsert(){
			ignore_user_abort(true);
				
            $f_image=$this->httpRequette->fileData('miniature');

            $formBuilder = new SerieFormBuilder(null, $this->config);
            $formBuilder->buildInsert();
            $formBuilder->setValues(array(
                'acteurs'=>$this->httpRequette->postData('acteurs'),
                'id_serieCategorie_categorie'=>$this->httpRequette->postData('id_serieCategorie_categorie'),
                'date'=>$this->httpRequette->postData('date'),
                'langue'=>$this->httpRequette->postData('langue'),
                'nom'=>$this->httpRequette->postData('nom'),
                'realisateur'=>$this->httpRequette->postData('realisateur'),
                'resume'=>$this->httpRequette->postData('resume'),
                'nbrSaisons'=>$this->httpRequette->postData('nbrSaisons'),
                'nbrEpisodes'=>$this->httpRequette->postData('nbrEpisodes')
                
            ));

            if(!$formBuilder->getForm()->isValid() || empty($f_image)){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index');
            }
            $record=$formBuilder->getForm()->getEntity();
            
            $imageController = new ImageController($this->app, 'Image', 'insert');
            
            $manager=$this->managers->getManagerOf('Serie');
            
			//Ajout de la miniature
            $image=$imageController->executeInsert(['file'=>$f_image, 
													'description'=>$this->config['define']['default-description-image'], 
													'redimention'=>true, 
													'x'=>$this->config['define']['miniature-x'],
													'y'=>$this->config['define']['miniature-y']]);
													
			if(!$image)
                $this->httpReponse->redirect('insert-index');
            
            $record->setId_image_miniature($image->getRecord()->getId());
            $record->setLogin($this->app->getUser()->getLogin());
            
            if(!$record->isValid()){
				//On supprime tous ce qui a été créé
				$imageController->executeDelete($image->getRecord()->getId());
                $this->httpReponse->redirect('insert-index');
            }
             
             
            $record->setId($manager->save($record));
            $i=$record->getId();   
            if(!is_numeric($i)){
				//On supprime tous ce qui a été créé
				$imageController->executeDelete($image->getRecord()->getId());
				
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('insert-index');
            }
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']); 
            $this->httpRequette->sessionUnset('form'); 
            $this->httpReponse->redirect( 'show-'.$record->getId() );
        }
        
        public function executeGestion(){
            $this->page->addVar('title', 'Mes series');
            parent::executeGestion();
        } 
    
        public function executeSearch($page){
			$httpData=strtolower($this->httpRequette->method()).'Data';
			$httpExists=strtolower($this->httpRequette->method()).'Exists';

            $this->httpRequette->$httpExists('recherche') ? $recherche=$this->httpRequette->$httpData('recherche') : $recherche='';
            $this->httpRequette->$httpExists('id_serieCategorie_categorie') ? $categorie=$this->httpRequette->$httpData('id_serieCategorie_categorie') : $categorie='';
            $this->httpRequette->$httpExists('langue') ? $langue=$this->httpRequette->$httpData('langue') : $langue='';
            $this->httpRequette->$httpExists('order') ? $order=$this->httpRequette->$httpData('order') : $order='';
            
			if( empty($recherche) && empty($categorie) && empty($langue) && empty($order) )
				return new Requete();
            
            $formBuilder = new SerieFormBuilder(null, $this->config);
            $formBuilder->buildSearch();
            $formBuilder->setValues(array(
                'recherche'=> $recherche,
                'id_serieCategorie_categorie'=> $categorie,
                'langue'=> $langue,
                'order'=> $order
            ));
            
            if(!$formBuilder->getForm()->isValid()){
				
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect($page);
            }
            
            $requeteBuilder=new SerieRequeteBuilder();
            $requeteBuilder->buildFromForm($formBuilder->getForm());
            $requete=$requeteBuilder->getRequete();
            //On traite le cas de la recherche avec sphinx
            if($recherche!=''){
				$sphinx= SphinxFactory::getSphinxClient();
				$resultats = $sphinx->Query( $sphinx->escapeString($recherche), 'serie', 'test');
				$ids= (!empty($resultats) && array_key_exists('matches', $resultats)) ? implode(',', array_keys($resultats['matches'])) : [];
                $requete->addCol(new MultipleCol(array('name'=>'id', 'value'=>$ids, 'logicalOperator'=>'&&', 'table'=>'serie')));
            }  
            
            $this->httpRequette->sessionUnset('form');
            return $requete;
        }
    
        public function executeUpdateIndex(){
            $this->page->addVar('title', 'Update serie');
            //On construit le menu
            $navBuilder=new SerieNavBuilder(null, $this->config);
            $nav=$navBuilder->buildUpdateTop();
            $this->page->addVar('secondaryTopNav', $nav);
            
            //On construit les formulaires
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }elseif($this->httpRequette->getExists('id')){
				$id=$this->httpRequette->getData('id');
				$manager=$this->managers->getManagerOf('serie');
				$record=$manager->get($id);
				$objFact=new ObjectFactory();
				$serie=$objFact->buildObject($record);
				
                $formBuilder = new SerieFormBuilder(null, $this->config);
                $form='';
                
                $formBuilder->buildUpdateMiniature($this->httpRequette->getData('id'));
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                //Attention position de buildInfo fait que c'est cet objet qui serat sauvegarder dans la session
                $formBuilder->buildUpdateInfo($this->httpRequette->getData('id'));
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                $this->page->addVar('form', $form);
            }
        } 
       
        public function executeUpdateInfo(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('serie');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
            
            $formBuilder = new SerieFormBuilder(null, $this->config);
            $formBuilder->buildUpdateInfo($id);
            $formBuilder->setValues(array(
                'acteurs'=>$this->httpRequette->postData('acteurs'),
                'id_serieCategorie_categorie'=>$this->httpRequette->postData('id_serieCategorie_categorie'),
                'date'=>$this->httpRequette->postData('date'),
                'langue'=>$this->httpRequette->postData('langue'),
                'nom'=>$this->httpRequette->postData('nom'),
                'realisateur'=>$this->httpRequette->postData('realisateur'),
                'resume'=>$this->httpRequette->postData('resume'),
                'nbrSaisons'=>$this->httpRequette->postData('nbrSaisons'),
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
            $manager=$this->managers->getManagerOf('serie');
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
        
        public function getRelated($obj){
            if(empty($obj))
                return array();
                
            $manager=$this->managers->getManagerOf('serie');
            $requete=new Requete();
            
            $requete->addCol(new StringCol(['name'=>'realisateur', 'table'=>'serie', 'value'=>$obj->getRealisateur(), 'logicalOperator'=>'||']));
            return $requete;    
        }
        
        public function executeShow($flagPag=true, $flagNav=true){
			parent::executeShow();
			$id=$this->httpRequette->getData('id');
			
			$saisonPagination=new SaisonPaginationBuilder(null, $this->config);
			$saisonPagination->buildShow($id);
			$cache= new Cache();
			$content=$cache->getCache('Frontend', 'Serie', 'showPagination-'.$id, (function($arg){
				return $arg['paginationBuilder']->getPagination()->build();
			}), array('paginationBuilder'=>$saisonPagination));
			
			
			$this->page->addVar('saisons', $content);
		}
}
