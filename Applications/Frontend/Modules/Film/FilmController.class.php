<?php
/*
 * name: FilmController
 * @description : 
 */

class FilmController extends MiddleController{ 
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
            $manager=$this->managers->getManagerOf('Film');
			$id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record))
				$this->httpReponse404();
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
                $this->httpReponse->redirect('index');
            }
            $id_files=explode(',', $record->getId_video_files_ar());
            $id_miniature=$record->getId_image_miniature();
            
            $videoController = new VideoController($this->app, null, 'delete');
            $imageController = new ImageController($this->app, null, 'delete');
            
			for($a=0 ; $a<count($id_files); $a++){
				!empty($id_files[$a]) ? $videoController->executeDelete($id_files[$a]) : null;
			}
            $imageController->executeDelete($id_miniature);
            
            $manager->delete($id);
  
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-delete']);
            $this->httpReponse->redirect('index');
        }
        
        public function executeDownload(){
			$manager=$this->managers->getManagerOf('Film');
            $id=$this->httpRequette->getData('id');
			$record=$manager->get($id);
            if(!is_object($record))
				$this->httpReponse->redirect404();
				
			$idFile=$this->httpRequette->getData('f');
			$type=$this->httpRequette->getData('t');

			switch($type){
				case 'video':
					$idAr=explode(',', $record->getId_video_files_ar());
					!in_array($idFile, $idAr) ? $this->httpReponse->redirect404() : null;
					
					$videoController=$this->managers->getManagerOf('video');
					$objFact=new ObjectFactory();
					
					$videoRecord=$videoController->get($idFile);
					
					$process=new FileProcess($objFact->buildObject($videoRecord));
					$process->download($record->getNom().'.'.$videoRecord->getExt());
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
            $this->page->addVar('title', 'Films');    
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
            $this->page->addVar('title', 'Ajout film');
            parent::executeInsertIndex();
        }
        
        public function executeInsert(){
			ignore_user_abort(true);
			if($this->httpRequette->fileExists('file'))
				$f_file=$this->httpRequette->fileData('file');
			elseif($this->httpRequette->postExists('url'))
				$f_url=$this->httpRequette->postData('url');
				
            $f_image=$this->httpRequette->fileData('miniature');

            $formBuilder = new FilmFormBuilder(null, $this->config);
            $formBuilder->buildInsert();
            $formBuilder->setValues(array(
                'acteurs'=>$this->httpRequette->postData('acteurs'),
                'id_filmCategorie_categorie'=>$this->httpRequette->postData('id_filmCategorie_categorie'),
                'date'=>$this->httpRequette->postData('date'),
                'langue'=>$this->httpRequette->postData('langue'),
                'nom'=>$this->httpRequette->postData('nom'),
                'realisateur'=>$this->httpRequette->postData('realisateur'),
                'resume'=>$this->httpRequette->postData('resume'),
                'saga'=>$this->httpRequette->postData('saga'),
                'subtitle'=>$this->httpRequette->postData('subtitle')
                
            ));
         
            if(!$formBuilder->getForm()->isValid() || empty($f_image)){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index');
            }
            $record=$formBuilder->getForm()->getEntity();
            
            $videoController = new VideoController($this->app, 'Video', 'insert');
            $imageController = new ImageController($this->app, 'Image', 'insert');
            
            $manager=$this->managers->getManagerOf('Film');

			//Ajout de la miniature
            $image=$imageController->executeInsert(['file'=>$f_image, 
													'description'=>$this->config['define']['default-description-image'], 
													'redimention'=>true, 
													'x'=>$this->config['define']['miniature-x'],
													'y'=>$this->config['define']['miniature-y']]);
													
			if(!$image)
                $this->httpReponse->redirect('insert-index');
            
						
			//Ajout de la video
			$id_video_files='';
			for($a=1; $a<10 ;$a++){
				$tmpD1=['description'=>$this->config['define']['default-description-video']];
				if(!empty($f_file))
					$tmpD1['file']=$f_file;
				elseif(!empty($f_url))
					$tmpD1['url']=$f_url;
				
				$video=$videoController->executeInsert($tmpD1);
				is_object($video) ? $id_video_files.=$video->getRecord()->getId().',' : null;
				$video=null;
				
				//On prépare la prochaine itération
				if($this->httpRequette->fileExists('file'.$a))
					$f_file=$this->httpRequette->fileData('file'.$a);
				elseif($this->httpRequette->postExists('url'.$a))
					$f_url=$this->httpRequette->postData('url'.$a);
				else{
					$f_url='';$f_file=array();
				}		
			}
            
            !empty($id_video_files) ? $record->setId_video_files_ar($id_video_files) : null;
            $record->setId_image_miniature($image->getRecord()->getId());
            $record->setLogin($this->app->getUser()->getLogin());
            
            if(!$record->isValid()){
				//On supprime tous ce qui a été créé
				$imageController->executeDelete($image->getRecord()->getId());
				$idAr=explode(',', $id_video_files);
				for($a=0; $a<count($idAr); $a++)
					$videoController->executeDelete($idAr[$a]);
				
                $this->httpReponse->redirect('insert-index');
            }
             
             
            $record->setId($manager->save($record));
            $i=$record->getId();   
            if(!is_numeric($i)){
				//On supprime tous ce qui a été créé
				$imageController->executeDelete($image->getRecord()->getId());
				$idAr=explode(',', $id_video_files);
				for($a=0; $a<count($idAr); $a++)
					$videoController->executeDelete($idAr[$a]);
				
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('insert-index');
            }
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect('show-'.$i);
        }
        
        public function executeGestion(){
            $this->page->addVar('title', 'Mes films');
            parent::executeGestion();
        } 
    
        public function executeSearch($page){
			$httpData=strtolower($this->httpRequette->method()).'Data';
			$httpExists=strtolower($this->httpRequette->method()).'Exists';

            $this->httpRequette->$httpExists('recherche') ? $recherche=$this->httpRequette->$httpData('recherche') : $recherche='';
            $this->httpRequette->$httpExists('id_filmCategorie_categorie') ? $categorie=$this->httpRequette->$httpData('id_filmCategorie_categorie') : $categorie='';
            $this->httpRequette->$httpExists('langue') ? $langue=$this->httpRequette->$httpData('langue') : $langue='';
            $this->httpRequette->$httpExists('order') ? $order=$this->httpRequette->$httpData('order') : $order='';
            
			if( empty($recherche) && empty($categorie) && empty($langue) && empty($order) )
				return new Requete();
            
            $formBuilder = new FilmFormBuilder(null, $this->config);
            $formBuilder->buildSearch();
            
            $formBuilder->setValues(array(
                'recherche'=> $recherche,
                'id_filmCategorie_categorie'=> $categorie,
                'langue'=> $langue,
                'order'=> $order
            ));
            
            if(!$formBuilder->getForm()->isValid()){
				
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect($page);
            }
            $requeteBuilder=new FilmRequeteBuilder();
            $requeteBuilder->buildFromForm($formBuilder->getForm());
            $requete= $requeteBuilder->getRequete();
            
            //On traite le cas de la recherche avec sphynx
            if($recherche!=''){
				$sphinx= SphinxFactory::getSphinxClient();
				$resultats = $sphinx->Query( $sphinx->escapeString($recherche), 'film', 'test');
				$ids= (!empty($resultats) && array_key_exists('matches', $resultats)) ? implode(',', array_keys($resultats['matches'])) : [];
                $requete->addCol(new MultipleCol(array('name'=>'id', 'value'=>$ids, 'logicalOperator'=>'&&', 'table'=>'film')));
            }

            $this->httpRequette->sessionUnset('form');
            return $requete;
        }
    
        public function executeUpdateIndex(){
            $this->page->addVar('title', 'Update film');
            //On construit le menu
            $navBuilder=new FilmNavBuilder(null, $this->config);
            $nav=$navBuilder->buildUpdateTop();
            $this->page->addVar('secondaryTopNav', $nav);
            
            //On construit les formulaires
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }elseif($this->httpRequette->getExists('id')){
				$id=$this->httpRequette->getData('id');
				$manager=$this->managers->getManagerOf('film');
				$record=$manager->get($id);
				$objFact=new ObjectFactory();
				$film=$objFact->buildObject($record);
				
                $formBuilder = new FilmFormBuilder(null, $this->config);
                $form='';
                
                $formBuilder->buildUpdateFile($id, $film->getFiles());
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
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
            $manager=$this->managers->getManagerOf('film');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
            
            $formBuilder = new FilmFormBuilder(null, $this->config);
            $formBuilder->buildUpdateInfo($id);
            $formBuilder->setValues(array(
                'acteurs'=>$this->httpRequette->postData('acteurs'),
                'id_filmCategorie_categorie'=>$this->httpRequette->postData('id_filmCategorie_categorie'),
                'date'=>$this->httpRequette->postData('date'),
                'langue'=>$this->httpRequette->postData('langue'),
                'nom'=>$this->httpRequette->postData('nom'),
                'realisateur'=>$this->httpRequette->postData('realisateur'),
                'resume'=>$this->httpRequette->postData('resume'),
                'saga'=>$this->httpRequette->postData('saga'),
                'subtitle'=>$this->httpRequette->postData('subtitle')
                
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
            $manager=$this->managers->getManagerOf('film');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id); 
            if(!is_object($record))
                $this->httpReponse->redirect404();
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
                  
            $videoController = new VideoController($this->app, null, 'insert');
            $filmController = new FilmController($this->app, null, 'insert');
			$idFiles=explode(',', $record->getId_video_files_ar());
			$nbrDelMax=count($idFiles);
			
			
			//On supprime les films à supprimer
			if( $this->httpRequette->postExists('deleteListFile') && $this->httpRequette->postData('deleteListFile')!="" ){
				$idDeletedFiles=explode(",", $this->httpRequette->postData('deleteListFile') );
				
				//on supprime les videos
				$a=0;
				while( $a<$nbrDelMax && $a<count($idDeletedFiles) ){
					$videoController->executeDelete($idDeletedFiles[$a]);
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
				$record->setId_video_files_ar($newIdFiles);
				if( !$manager->save($record) ){
					$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
					$this->httpReponse->redirect('update-index-'.$id);
				}
			}
			
			//On modifie les videos
			for($a=0; $a<11; $a++){
				$flag=true;

				if( $this->httpRequette->fileExists('file'.$a) && $this->httpRequette->fileData('file'.$a)['size']!=0
&& in_array( $this->httpRequette->postData('id-file'.$a) ,$idFiles ) ){
					$flag=$videoController->executeUpdate(['id'=>$this->httpRequette->postData('id-file'.$a),
						'file'=>$this->httpRequette->fileData('file'.$a),
						'description'=>$this->config['define']['default-description-video']]);
					
				}elseif($this->httpRequette->postExists('url'.$a) && in_array( $this->httpRequette->postData('id-file'.$a) ,$idFiles ) ){
					$flag=$videoController->executeUpdate(['id'=>$this->httpRequette->postData('id-file'.$a),
						'url'=>$this->httpRequette->postData('url'.$a),
						'description'=>$this->config['define']['default-description-video']]);
					
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
					$flag=$videoController->executeInsert(['file'=>$this->httpRequette->fileData('file'.$a),
															'description'=>$this->config['define']['default-description-video']]);
				}elseif($this->httpRequette->postExists('url'.$a)){
					$flag=$videoController->executeInsert(['url'=>$this->httpRequette->postData('url'.$a),
															'description'=>$this->config['define']['default-description-video']]);
				}	
				is_object($flag) ? $newIds.=$flag->getRecord()->getId()."," : null;
				$flag=null;
					
			}
			strlen($newIds)==0 ? null : $newIds=substr( $newIds, 0, strlen($newIds)-1 );
			($newIds!="" && $record->getId_video_files_ar()!="") ? $record->setId_video_files_ar( $record->getId_video_files_ar().','.$newIds ) : null;
			($newIds!="" && $record->getId_video_files_ar()=="") ? $record->setId_video_files_ar( $newIds ) : null;

			
			
			//On sauvegarde l'objet film ainsi modifié
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
            $manager=$this->managers->getManagerOf('film');
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
                
            $manager=$this->managers->getManagerOf('film');
            $requete=new Requete();
            
            ($obj->getSaga()!="") ? $requete->addCol(new StringCol(['name'=>'saga', 'table'=>'film', 'value'=>$obj->getSaga()])) : null;
            $requete->addCol(new StringCol(['name'=>'realisateur', 'table'=>'film', 'value'=>$obj->getRealisateur(), 'logicalOperator'=>'||']));
            return $requete;    
        }
}
