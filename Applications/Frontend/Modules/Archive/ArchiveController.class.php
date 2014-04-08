<?php
/*
 * name: ArchiveController
 * @description : 
 */

class ArchiveController extends FileController{ 
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
         public function executeDelete($id){
            $manager=$this->managers->getManagerOf('Archive');            
            $record=$manager->get($id);
            if(!is_object($record))
				return false;
				
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
                return false;
            }
            
            $objFactory=new ObjectFactory();
            $obj=$objFactory->buildObject($record);
            $process=new ArchiveProcess($obj);
            $process->unlink();
            $manager->delete($id);
            return true;
        }

        public function executeInsert($dataServ=null){
			if(array_key_exists('file', $dataServ))
				$file=parent::executeInsert(array('file'=>$dataServ['file'], 'dir'=>Archive::DIR, 'redirect'=>false));
			elseif(array_key_exists('url', $dataServ))
				$file=parent::executeInsert(array('url'=>$dataServ['url'], 'dir'=>Archive::DIR, 'redirect'=>false));
           
            $manager=$this->managers->getManagerOf('Archive');  
            if(!$file)
                return false;         
            
            $data=array('path'=>$file->getPath(),
                        'type'=>$file->getType(),
                        'ext'=>$file->getExt(),
                        'login'=>$file->getLogin(),
                        'size'=>$file->getSize(),
                        'md5'=>$file->getMd5(),
                        'sha512'=>$file->getSha512(),
                        'description'=>$dataServ['description']
            );

            if(isset($dataServ['id'])){//Dans le cas où on veut un id précis notamment ac update
                $data['id']=$dataServ['id'];
            }

            $record=new ArchiveRecord($data);
            if(!$record->isValid()){
                $user->setFlash($this->config['flash']['invalid-form']);
                return false;
            }
            
            $record->setId($manager->save($record));//Attention ne pas tt regrouper dans le test dans le cas d'un update save renvoit un bool
            $i=$record->getId();
            if(empty($i)){
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                return false;
            }
                  
            $objectFactory=new ObjectFactory();
            return $objectFactory->buildObject($record);
        }
    
        public function executeUpdate($dataServ){
            $manager=$this->managers->getManagerOf('Archive');
            $objFactory=new ObjectFactory();
            
            $record=$manager->get($dataServ['id']);
            if(!$record instanceof Record){
                return false;
            }
            
            if($record->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
                return false;
            }
            
            return $this->executeInsert($dataServ);
        }
}
