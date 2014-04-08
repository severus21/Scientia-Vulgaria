<?php
/*
 * name: EpisodeController
 * @description : 
 */

class EpisodeController extends MiddleController{ 
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
            $manager=$this->managers->getManagerOf('Episode');
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
					$this->httpReponse->redirect('../saison/show-'.$record->getIdSaison());
				return false;
            }
            
            //On supprime les videos
            $id_files=explode(',', $record->getId_video_files_ar());
            $videoController = new VideoController($this->app, null, 'delete');
			for($a=0 ; $a<count($id_files); $a++){
				!empty($id_files[$a]) ? $videoController->executeDelete($id_files[$a]) : null;
			}
            
            $manager->delete($id);
  
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-delete']);
            if(empty($idServ))
            $this->httpReponse->redirect('../saison/show-'.$record->getIdSaison());
        }
        
        public function executeMultiDelete($idSaison){//Traitement déjà effectué dans saison delete
			$manager=$this->managers->getManagerOf('Episode');
			$requete=new Requete();
			$requete->addCol( new IntCol( ['name'=>'idSaison', 'comparisonOperator'=>'=', 'value'=>$idSaison ]));
			$records=$manager->getList($requete);
			
			for($a=0 ; $a<count($records) ; $a++)
				$this->executeDelete( $records[$a]->getId(), true);
			return true;
		}
        
         public function executeDownload(){
			$manager=$this->managers->getManagerOf('Episode');
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
					$process->download($record->getNom());
				break;
				default:
					$this->httpReponse->redirect404();
				break;
			}
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
            $this->page->addVar('title', 'Ajout episode');
            parent::executeInsertIndex();
        }
        
        public function executeInsert(){
			ignore_user_abort(true);
			if($this->httpRequette->fileExists('file'))
				$f_file=$this->httpRequette->fileData('file');
			elseif($this->httpRequette->postExists('url'))
				$f_url=$this->httpRequette->postData('url');
				
            $f_image=$this->httpRequette->fileData('miniature');
            $id=$this->httpRequette->getData('id');
			$saisonManager=$this->managers->getManagerOf('saison');
			$saisonRecord=$saisonManager->get($id);
			if(!$saisonRecord)
                $this->httpReponse->redirect404();

            $formBuilder = new EpisodeFormBuilder(null, $this->config);
            $formBuilder->buildInsert();
            $formBuilder->setValues(array(
                'nom'=>$this->httpRequette->postData('nom'),
                'n'=>$this->httpRequette->postData('n'),
                'date'=>$this->httpRequette->postData('date'),
                'resume'=>$this->httpRequette->postData('resume'),
                'subtitle'=>$this->httpRequette->postData('subtitle')
                
            ));

            if(!$formBuilder->getForm()->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index-'.$id);
            }
            $record=$formBuilder->getForm()->getEntity();
           
            $videoController = new VideoController($this->app, 'Video', 'insert');
            
            $manager=$this->managers->getManagerOf('Episode');
            
			//Ajout de la video
			$id_video_files='';
			for($a=1; $a<10 ;$a++){
				$tmpD1=['description'=>$this->config['define']['default-description-video']];
				if(!empty($f_file))
					$tmpD1['file']=$f_file;
				elseif(!empty($f_url))
					$tmpD1['url']=$f_url;
				
				$video=$videoController->executeInsert($tmpD1, false);	
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
						
			
            $record->setLogin($this->app->getUser()->getLogin());
            $record->setIdSaison($id);
            $record->setIdSerie($saisonRecord->getIdSerie());
            
            if(!$record->isValid()){
				$idAr=explode(',', $id_video_files);
				for($a=0; $a<count($idAr); $a++)
					$videoController->executeDelete($idAr[$a]);
				
				
                $this->httpReponse->redirect('insert-index-'.$id);
            }
             
             
            $record->setId($manager->save($record));
            $i=$record->getId();   
            if(!is_numeric($i)){
				$idAr=explode(',', $id_video_files);
				for($a=0; $a<count($idAr); $a++)
					$videoController->executeDelete($idAr[$a]);
				
				
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('insert-index-'.$id);
            }
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);
            $this->httpRequette->sessionUnset('form');  
            $this->httpReponse->redirect( 'show-'.$record->getId() );
        }
        
        public function executeUpdateIndex(){
            $this->page->addVar('title', 'Update episode');
            $id=$this->httpRequette->getData('id');
            //On construit le menu
            $navBuilder=new EpisodeNavBuilder(null, $this->config);
            $nav=$navBuilder->buildUpdateTop($id);
            $this->page->addVar('secondaryTopNav', $nav);
            
            
            //On construit les formulaires
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
				$id=$this->httpRequette->getData('id');
				$manager=$this->managers->getManagerOf('episode');
				$record=$manager->get($id);
				$objFact=new ObjectFactory();
				$episode=$objFact->buildObject($record);
				
                $formBuilder = new EpisodeFormBuilder(null, $this->config);
                $form='';
                
                $formBuilder->buildUpdateFile($id, $episode->getFiles());
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
            $manager=$this->managers->getManagerOf('episode');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
            
            $formBuilder = new EpisodeFormBuilder(null, $this->config);
            $formBuilder->buildUpdateInfo($id);
            $formBuilder->setValues(array(
                'nom'=>$this->httpRequette->postData('nom'),
                'n'=>$this->httpRequette->postData('n'),
                'date'=>$this->httpRequette->postData('date'),
                'resume'=>$this->httpRequette->postData('resume'),
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
            $manager=$this->managers->getManagerOf('episode');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id); 
            if(!is_object($record))
                $this->httpReponse->redirect404();
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('../saison/show-'.$record->getIdSaison());
            }
                  
            $videoController = new VideoController($this->app, null, 'insert');
            $episodeController = new EpisodeController($this->app, null, 'insert');
			$idFiles=explode(',', $record->getId_video_files_ar());
			$nbrDelMax=count($idFiles);
			
			
			//On supprime les episodes à supprimer
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

			$videoController->executeUpdateStatut();
			
			//On sauvegarde l'objet episode ainsi modifié
			if( $manager->save($record) ){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-record']); 
				$this->httpRequette->sessionUnset('form'); 
				$this->httpReponse->redirect('show-'.$id);
			}else{
				$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				$this->httpReponse->redirect('update-index-'.$id);
			}	
		}
		
        public function executeShow($flagPag=true, $flagNav=true){
			parent::executeShow(false,true);
		}
}
