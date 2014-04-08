<?php
/*
 * name: EbookController
 * @description : 
 */

class EbookController extends MiddleController{ 
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
            $manager=$this->managers->getManagerOf('Ebook');
            $id=$this->httpRequette->getData('id');
			$record=$manager->get($id);
            if(!is_object($record))
				$this->httpReponse->redirect404();
            
            $record=$manager->get($id);
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
            $id_files=explode(',', $record->getId_document_files_ar());
            $id_miniature=$record->getId_image_miniature();
            
            $documentController = new DocumentController($this->app, null, 'delete');
            $imageController = new ImageController($this->app, null, 'delete');
            
            for($a=0 ; $a<count($id_files); $a++){
				!empty($id_files[$a]) ? $documentController->executeDelete($id_files[$a]): null;
			}  
            $imageController->executeDelete($id_miniature);

            $manager->delete($id);
			
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-delete']);
            $this->httpReponse->redirect('index');
        }
       
		public function executeDownload(){
			$manager=$this->managers->getManagerOf('Ebook');
            $id=$this->httpRequette->getData('id');
			$record=$manager->get($id);
            if(!is_object($record))
				$this->httpReponse->redirect404();
				
			$idFile=$this->httpRequette->getData('f');
			$type=$this->httpRequette->getData('t');

			switch($type){
				case 'document':
					$idAr=explode(',', $record->getId_document_files_ar());
					!in_array($idFile, $idAr) ? $this->httpReponse->redirect404() : null;
					
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
            $this->page->addVar('title', 'Ebooks');
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
            $this->page->addVar('title', 'Ajout ebook');
            parent::executeInsertIndex();
        }
        
        public function executeInsert(){
			ignore_user_abort(true);
            if($this->httpRequette->fileExists('file'))
				$f_file=$this->httpRequette->fileData('file');
			elseif($this->httpRequette->postExists('url'))
				$f_url=$this->httpRequette->postData('url');
            
            $f_image=$this->httpRequette->fileData('miniature');
      
            $formBuilder = new EbookFormBuilder(null,$this->config);
            $formBuilder->buildInsert();
            $formBuilder->setValues(array(
                'isbn'=>$this->httpRequette->postData('isbn'),
                'auteur'=>$this->httpRequette->postData('auteur'),
                'date'=>$this->httpRequette->postData('date'),
                'editeur'=>$this->httpRequette->postData('editeur'),
                'id_etiquette_etiquette'=>$this->httpRequette->postData('id_etiquette_etiquette'),
                'id_ebookCategorie_genre'=>$this->httpRequette->postData('id_ebookCategorie_genre'),
                'langue'=>$this->httpRequette->postData('langue'),
                'resume'=>$this->httpRequette->postData('resume'),
                'serie'=>$this->httpRequette->postData('serie'),
                'nom'=>$this->httpRequette->postData('nom')
            ));

            if(!$formBuilder->getForm()->isValid() || empty($f_image)){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index');
            }
            $record=$formBuilder->getForm()->getEntity();
            
            $documentController = new DocumentController($this->app, null, 'insert');
            $imageController = new ImageController($this->app, null, 'insert');
            
            $manager=$this->managers->getManagerOf('Ebook');

            //Ajout de la miniature
            $image=$imageController->executeInsert(['file'=>$f_image,
													'description'=>$this->config['define']['default-description-image'],
													'redimention'=>true,
													'x'=>$this->config['define']['miniature-x'],
													'y'=>$this->config['define']['miniature-y']]);
            if(!$image)
                $this->httpReponse->redirect('insert-index');
            
            
            //Ajout du/des documents
			$id_document_files='';
			for($a=1; $a<10 ;$a++){
				if(!empty($f_file)){
					$document=$documentController->executeInsert(['file'=>$f_file,
															'description'=>$this->config['define']['default-description-document']]);
				}elseif(!empty($f_url)){
					$document=$documentController->executeInsert(['url'=>$f_url,
															'description'=>$this->config['define']['default-description-document']]);
				}
				is_object($document) ? $id_document_files.=$document->getRecord()->getId().',' : null;
				$document=null;
				
				//On prépare la prochaine itération
				if($this->httpRequette->fileExists('file'.$a))
					$f_file=$this->httpRequette->fileData('file'.$a);
				elseif($this->httpRequette->postExists('url'.$a))
					$f_url=$this->httpRequette->postData('url'.$a);
				else{
					$f_url='';$f_file=array();
				}
			}
			
            !empty($id_document_files) ? $record->setId_document_files_ar($id_document_files) : null;
            $record->setId_image_miniature($image->getRecord()->getId());
            $record->setLogin($this->app->getUser()->getLogin());
            
            if(!$record->isValid()){
				$imageController->executeDelete($image->getRecord()->getId());
				$idAr=explode(',', $id_document_files);
				for($a=0; $a<count($idAr); $a++)
					$documentController->executeDelete($idAr[$a]);
					
                $this->httpReponse->redirect('insert-index');
            }
             
             
            $record->setId($manager->save($record));
            $i=$record->getId();   
            if(!is_numeric($i)){
				$imageController->executeDelete($image->getRecord()->getId());
				$idAr=explode(',', $id_document_files);
				for($a=0; $a<count($idAr); $a++)
					$documentController->executeDelete($idAr[$a]);
					
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('insert-index');
            }
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);   
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect('show-'.$i);
        }
        
        public function executeGestion(){
            $this->page->addVar('title', 'Mes ebooks');
            parent::executeGestion();
        } 
    
        public function executeSearch($page){
            $httpData=strtolower($this->httpRequette->method()).'Data';
			$httpExists=strtolower($this->httpRequette->method()).'Exists';
			
            $this->httpRequette->$httpExists('recherche') ? $recherche=$this->httpRequette->$httpData('recherche') : $recherche='';
            $this->httpRequette->$httpExists('id_ebookCategorie_genre') ? $genre=$this->httpRequette->$httpData('id_ebookCategorie_genre') : $genre='';
            $this->httpRequette->$httpExists('id_etiquette_etiquette') ? $etiquette=$this->httpRequette->$httpData('id_etiquette_etiquette') : $etiquette='';
            $this->httpRequette->$httpExists('langue') ? $langue=$this->httpRequette->$httpData('langue') : $langue='';
            $this->httpRequette->$httpExists('order') ? $order=$this->httpRequette->$httpData('order') : $order='';
            
            if( empty($recherche) && empty($genre) && empty($etiquette) && empty($order) && empty($langue) )
				return new Requete();
            
            $formBuilder = new EbookFormBuilder(null, $this->config);
            $formBuilder->buildSearch();
            $formBuilder->setValues(array(
                'recherche'=> $recherche,
                'id_ebookCategorie_genre'=> $genre,
                'id_etiquette_etiquette'=> $etiquette,
                'langue'=>$langue,
                'order'=> $order
            ));
           
            if(!$formBuilder->getForm()->isValid()){
                
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect($page);
            }
            
            $requeteBuilder=new EbookRequeteBuilder();
            $requeteBuilder->buildFromForm($formBuilder->getForm());
            $requete= $requeteBuilder->getRequete();
            //On traite le cas de la recherche avec sphinx
            if($recherche!=''){
				$sphinx= SphinxFactory::getSphinxClient();
				$resultats = $sphinx->Query( $sphinx->escapeString($recherche), 'ebook', 'test');
				$ids= (!empty($resultats) && array_key_exists('matches', $resultats)) ? implode(',', array_keys($resultats['matches'])) : [];
                $requete->addCol(new MultipleCol(array('name'=>'id', 'value'=>$ids, 'logicalOperator'=>'&&', 'table'=>'ebook')));
            }        

			$this->httpRequette->sessionUnset('form');
            return $requete;
        }
    
        public function executeUpdateIndex(){
            $this->page->addVar('title', 'Update ebook');
            
            //On construit le menu
            $navBuilder=new EbookNavBuilder(null, $this->config);
            $nav=$navBuilder->buildUpdateTop();
            $this->page->addVar('secondaryTopNav', $nav);
            
            //On construit les formulaires
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
				$id=$this->httpRequette->getData('id');
				$manager=$this->managers->getManagerOf('ebook');
				$record=$manager->get($id);
				$objFact=new ObjectFactory();
				$ebook=$objFact->buildObject($record);
				
                $formBuilder = new EbookFormBuilder(null, $this->config);
                $form='';
                
                $formBuilder->buildUpdateFile($id, $ebook->getFiles());
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();

                $formBuilder->buildUpdateMiniature($id);
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                //Attention position de buildInfo fait que c'est cet objet qui serat sauvegarder dans la session
                $formBuilder->buildUpdateInfo($id);
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                $this->page->addVar('form', $form);
            }
        } 
       
        public function executeUpdateInfo(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('ebook');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
            
            $formBuilder = new EbookFormBuilder(null, $this->config);
            $formBuilder->buildUpdateInfo($id);
            $formBuilder->setValues(array(
                'auteur'=>$this->httpRequette->postData('auteur'),
                'date'=>$this->httpRequette->postData('date'),
                'editeur'=>$this->httpRequette->postData('editeur'),
                'isbn'=>$this->httpRequette->postData('isbn'),
                'id_etiquette_etiquette'=>$this->httpRequette->postData('id_etiquette_etiquette'),
                'id_ebookCategorie_genre'=>$this->httpRequette->postData('id_ebookCategorie_genre'),
                'langue'=>$this->httpRequette->postData('langue'),
                'resume'=>$this->httpRequette->postData('resume'),
                'serie'=>$this->httpRequette->postData('serie'),
                'nom'=>$this->httpRequette->postData('nom')
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
            $manager=$this->managers->getManagerOf('ebook');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id); 
            if(!is_object($record))
                $this->httpReponse->redirect404();
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
                  
            $documentController = new DocumentController($this->app, null, 'insert');
            $ebookController = new EbookController($this->app, null, 'insert');
			$idFiles=explode(',', $record->getId_document_files_ar());
			$nbrDelMax=count($idFiles);
			
			
			//On supprime les ebooks à supprimer
			if( $this->httpRequette->postExists('deleteListFile') && $this->httpRequette->postData('deleteListFile')!="" ){
				$idDeletedFiles=explode(",", $this->httpRequette->postData('deleteListFile') );
				
				//on supprime les documents
				$a=0;
				while( $a<$nbrDelMax && $a<count($idDeletedFiles) ){
					$documentController->executeDelete($idDeletedFiles[$a]);
					$a++;
				}
				
				//On enlevez les id inutiles
				$tmp="";
				for($a=0; $a<count($idFiles); $a++){
					if( !in_array($idFiles[$a], $idDeletedFiles) ){
						$tmp=$idFiles[$a].',';
					}
				}
				$newIdFiles= (strlen($tmp)>0) ? substr( $tmp, 0, strlen($tmp)-1 ) : $tmp;  
				$record->setId_document_files_ar($newIdFiles);
				if( !$manager->save($record) ){
					$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
					$this->httpReponse->redirect('update-index-'.$id);
				}
			}
			
			//On modifie les documents
			for($a=0; $a<11; $a++){
				$flag=true;

				if( $this->httpRequette->fileExists('file'.$a) && $this->httpRequette->fileData('file'.$a)['size']!=0
&& in_array( $this->httpRequette->postData('id-file'.$a) ,$idFiles ) ){
					$flag=$documentController->executeUpdate(['id'=>$this->httpRequette->postData('id-file'.$a),
						'file'=>$this->httpRequette->fileData('file'.$a),
						'description'=>$this->config['define']['default-description-document']]);
					
				}elseif($this->httpRequette->postExists('url'.$a) && in_array( $this->httpRequette->postData('id-file'.$a) ,$idFiles ) ){
					$flag=$documentController->executeUpdate(['id'=>$this->httpRequette->postData('id-file'.$a),
						'url'=>$this->httpRequette->postData('url'.$a),
						'description'=>$this->config['define']['default-description-document']]);
					
				}
				
				if( !is_object($flag) && !$flag){
					$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
					$this->httpReponse->redirect('update-index-'.$id);
				}
			}
			
			//On ajoute les fichiers
			$newIds="";
			for($a=11; $a<22; $a++){
				if( $this->httpRequette->fileExists('file'.$a) && $this->httpRequette->fileData('file'.$a)['size']!=0 ){
					$flag=$documentController->executeInsert(['file'=>$this->httpRequette->fileData('file'.$a),
															'description'=>$this->config['define']['default-description-document']]);
				}elseif($this->httpRequette->postExists('url'.$a)){
					$flag=$documentController->executeInsert(['url'=>$this->httpRequette->postData('url'.$a),
															'description'=>$this->config['define']['default-description-document']]);
				}	
				is_object($flag) ? $newIds.=$flag->getRecord()->getId()."," : null; 
				$flag=null;			
			}
			strlen($newIds)==0 ? null : $newIds=substr( $newIds, 0, strlen($newIds)-1 );
			($newIds!="" && $record->getId_document_files_ar()!="") ? $record->setId_document_files_ar( $record->getId_document_files_ar().','.$newIds ) : null;
			($newIds!="" && $record->getId_document_files_ar()=="") ? $record->setId_document_files_ar( $newIds ) : null;
			
			//On sauvegarde l'objet ebook ainsi modifié
			if( $manager->save($record) ){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
				$this->httpRequette->sessionUnset('form');
				$this->httpReponse->redirect('show-'.$id);
			}else{
				$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				$this->httpReponse->redirect('update-index-'.$id);
			}	
		}
             
        public function executeUpdateMiniature(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('ebook');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id);       
            if(!is_object($record))
                $this->httpReponse->redirect404();
                
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
            
            $f_image=$this->httpRequette->fileData('miniature');
            $imageController = new ImageController($this->app, null, 'update');
            $flag=$imageController->executeUpdate(['id'=>$record->getId_image_miniature(),
													'file'=>$f_image, 
													'description'=>$this->config['define']['default-description-image'],
													'redimention'=>true,
													'x'=>$this->config['define']['miniature-x'],
													'y'=>$this->config['define']['miniature-y']]);	
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
                
            $manager=$this->managers->getManagerOf('ebook');
            $requete=new Requete();
            ($obj->getSerie()!="") ? $requete->addCol(new StringCol(['name'=>'serie', 'table'=>'ebook', 'value'=>$obj->getSerie()])) : null;
            $requete->addCol(new StringCol(['name'=>'auteur', 'table'=>'ebook', 'value'=>$obj->getAuteur(), 'logicalOperator'=>'||']));
            return $requete;    
        }
}
