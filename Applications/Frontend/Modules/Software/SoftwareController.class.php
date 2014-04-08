<?php
/*
 * name: SoftwareController
 * @description : 
 */

class SoftwareController extends MiddleController{ 
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
            $manager=$this->managers->getManagerOf('Software');
            if($this->httpRequette->getExists('id'))
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record))
				$this->httpReponse404();
            
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
                $this->httpReponse->redirect('index');
            }
            $id_file=$record->getId_archive_file();
            $id_miniature=$record->getId_image_miniature();
            $id_tutoriel=$record->getId_document_tutoriel();
            
            $archiveController = new ArchiveController($this->app, null, 'delete');
            $documentController = new DocumentController($this->app, null, 'delete');
            $imageController = new ImageController($this->app, null, 'delete');
            
            
            
            !empty($id_file) ? $archiveController->executeDelete($id_file) : null;
            !empty($id_tutoriel) ? $documentController->executeDelete($id_tutoriel) : null;
            $imageController->executeDelete($id_miniature);
            
            $manager->delete($id);
  
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-delete']);
            $this->httpReponse->redirect('index');
        }
        
        public function executeDownload(){
			$manager=$this->managers->getManagerOf('Software');
            $id=$this->httpRequette->getData('id');
			$record=$manager->get($id);
            if(!is_object($record))
				$this->httpReponse->redirect404();
				
			$idFile=$this->httpRequette->getData('f');
			$type=$this->httpRequette->getData('t');

			switch($type){
				case 'archive':
					$idFile!==$record->getId_archive_file() ? $this->httpReponse->redirect404() : null;
					
					$archiveController=$this->managers->getManagerOf('archive');
					$objFact=new ObjectFactory();
					
					$archiveRecord=$archiveController->get($idFile);
					
					$process=new FileProcess($objFact->buildObject($archiveRecord));
					$process->download($record->getNom());
				break;
				case 'document':
					$idFile!==$record->getId_document_tutoriel() ? $this->httpReponse->redirect404() : null;
					
					$documentController=$this->managers->getManagerOf('document');
					$objFact=new ObjectFactory();
					
					$documentRecord=$documentController->get($idFile);
					
					$process=new FileProcess($objFact->buildObject($documentRecord));
					$process->download($record->getNom());
				break;
				default:
					$this->httpReponse->redirect404();
				break;
			}
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
            $this->page->addVar('title', 'Software');
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
            $this->page->addVar('title', 'Ajout software');
            parent::executeInsertIndex();
        }
        
        public function executeInsert(){
			ignore_user_abort(true);
            if($this->httpRequette->fileExists('file'))
				$f_file=$this->httpRequette->fileData('file');
			elseif($this->httpRequette->postExists('url'))
				$f_url=$this->httpRequette->postData('url');
				
            $f_image=$this->httpRequette->fileData('miniature');
            $f_document=$this->httpRequette->fileData('tutoriel');
            
            $formBuilder = new SoftwareFormBuilder(null, $this->config);
            $formBuilder->buildInsert();
            $formBuilder->setValues(array(
                'nom'=>$this->httpRequette->postData('nom'),
                'id_softwareCategorie_categorie'=>$this->httpRequette->postData('id_softwareCategorie_categorie'),
                'date'=>$this->httpRequette->postData('date'),
                'description'=>$this->httpRequette->postData('description'),
                'developpeur'=>$this->httpRequette->postData('developpeur'),
                'langue'=>$this->httpRequette->postData('langue'),
                'license'=>$this->httpRequette->postData('license'),
                'id_operatingSystem_os'=>$this->httpRequette->postData('id_operatingSystem_os'),
                'version'=>$this->httpRequette->postData('version')
            ));

            if(!$formBuilder->getForm()->isValid() || empty($f_image)){    
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index');
            }
            $record=$formBuilder->getForm()->getEntity();
            
            $archiveController = new ArchiveController($this->app, null, 'insert');
            $documentController = new DocumentController($this->app, null, 'insert');
            $imageController = new ImageController($this->app, null, 'insert');
            
            $manager=$this->managers->getManagerOf('Software');  
				
            //Ajout de la miniature
            $image=$imageController->executeInsert(['file'=>$f_image,
													'description'=>$this->config['define']['default-description-image'],
													'redimention'=>true,
													'x'=>$this->config['define']['miniature-x'],
													'y'=>$this->config['define']['miniature-y']]);
			if(!$image)
                $this->httpReponse->redirect('insert-index');
            
            
			//Ajout du tutoriel s'il existe
            ($f_document['size']!=0) ? $document=$documentController->executeInsert(['file'=>$f_document,
																				'description'=>$this->config['define']['default-description-document']]) : null;
			if(($f_document['size']!=0) && !$document){
				//On supprime tous ce qui a été créé
				$imageController->executeDelete($image->getRecord()->getId());
                $this->httpReponse->redirect('insert-index');
			}					
													
            //Ajout de l'archive
			if(!empty($f_file)){
				$archive=$archiveController->executeInsert(['file'=>$f_file,
															'description'=>$this->config['define']['default-description-archive']]);
			}elseif(!empty($f_url)){
				$archive=$archiveController->executeInsert(['url'=>$f_url,
														'description'=>$this->config['define']['default-description-archive']]);
			}
  
            !empty($archive) ? $record->setId_archive_file($archive->getRecord()->getId()) : null;
            is_object($document) ? $record->setId_document_tutoriel($document->getRecord()->getId()) : null;
            $record->setId_image_miniature($image->getRecord()->getId());
            $record->setLogin($this->app->getUser()->getLogin());
            
            if(!$record->isValid()){
				//On supprime tous ce qui a été créé
				$imageController->executeDelete($image->getRecord()->getId());
				!empty($document) ? $documentController->executeDelete($document->getRecord()->getId()) : null;
				!empty($archive) ? $archiveController->executeDelete($archive->getRecord()->getId()) : null;
				
                $this->httpReponse->redirect('insert-index');
			}
             
            $record->setId($manager->save($record));
            $i=$record->getId();   
			if(!is_numeric($i)){
				//On supprime tous ce qui a été créé
				$imageController->executeDelete($image->getRecord()->getId());
				$documentController->executeDelete($document->getRecord()->getId());
				!empty($archive) ? $archiveController->executeDelete($archive->getRecord()->getId()) : null;
				
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('insert-index');
            }
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect('show-'.$i);
        }
        
        public function executeGestion(){
            $this->page->addVar('title', 'Mes Softwares');
            parent::executeGestion();
        }   
    
        public function executeSearch($page){
            $httpData=strtolower($this->httpRequette->method()).'Data';
			$httpExists=strtolower($this->httpRequette->method()).'Exists';
            
            $this->httpRequette->$httpExists('recherche') ? $recherche=$this->httpRequette->$httpData('recherche') : $recherche='';
            $this->httpRequette->$httpExists('id_softwareCategorie_categorie') ? $categorie=$this->httpRequette->$httpData('id_softwareCategorie_categorie') : $categorie='';
            $this->httpRequette->$httpExists('id_operatingSystem_os') ? $os=$this->httpRequette->$httpData('id_operatingSystem_os') : $os='';
			$this->httpRequette->$httpExists('langue') ? $langue=$this->httpRequette->$httpData('langue') : $langue='';
            $this->httpRequette->$httpExists('order') ? $order=$this->httpRequette->$httpData('order') : $order='';
            
            if( empty($recherche) && empty($categorie) && empty($os) && empty($order) && empty($langue) )
				return new Requete();
            
            $formBuilder = new SoftwareFormBuilder(null, $this->config);
            $formBuilder->buildSearch();
            $formBuilder->setValues(array(
                'recherche'=> $recherche,
                'id_softwareCategorie_categorie'=>  $categorie,
                'id_operatingSystem_os'=> $os,
                'langue'=>$langue,
                'order'=> $order
            ));
            
            if(!$formBuilder->getForm()->isValid()){
                
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect($page);
            }
            
            $requeteBuilder=new SoftwareRequeteBuilder();
            $requeteBuilder->buildFromForm($formBuilder->getForm());
            $requete= $requeteBuilder->getRequete();
            //On traite le cas de la recherche avec sphinx
            if($recherche!=''){
				$sphinx= SphinxFactory::getSphinxClient();
				$resultats = $sphinx->Query( $sphinx->escapeString($recherche), 'software', 'test');
				$ids= (!empty($resultats) && array_key_exists('matches', $resultats)) ? implode(',', array_keys($resultats['matches'])) : [];
                $requete->addCol(new MultipleCol(array('name'=>'id', 'value'=>$ids, 'logicalOperator'=>'&&', 'table'=>'software')));
            }  
            
            $this->httpRequette->sessionUnset('form');
            return $requete;
        }
    
        public function executeUpdateIndex(){
            $this->page->addVar('title', 'Update software');
            
            //On construit le menu
            $navBuilder=new SoftwareNavBuilder(null, $this->config);
            $nav=$navBuilder->buildUpdateTop();
            $this->page->addVar('secondaryTopNav', $nav);
            
            //On construit les formulaires
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }elseif($this->httpRequette->getExists('id')){
				$id=$this->httpRequette->getData('id');
				$manager=new SoftwareManager(PDOFactory::getMysqlConnexion());
				$record=$manager->get($id);
				$objectFactory=new ObjectFactory();
				$ebook=$objectFactory->buildObject($record);
				
                $formBuilder = new SoftwareFormBuilder(null, $this->config);
                $form='';
                
                $formBuilder->buildUpdateFile($id, $ebook->getFile());
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                $formBuilder->buildUpdateMiniature($id);
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                $formBuilder->buildUpdateTutoriel($id);
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                //Attention position de buildInfo fait que c'est cet objet qui serat sauvegarder dans la session
                $formBuilder->buildUpdateInfo($ebook);
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                $this->page->addVar('form', $form);
            }
        } 

        public function executeUpdateInfo(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('Software');
            $id=$this->httpRequette->getData('id');
            
            //A améliorer
            $record=$manager->get($id);
            $objFact=new ObjectFactory();
            $obj=$objFact->buildObject($record);
            
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
            
            $formBuilder = new SoftwareFormBuilder(null, $this->config);
            $formBuilder->buildUpdateInfo($obj);
            $formBuilder->setValues(array(
                'nom'=>$this->httpRequette->postData('nom'),
                'id_softwareCategorie_categorie'=>$this->httpRequette->postData('id_softwareCategorie_categorie'),
                'date'=>$this->httpRequette->postData('date'),
                'description'=>$this->httpRequette->postData('description'),
                'developpeur'=>$this->httpRequette->postData('developpeur'),
                'langue'=>$this->httpRequette->postData('langue'),
                'license'=>$this->httpRequette->postData('license'),
                'id_operatingSystem_os'=>$this->httpRequette->postData('id_operatingSystem_os'),
                'version'=>$this->httpRequette->postData('version')
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
       
        public function executeUpdateFile(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('Software');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
             
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
       
            $f_file=$this->httpRequette->fileData('file');
            $archiveController = new ArchiveController($this->app, null, 'update');
            if($record->getId_archive_file()){
				$flag=$archiveController->executeUpdate(['file'=>$f_file,
													'description'=>$this->config['define']['default-description-software'],
													'id'=>$record->getId_archive_file()]);
													exit;	
			}else{
				$archive=$archiveController->executeInsert(['file'=>$f_file,
													'description'=>$this->config['define']['default-description-software']]);
				if(!empty($archive)){
					$record->setId_archive_file($archive->getRecord()->getId());
					$manager->save($record);
					$flag=true;
				}else
					$flag=false;
			}
            
            if($flag){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-file-update']);
				$this->httpRequette->sessionUnset('form');
				$this->httpReponse->redirect('show-'.$id);
			}else{
				$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				$this->httpReponse->redirect('update-index-'.$id);
			}
        }
        
        public function executeUpdateMiniature(){
			ignore_user_abort(true);
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id); 
            if(!is_object($record)){
				$this->httpReponse->redirect404();
            }
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }     
            $f_image=$this->httpRequette->fileData('miniature');
            $imageController = new ImageController($this->app, null, 'update');
            $flag=$imageController->executeUpdate(['file'=>$f_image,
													'description'=>$this->config['define']['default-description-image'],
													'redimention'=>true,
													'x'=>$this->config['define']['miniature-x'],
													'y'=>$this->config['define']['miniature-y']]);
            
            if($flag){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-file-update']);
				$this->httpReponse->redirect('index');
			}else{
				$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				$this->httpReponse->redirect('update-index-'.$id);
			}
        }
       
        public function executeUpdateTutoriel(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('Software');
			$id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
             
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }      
            
            $f_document=$this->httpRequette->fileData('tutoriel');
            $documentController = new DocumentController($this->app, null, 'update');
            $flag=$documentController->executeUpdate(['file'=>$f_document,
														'description'=>$this->config['define']['default-description-document'],
														'id'=>$record->getId_document_tutoriel()]);
            if($flag){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-file-update']);
				$this->httpRequette->sessionUnset('form');
				$this->httpReponse->redirect('show-'.$id);
			}else{
				$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				$this->httpReponse->redirect('update-index-'.$id);
			}
        }
        
        public function getRelated($obj){
            if(empty($obj))
                return array();
                
            $manager=$this->managers->getManagerOf('Software');
            $requete=new Requete();
            $requete->addCol(new StringCol(['name'=>'nom', 'value'=>$obj->getNom()]));
            $requete->addCol(new StringCol(['name'=>'developpeur', 'value'=>$obj->getDeveloppeur(), 'logicalOperator'=>'||']));
            return $requete;    
        }
}
