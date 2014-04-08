<?php
/*
 * name: DocumentController
 * @description : 
 */

class DocumentController extends FileController{ 
    /*
        Attributs
    */
        protected $displayTypes=['epub','pdf'];
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
        public function executeDelete($id){
            $manager=$this->managers->getManagerOf('Document');      
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
            $obj=$objFactory->buildObject($manager->get($id));
            $process=new DocumentProcess($obj);
            $process->unlink();
            $manager->delete($id);
            return true;
        }

        public function executeInsert($dataServ=null){
            if(array_key_exists('file', $dataServ))
				$file=parent::executeInsert(array('file'=>$dataServ['file'], 'dir'=>Archive::DIR, 'redirect'=>false));
			elseif(array_key_exists('url', $dataServ))
				$file=parent::executeInsert(array('url'=>$dataServ['url'], 'dir'=>Archive::DIR, 'redirect'=>false));
           
            if(!$file)
				return false;
				
            $manager=$this->managers->getManagerOf('Document');  
                
            $data=array('path'=>$file->getPath(),
                        'type'=>$file->getType(),
                        'ext'=>$file->getExt(),
                        'login'=>$file->getLogin(),
                        'size'=>$file->getSize(),
                        'md5'=>$file->getMd5(),
                        'sha512'=>$file->getSha512()
            );

            if(isset($dataServ['id'])){//Dans le cas où on veut un id précis notamment ac update
                $data['id']=$dataServ['id'];
            }

            $record=new DocumentRecord($data);
            if(!$record->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                return false;
            } 
            
            $record->setId($manager->save($record));//A ne pas simplifier car save peut renvoyer un bool ou un int        
            if(!is_int($record->getId())){
               $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				return false;
            }
                  
            $objectFactory=new ObjectFactory();
            return $objectFactory->buildObject($record);
        }
    
        public function executeUpdate($dataServ){
            $manager=$this->managers->getManagerOf('Document');
            $objFactory=new ObjectFactory();
            
            $record=$manager->get($dataServ['id']);
            if(!$record instanceof Record){
				return false;
            }
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
                if(empty($dataServ))
					$this->httpReponse->redirect('insert-index');
				else 
					return false;
            }
            
            $obj=$objFactory->buildObject($record);
            return $this->executeInsert($dataServ);
        }

		public function executeShow($flagPag = true, $flagNav = true){
            $this->httpRequette->getExists('id') ? $id=$this->httpRequette->getData('id'): null;
            $this->httpRequette->getExists('type') ? $type=$this->httpRequette->getData('type'): null;
            ( empty($id) || !in_array($type, $this->displayTypes) ) ?  $this->httpReponse->redirect404() : null;
         
            $manager=$this->managers->getManagerOf('document');
            $record=$manager->get($id);
            empty($record) ?  $this->httpReponse->redirect404() : null;
			$objFac=new ObjectFactory();
            $obj=$objFac->buildObject($record);    
            !is_file($obj->getPath()) ? $this->httpReponse->redirect404() : null;
			
			$this->page->addVar('type', $type);
			$this->page->addVar('document', $obj);
		}
}
