<?php
/*
 * name: ImageController
 * @description : 
 */

class ImageController extends FileController{ 
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
            $manager=$this->managers->getManagerOf('Image');
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
			$process=new ImageProcess($obj);
			$min=$obj->getMiniature();
			//On supprime l'image
			$process->unlink();
			$manager->delete($id);
			
			//On supprime la miniature(si existance);
			if(isset($min))
				$this->executeDelete($min->getRecord()->getId());
			
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
            $this->page->addVar('title', 'Images');
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
            $this->page->addVar('title', 'Ajout image');
            parent::executeInsertIndex();
        }
        
        //id_image_miniature=-1 pour les miniatures
        public function executeInsert($dataServ=null){
            //On recupère les donées soit depuis le formulaire soit depuis le paramètre
            if(empty($dataServ)){
                $file=$this->httpRequette->fileData('file');
                $redimention=$this->httpRequette->postData('redimention');
                $x=(int)$this->httpRequette->postData('x');
                $y=(int)$this->httpRequette->postData('y');
                $description=$this->httpRequette->postData('description');
                $formBuilder = new ImageFormBuilder();
                $formBuilder->buildInsert();
                $formBuilder->setValues(array('x'=>$x,
                                                'y'=>$y,
                                                'description'=>$description));
                
                if(!$formBuilder->getForm()->isValid()){
                    $this->httpRequette->addSessionVar('form', $formBuilder->getForm()->createView());
                    $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                    $this->httpReponse->redirect('insert-index');
                }   
            }else{
				if(empty($dataServ['file']))
					return false;
					
                $file=$dataServ['file'];
                $redimention=$dataServ['redimention'];
                $x=(int)$dataServ['x'];
                $y=(int)$dataServ['y']; 
                $description=$dataServ['description'];   
            }
            isset($dataServ)?$redirect=false : $redirect=true;
            $file=parent::executeInsert(array('file'=>$file, 'dir'=>Image::DIR, 'redirect'=>$redirect));
            if(!$file)
				return false;
				
            $manager=$this->managers->getManagerOf('Image');  
            /* 
                Traitement relatif à l'image
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
			//Dans le cas où on veut un id précis notamment ac update
            if(isset($dataServ['id'])){
                $data['id']=$dataServ['id'];
            }
            $img=new Image($data);  
             
            /* 
                Traitement relatif à la miniature
            */
                $process=new ImageProcess($img);
                if(($redimention || $redimention=='true') && $x>0 && $y>0 && $x<2000 && $y<2000){                        
                    $dataMin=$process->createMiniature(Image::DIR, $x, $y, true);     
                    $dataMin=array('path'=>$dataMin['path'],
                                    'type'=>$file->getType(),
                                    'id_image_miniature'=>-1,
                                    'ext'=>$file->getExt(),
                                    'login'=>$file->getLogin(),
                                    'size'=>filesize($dataMin['path']),
                                    'x'=>$dataMin['x'],
                                    'y'=>$dataMin['y']
                    );
                    
                    $minProcess=new ImageProcess(new Image($dataMin));
                    $dataMin['md5']=$minProcess->hashFile('md5');
                    $dataMin['sha512']=$minProcess->hashFile('sha512');
                    
                    $dataMin['id']=$manager->insert($dataMin);
                    $dataMin['id']>0 ?  $data['id_image_miniature']=$dataMin['id'] : null;
                } 
               
                 
            $xy=$process->getXY();
            $data['x']=$xy['x'];
            $data['y']=$xy['y'];
            $imgR=new ImageRecord($data);
            if(!$imgR->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['form-invalid']);
                if(empty($dataServ))
					$this->app->getHttpReponse()->redirect('insert-index');
				else
					return false;
            }
            
            $imgR->setId($manager->save($imgR));
            $i=$imgR->getId();
            if(!is_int($i)){
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
                return $objectFactory->buildObject($imgR);
            }
        }
        
        public function executeGestion(){
            $this->page->addVar('title', 'Mes images');
            parent::executeGestion();
        }   
    
        public function executeSearch($page){
            if($this->httpRequette->method()=='POST'){
                $formBuilder = new ImageFormBuilder($page);
                $formBuilder->buildSearch();
                $formBuilder->setValues(array('description'=> $this->httpRequette->postData('description')));
                
                if(!$formBuilder->getForm()->isValid()){
                    $this->httpRequette->addSessionVar('form', $formBuilder->getForm()->createView());
                    $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                    $this->httpReponse->redirect($page);
                }
                
                $requeteBuilder=new ImageRequeteBuilder();
                $requeteBuilder->buildFromForm($formBuilder->getForm());
                return $requeteBuilder->getRequete();
            }else{
                return new Requete();
            }
        }
    
        public function executeUdateIndex(){
            $this->page->addVar('title', 'Update image');
            parent::executeUpdateIndex();
        }
    
        public function executeUpdate($dataServ=null){
            $manager=$this->managers->getManagerOf('Image');
            if(isset($dataServ['id'])){
                $id=$dataServ['id'];
                $data=$dataServ;
            }elseif($this->httpRequette->getExists('id')){
                $data=array();
                $data['id']=$this->httpRequette->getData('id');
                $data['file']=$this->httpRequette->fileData('file');
                $data['redimention']=$this->httpRequette->postData('redimention');
                $data['x']=(int)$this->httpRequette->postData('x');
                $data['y']=(int)$this->httpRequette->postData('y');
                $data['description']=$this->httpRequette->postData('description');
                
                $id=$this->httpRequette->getData('id');   
            }else{
                $this->httpReponse->redirect404();
            }
            
            
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
            
            
            
            //on check pour savoir si les données sont bonne
            $imgR=new ImageRecord($data);
            if(!$imgR->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                if(empty($dataServ))
					$this->app->getHttpReponse()->redirect('update-index');
				else 
					return false;
            }
			
			//$objFactory=new ObjectFactory();
            //$obj=$objFactory->buildObject($record);
            
            //On supprime la miniature
           is_int($imgR->getId_image_miniature()) ? $this->executeDelete($imgR->getId_image_miniature()) : null;
            $img=$this->executeInsert($data);
            
            
            if(isset($dataServ)){
                return $img;
            }else{
              //  $this->app->getUser()->setFlash($obj->generateHtmlPath());
               // $this->httpReponse->redirect('index');
            }
            
            
        }

}
