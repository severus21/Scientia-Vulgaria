<?php
/*
	Auteur : Oceane21
	Version : 1.0.0
	Projet : Scientia Vulgaria Project
*/

class TmpfileController extends FileController{

	public function executeDelete(){
		$objFact=new ObjectFactory();
		$manager=$this->managers->getManagerOf('Tmpfile');
		$requete=new Requete();
		$requete->addCol( new IntCol(['name'=>'ttl', 'comparisonOperator'=>'<', 'value'=>time()]) );
		$tmpfiles=$objFact->buildObjectFromRequete('Tmpfile', $requete);
		$process=new FileProcess();
		
		
		for($a=0; $a<count($tmpfiles) ; $a++){
			$process->setFile($tmpfiles[$a]);
            $process->unlink();
            $manager->delete($tmpfiles[$a]->getRecord()->getId());
		}
	}
	
	public function executeInsertIndex(){
		$this->page->addVar('title', 'Ajout fichier temporaire');
		//On construit le formulaire
		if($this->httpRequette->sessionExists('form')){
			$this->page->addVar('form', $this->httpRequette->sessionData('form'));
			$this->httpRequette->sessionUnset('form');
		}else{
			$formBuilder = new TmpfileFormBuilder(null, $this->config);
			$formBuilder->buildInsert();
			$form=$formBuilder->getForm()->createView();
			$this->page->addVar('form', $form);
		}
		$this->page->addVar('config', $this->config['view']);
	}
	
	//dataServ pour la compatibilitée avec FileController
	public function executeInsert($dataServ=null){
		ignore_user_abort(true);
		
		$f_file=null; $f_url=null; 
		//Vérification du form
		if($this->httpRequette->fileExists('file'))
			$f_file=$this->httpRequette->fileData('file');
		elseif($this->httpRequette->postExists('url'))
			$f_url=$this->httpRequette->postData('url');
			
		if( (empty($f_file) && empty($f_url)) ){
			$this->httpRequette->addSessionVar('form', $formBuilder->getForm()->createView());
			$this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
			$this->httpReponse->redirect('insert-index');
		}
		
		//Initialisation
		$a=1;
		$tmpfileRecords=array(); $tmpD1=array();
		$tmpD1['dir']=Tmpfile::DIR; $tmpD1['redirect']='insert-index'; $tmpD1['allowAllTypes']=true;
		$manager=$this->managers->getManagerOf('Tmpfile'); 
		
		//Ajout des fichiers
		while( $f_url!='' ||  !empty($f_file) ){
			if(!empty($f_file))
				$tmpD1['file']=$f_file;
			elseif(!empty($f_url))
				$tmpD1['url']=$f_url;
            
			
            $file=parent::executeInsert($tmpD1);
            $data=array('path'=>$file->getPath(),
                        'ttl'=>time()+Tmpfile::TTL,
                        'type'=>$file->getType(),
                        'description'=>"",
                        'ext'=>$file->getExt(),
                        'login'=>$file->getLogin(),
                        'size'=>$file->getSize(),
                        'md5'=>$file->getMd5(),
                        'sha512'=>$file->getSha512()
            );
            
            
            $tmpfileRecord=new TmpfileRecord($data);
            if(!$tmpfileRecord->isValid()){
				$this->app->getUser()->setFlash($this->config['flash']['form-invalid']);
                $this->app->getHttpReponse()->redirect('insert-index');
            }     
			
			$tmpfileRecord->setId($manager->save($tmpfileRecord));
			if(!is_int($tmpfileRecord->getId())){
				$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				$this->httpReponse->redirect('insert-index');
			}
			$tmpfileRecords[]=$tmpfileRecord;
			
			
			
			//On prépare la prochaine itération
			if($this->httpRequette->fileExists('file'.$a))
				$f_file=$this->httpRequette->fileData('file'.$a);
			elseif($this->httpRequette->postExists('url'.$a))
				$f_url=$this->httpRequette->postData('url'.$a);
			else{
				$f_url='';$f_file=array();
			}		
		}
		
		$this->page->addVar('tmpfileRecords', $tmpfileRecords);
		$this->page->addVar('config', $this->config['view']);
		
		
	}
}
