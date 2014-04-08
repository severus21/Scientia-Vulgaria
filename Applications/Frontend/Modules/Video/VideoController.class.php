<?php
/*
 * name: VideoController
 * @description : 
 */

class VideoController extends FileController{ 
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
        public function executeDelete($idServ=null){
            $manager=$this->managers->getManagerOf('Video');
            if(isset($idServ))
                $id=$idServ;
            elseif($this->httpRequette->getExists('id'))
                $id=$this->httpRequette->getData('id');
            
            if(empty($id))
                $this->httpReponse->redirect404();
            
            //On vérifie les droits   
            $record=$manager->get($id);
            if(!is_object($record)){
				if(empty($idServ))
					$this->httpReponse->redirect404();
				else
					return false;
			}
			
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				if(empty($idServ))
                    $this->httpReponse->redirect('index');
                else
                    return false;
            }
            $objFactory=new ObjectFactory();
            $obj=$objFactory->buildObject($record);
            $process=new VideoProcess($obj);
            $process->unlink();
            $manager->delete($id);
            $streaming=$obj->getStreaming();
            
            if(isset($streaming))
                $this->executeDelete($streaming->getRecord()->getId());
  
            if(empty($idServ)){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-delete']);
                $this->httpReponse->redirect('index');
            }else
                return true;
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
            $this->page->addVar('title', 'Videos');
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
            $this->page->addVar('title', 'Ajout video');
            parent::executeInsertIndex();
        }
        
        //id_video_streaming=-1 pour les video destinées streaming
        public function executeInsert($dataServ=null, $conv=true){
			ignore_user_abort(true);
			//On recupère les donées soit depuis le formulaire soit depuis le paramètre
            if(empty($dataServ)){
                $description=$this->httpRequette->postData('description');
                $formBuilder = new VideoFormBuilder();
                $formBuilder->buildInsert();
                $formBuilder->setValues(array('description'=>$description));
                
                if(!$formBuilder->getForm()->isValid()){
                    $this->httpRequette->addSessionVar('form', $formBuilder->getForm()->createView());
                    $this->app->getUser()->setFlash('Champs invalides');
                    $this->httpReponse->redirect('insert-index');
                }   
                
                if($this->httpRequette->fileExisrs('file'))
					$dataServ=array('file'=>$this->httpRequette->fileData('file'), 'description'=>$description, 'redirect'=>true);
				elseif($this->httpRequette->postExists('url'))
					$dataServ=array('url'=>$this->httpRequette->fileData('url'), 'description'=>$description, 'redirect'=>true);
					
            }else{
				if(empty($dataServ['file']) && empty($dataServ['url']))
					return false;
				$dataServ['redirect']=false;
				$description=$dataServ['description'];
            }
            
            $dataServ['dir']=Video::DIR;
            $file=parent::executeInsert($dataServ);
            $manager=$this->managers->getManagerOf('Video');  
          
            if(!$file)
				return false;
            
            /* 
                Traitement relatif à la video
            */
            
            $data=array('path'=>$file->getPath(),
                        'type'=>$file->getType(),
                        'description'=>$description,
                        'ext'=>$file->getExt(),
                        'login'=>$file->getLogin(),
                        'size'=>$file->getSize(),
                        'md5'=>$file->getMd5(),
                        'sha512'=>$file->getSha512()
            );
            
            
            $videoR=new VideoRecord($data);
            if(!$videoR->isValid()){
				$this->app->getUser()->setFlash($this->config['flash']['form-invalid']);
                if(empty($dataServ))
					$this->app->getHttpReponse()->redirect('insert-index');
				else
					return false;
            } 
            
            $record=new VideoRecord($data);
			$video=new Video(array_merge(['record'=>$record], $data)); 
			$videoProcess=new VideoProcess($video);
			$videoProcess->hydrateFromFile($video->getPath());   
            /* 
                Traitement relatif au fichier de streaming 
            */
            if($conv){
                $dataStream=array('path'=>Video::DIR.'/'.String::uniqStr().'.webm',
                                'id_video_streaming'=>-1,
                                'login'=>$file->getLogin(),
                                'type'=>'video/webm',
                                'ext'=>'webm',
                                'statut'=>'done'
                );
                
                //Construction des objets
                $secondRecord=new VideoRecord($dataStream);
                $stream=new Video(array_merge(['record'=>$secondRecord], $dataStream));
                $video->setStreaming($stream);
                
                
               
                //Enregistrement de la video pour le streaming
                $record->setId_video_streaming($manager->save($secondRecord));
                isset($dataServ['id']) ? $record->setId($dataServ['id']): null; 
				
			}
				
			$record->setId($manager->save($record));
			if(!is_int($record->getId())){
				$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				if(empty($dataServ))
					$this->httpReponse->redirect('insert-index');
				else
					return false;
			}
             
				//Convertion si nécéssaire, sinon ajout à la liste de convertion
				/*	if($conv){
					$stream->getRecord()->setStatut('processing');
					$stream->getRecord()->setLastStatutTime(time());
					$manager->save( $stream->getRecord() );
					
					$videoProcess->vid2strea();
					$videoProcess->setFile($stream);                
					$videoProcess->hydrateFromFile($stream->getPath());
					$stream->getRecord()->setStatut('done');
					$manager->save( $stream->getRecord() );
				}*/
               
            if(empty($dataServ)){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);
                $this->httpReponse->redirect('index');
            }else{    
                $objectFactory=new ObjectFactory();
                return $objectFactory->buildObject($record);
            }
        }
        
        public function executeGestion(){
            $this->page->addVar('title', 'Mes videos');
            parent::executeGestion();
        }   
		
        public function executeSearch($page){
            if($this->httpRequette->method()=='POST'){
                $formBuilder = new VideoFormBuilder($page);
                $formBuilder->buildSearch();
                $formBuilder->setValues(array('description'=> $this->httpRequette->postData('description')));
                
                if(!$formBuilder->getForm()->isValid()){
                    $this->httpRequette->addSessionVar('form', $formBuilder->getForm()->createView());
                    $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                    $this->httpReponse->redirect($page);
                }
                
                $requeteBuilder=new VideoRequeteBuilder();
                $requeteBuilder->buildFromForm($formBuilder->getForm());
                return $requeteBuilder->getRequete();
            }else{
                return new Requete();
            }
        }
    
        public function executeUpdateIndex(){
            $this->page->addVar('title', 'Update video');
            parent::executeUpdateIndex();
        }
    
        public function executeUpdate($dataServ=null){
            $manager=$this->managers->getManagerOf('Video');
            if(isset($dataServ['id'])){
                $id=$dataServ['id'];
                $data=$dataServ;
            }elseif($this->httpRequette->getExists('id')){
                $data=array();
                $id=$this->httpRequette->getData('id');     
                $data['id']=$id;
                $data['description']=$this->httpRequette->postData('description');    
                
                if($this->httpRequette->fileExists('file'))
					$data['file']=$this->httpRequette->fileData('file');
				elseif($this->httpRequette->postExists('url'))
					$data['url']=$this->httpRequette->postData('url');
            }else{
                $this->httpReponse->redirect404();
            }
            
            //On vérifie les droits
            $record=$manager->get($id);
            if(!$record instanceof Record){
                if(empty($dataServ))
					$this->httpReponse->redirect404();
				else 
					return false;
            }
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
            
            
            //on check pour savoir si les données sont bonnes
            $videoR=new VideoRecord($data);
            if(!$videoR->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                if(empty($dataServ))
					$this->app->getHttpReponse()->redirect('update-index');
				else 
					return false;
            }
            
			$objFactory=new ObjectFactory();
            $obj=$objFactory->buildObject($record);
            $process=new VideoProcess($obj);
            $process->unlink();
            $streaming=$obj->getStreaming();
            
            if(isset($streaming))
                $this->executeDelete($streaming->getRecord()->getId());
                
            $video=$this->executeInsert($data);
            if(isset($dataServ)){
                return $video;
            }else{
                $this->app->getUser()->setFlash($obj->generateHtmlPath());
                $this->httpReponse->redirect('index');
            }
        }
        
        public function executeShow($flagPag = true, $flagNav = true){
            $manager=$this->managers->getManagerOf('Video');
            $id=$this->httpRequette->getData('id');
            
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
            $objFac=new ObjectFactory();
            $this->page->addVar('video', $objFac->buildObject($record));
            $this->page->addVar('config',  $this->config);
        }

		public function executeUpdateStatut(){
			$manager=$this->managers->getManagerOf('Video');
			$objFact=new ObjectFactory();
			$requete=new Requete();
			$requete->addCol( new StringCol(['name'=>'statut', 'comparisonOperator'=>'=', 'strict'=>true, 'value'=>'waiting']));
			$requete->addCol( new IntCol(['name'=>'id_video_streaming', 'comparisonOperator'=>'>', 'logicalOpertor'=>'and', 'value'=>'0']));

			//Videos jamais converties
			$videos=$objFact->buildObjectFromRequete('Video', $requete);
			for($a=0 ; $a<count($videos) ; $a++){
				$video=$videos[$a];
				$video->getRecord()->setStatut('processing');
				$video->getRecord()->setLastStatutTime(time());
				$manager->save( $video->getRecord() );
				
				$videoProcess= new VideoProcess($video);
				$videoProcess->vid2strea();
				$videoProcess->setFile($video);                
				$videoProcess->hydrateFromFile($video->getPath());
				$video->getRecord()->setStatut('done');
				$manager->save( $video->getRecord() );
			}
		}

}
