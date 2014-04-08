<?php
/*
 * name: AudioController
 * @description : 
 */

class AudioController extends FileController{ 
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
            $manager=$this->managers->getManagerOf('Audio');
            if(isset($idServ))
                $id=$idServ;
            elseif($this->httpRequette->getExists('id'))
                $id=$this->httpRequette->getData('id');
            
            if(empty($id))
                $this->httpReponse->redirect404();
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				if(empty($idServ))
                    $this->httpReponse->redirect('index');
                else
                    return false;
            }
            
            $objFactory=new ObjectFactory();
            $obj=$objFactory->buildObject($manager->get($id));
            $process=new AudioProcess($obj);
            $process->unlink();
            $manager->delete($id);
            $streaming=$obj->getStreaming();
            
            if(isset($streaming))
                $this->executeDelete($streaming->getRecord()->getId());
  
            if(empty($idServ)){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-delete'];
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
            $this->page->addVar('title', 'Audios');
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
            $this->page->addVar('title', 'Ajout audio');
            parent::executeInsertIndex();
        }
        
        public function executeInsert($dataServ=null){//id_audio_streaming=-1 pour les audio destinées streaming
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
					$dataServ=array('file'=>$this->httpRequette->fileData('file');, 'description'=>$description, 'redirect'=>true);
				elseif($this->httpRequette->postExists('url'))
					$dataServ=array('url'=>$this->httpRequette->fileData('url');, 'description'=>$description, 'redirect'=>true);
					
            }else{
				if(empty($dataServ['file']) && empty($dataServ['url']))
					return false;
				$dataServ['redirect']=false;
            }
            
            $dataServ['dir']=Audio::DIR;
            $file=parent::executeInsert($dataServ);
            $manager=$this->managers->getManagerOf('Audio');  
            
            if(!$file)
				return false;
            
            /* 
                Traitement relatif à la audio
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
            

            
            
            $audioR=new AudioRecord($data);
            if(!$audioR->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['form-invalid']);
                if(empty($dataServ))
					$this->app->getHttpReponse()->redirect('insert-index');
				else
					return false;
            }     
            /* 
                Traitement relatif au fichier de streaming 
            */
                $dataStream=array('path'=>Audio::DIR.'/'.String::uniqStr().'.ogg',
                                'id_audio_streaming'=>-1,
                                'login'=>$file->getLogin(),
                                'type'=>'audio/ogg',
                                'ext'=>'ogg'
                );
                
                //Convertion
                $record=new AudioRecord($data);
                $audio=new Audio(array_merge(['record'=>$record], $data));
                $audioProcess=new AudioProcess($audio);
                $audioProcess->hydrateFromFile($audio->getPath());
                $audioProcess->aud2strea($dataStream['path']);
                //Extraction des renseignements 
                $secondRecord=new AudioRecord($dataStream);
                $stream=new Audio(array_merge(['record'=>$secondRecord], $dataStream));
                $audioProcess->setFile($stream);                
                $audioProcess->hydrateFromFile($dataStream['path']);
                
                //Enregistrement de la audio pour le streaming
                $record->setId_audio_streaming($manager->save($secondRecord));
                isset($dataServ['id']) ? $record->setId($dataServ['id']): null;
                
            $record->setId($manager->save($record));
            if(!is_numeric($record->getId())){
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                if(empty($dataServ))
                    $this->httpReponse->redirect('insert-index');
                else
                    return false;
            }
                
            if(empty($dataServ)){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);
                $this->httpReponse->redirect('index');
            }else{    
                $objectFactory=new ObjectFactory();
                return $objectFactory->buildObject($record);
            }
        }
        
        public function executeGestion(){
            $this->page->addVar('title', 'Mes audios');
            parent::executeGestion();
        }   
    
        public function executeSearch($page){
            if($this->httpRequette->method()=='POST'){
                $formBuilder = new AudioFormBuilder($page);
                $formBuilder->buildSearch();
                $formBuilder->setValues(array('description'=> $this->httpRequette->postData('description')));
                
                if(!$formBuilder->getForm()->isValid()){
                    $this->httpRequette->addSessionVar('form', $formBuilder->getForm()->createView());
                    $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                    $this->httpReponse->redirect($page);
                }
                
                $requeteBuilder=new AudioRequeteBuilder();
                $requeteBuilder->buildFromForm($formBuilder->getForm());
                return $requeteBuilder->getRequete();
            }else{
                return new Requete();
            }
        }
    
        public function executeUpdateIndex(){
            $this->page->addVar('title', 'Update audio');
            parent::executeUpdateIndex();
        }
    
        public function executeUpdate($dataServ=null){
            $manager=$this->managers->getManagerOf('Audio');
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
            
            $objFactory=new ObjectFactory();
            
             $record=$manager->get($id);
            if(!$record instanceof Record){
                if(empty($dataServ))
					$this->httpReponse->redirect404();
				else 
					return false;
            }
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
                if(empty($dataServ))
					$this->httpReponse->redirect('insert-index');
				else 
					return false;
            }
            
            //on check pour savoir si les données sont bonnes
            $audioR=new AudioRecord($data);
            if(!$audioR->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                if(empty($dataServ))
					$this->app->getHttpReponse()->redirect('update-index');
				else 
					return false;
            }

			$objFactory=new ObjectFactory();
            $obj=$objFactory->buildObject($record);
            $this->executeDelete($id);
            $audio=$this->executeInsert($data);
            if(isset($dataServ)){
                return $audio;
            }else{
                $this->app->getUser()->setFlash($obj->generateHtmlPath());
                $this->httpReponse->redirect('index');
            }
            
            
        }
        
        public function executeShow($idServ=null){
            $manager=$this->managers->getManagerOf('Audio');
            $id=$this->httpRequette->getData('id');
            
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
            $objFac=new ObjectFactory();
            $this->page->addVar('audio', $objFac->buildObject($record));
        }
}
