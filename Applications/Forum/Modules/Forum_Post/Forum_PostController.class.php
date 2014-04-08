<?php

class Forum_PostController extends MiddleController{
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
		public function executeDelete($idServ=null, $deleteAll=false){
			$manager=$this->managers->getManagerOf('post');
			if(empty($idServ))
				$id=$this->httpRequette->getData('id');
			else
				$id=$idServ;
				
			$record=$manager->get($id);
			
			if( !is_object($record) )
				$this->httpReponse->redirect404();
			
			if($record->getId_user_createur()!=$this->app->getUser()->getRecord()->getId() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
            
			$forumManager=$this->managers->getManagerOf('forum');
			$forumRecord=$forumManager->get( $record->getForumId() );
			if( $forumRecord->getAccreditation()>$this->app->getUser()->getAccreditation() ){
				$this->httpReponse->redirect404();
			}
			
			$userManager=new UserManager(PDOFactory::getMysqlConnexion());
			$createur=$userManager->get( $record->getId_user_createur() );
			if( $createur->getLogin()!=$this->app->getUser()->getLogin() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR ){
				$this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				if(empty($id))
					$this->httpReponse->redirect('/forum');
				else
					return false;
			}
			
			$topicManager=$this->managers->getManagerOf('topic');
			$topicRecord=$topicManager->get( $record->getTopicId() );
			
			if( (empty($idServ) && $topicRecord->getId_post_firstPost() ==$id) && !$deleteAll){
				$this->app->getUser()->setFlash($this->config['flash']['delete-first-not-allowed']);
				$this->httpReponse->redirect('/forum/topic/show-'.$topicRecord->getId());
			}
			
			//Cas : dernier post d'un topic	
			if($topicRecord->getId_post_lastPost()==$id){
				$req=new Requete();
				$req->addCol( new IntCol(['table'=>'post', 'name'=>'topicId', 'value'=>$topicRecord->getId()]) );
				$postRecords=$manager->getList($req);
				
				//$postRecords >=2, 
				if( count($postRecords)>1 )
					$topicRecord->setId_post_lastPost( $postRecords[ count($postRecords)-2 ]->getId() );
			}	
			//Cas : dernier post d'un forum	
			if($forumRecord->getId_post_lastPost()==$id){
				$req=new Requete();
				$req->addCol(new IntCol(['table'=>'post', 'name'=>'forumId', 'value'=>$forumRecord->getId()]));
				$postRecords=$manager->getList($req);
				
				//$postRecords >=2,
				if( count($postRecords)>1 )
					$forumRecord->setId_post_lastPost( $postRecords[ count($postRecords)-2 ]->getId() );
				else
					$forumRecord->setId_post_lastPost( -1 );
					
			}	
		
			$forumRecord->decrPosts();
			$topicRecord->decrPosts();
			$forumManager->save($forumRecord);
			$topicManager->save($topicRecord);
			
			$manager->delete($id);
			
			$this->app->getUser()->setFlash($this->config['flash']['successfull-delete']);
			if(empty($idServ)){
				$this->httpReponse->redirect('/forum/topic/show-'.$topicRecord->getId());
			}else{
				return true;
			}		
		}
		
		public function executeInsertIndex($id=null, $redirect=null){
            $cache=new Cache();
            
            //On construit le formulaire
            if($this->httpRequette->sessionExists('form')){
				$buff=$this->httpRequette->sessionData('form');
				$this->httpRequette->sessionUnset('form');
				
				/*if($retrun)
					return $buff;*/
				
                $this->page->addVar('form', $buff);
                if(empty($id))
					$id=$this->httpRequette->getData('id');
               
            }else{
				$formBuilder = new Forum_PostFormBuilder(null, $this->config);
				$formBuilder->buildInsert( $id , $redirect);
				$form=$formBuilder->getForm()->createView();
				
				if(isset($id))
					return $form;
				
                $this->page->addVar('form', $form);
            }
        }
       	
		public function executeInsert($dataServ=null){
			$manager=$this->managers->getManagerOf('post');
			
			$managerTopic=$this->managers->getManagerOf('topic');
			
			if(empty($dataServ)){
				$id=$this->httpRequette->getData('id');
				$topicRecord=$managerTopic->get($id);
			}else
				$topicRecord=$dataServ['topicRecord'];
			
			
			if( !is_object($topicRecord) ){
				empty($dataServ) ? $this->httpReponse->redirect404() : null;
				return false;
			}
			
			$managerForum=$this->managers->getManagerOf('forum');
			$forumRecord=$managerForum->get( $topicRecord->getForumId() );
			if( !is_object($forumRecord) || $forumRecord->getAccreditation()>$this->app->getUser()->getAccreditation() ||
$forumRecord->getPost_Statut()>$this->app->getUser()->getStatut() ){
				empty($dataServ) ? $this->httpReponse->redirect404() : null;
				return false;
			}

			//Si le script est appelé depuis une autre page script
			if(empty($dataServ)){
				$formBuilder = new Forum_PostFormBuilder(null,$this->config);
				$formBuilder->buildInsert();
				$formBuilder->setValues(array(
					'textBBCode'=>$this->httpRequette->postData('textBBCode')
				));
				$redirect=$this->httpRequette->postData('redirect');
				if(!$formBuilder->getForm()->isValid()){
					$this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
					if(empty($dataServ))
						if(!empty($redirect))
							$this->httpReponse->redirect($redirect);
						else
							$this->httpReponse->redirect('/forum/post/insert-index-'.$id);
					else
						return false;
				}
            
				$record=$formBuilder->getForm()->getEntity();
				
			}else{
				$record=new PostRecord();
				$record->setTextBBCode($dataServ['textBBCode']);
			}
			//echo String::parseBBCode($record->getTextBBCode());exit;	
			$record->setTextHtml(String::parseBBCode($record->getTextBBCode()));
            $record->setId_user_createur( $this->app->getUser()->getRecord()->getId() );
            $record->setDateCreation( date('Y-m-d H:i:s') );
            $record->setTopicId( $topicRecord->getId() );
            $record->setForumId( $forumRecord->getId() );           
            
            if(!$record->isValid()){
				if(empty($dataServ))
					if(!empty($redirect))
							$this->httpReponse->redirect($redirect);
						else
							$this->httpReponse->redirect('/forum/post/insert-index-'.$id);
				else
					return false;
            }
            
            $record->setId($manager->save($record));
            $i=$record->getId();   
            if(!is_numeric($i)){
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                if(empty($dataServ))
					if(!empty($redirect))
							$this->httpReponse->redirect($redirect);
						else
							$this->httpReponse->redirect('/forum/post/insert-index-'.$id);
				else
					return false;
            }
            //On enregistre le dernier post + incrementation du nombre
            $topicRecord->setId_post_lastPost($i);
            $topicRecord->incrPosts();
            $forumRecord->setId_post_lastPost($i);
            $forumRecord->incrPosts();
            
            $managerTopic->save($topicRecord);
            $managerForum->save($forumRecord);
            
            if(empty($dataServ)){
				$this->app->getUser()->setFlash( $this->config['flash']['successfull-record'] );   
				$this->httpRequette->sessionUnset('form');
				if(!empty($redirect))
						$this->httpReponse->redirect($redirect);
					else
						$this->httpReponse->redirect('/forum/index');
			}else
				return $record;
			}
		
		public function executeUpdateIndex($id=null, $redirect=null){        
            //On construit le formulaire
            if($this->httpRequette->sessionExists('form')){
				$buff=$this->httpRequette->sessionData('form');
				$this->httpRequette->sessionUnset('form');
                $this->page->addVar('form', $buff);
               
            }else{
				if(empty($id))
					$id=$this->httpRequette->getData('id');
				$formBuilder = new Forum_PostFormBuilder(null, $this->config);
				$formBuilder->buildUpdate( $id , $redirect);
				$form=$formBuilder->getForm()->createView();
				
                $this->page->addVar('form', $form);
            }
        }
       	
       	public function executeUpdate(){
			$manager=$this->managers->getManagerOf('post');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id); 
            if(!is_object($record))
                $this->httpReponse->redirect404();
            
            if($record->getId_user_createur()!=$this->app->getUser()->getRecord()->getId() && $this->app->getUser()->getStatut()<User::STATUT_MODERATEUR){
                $this->app->getUser()->setFlash($this->config['flash']['access-denied']);
				$this->httpReponse->redirect('index');
            }
            
            $forumManager=$this->managers->getManagerOf('forum');
			$forumRecord=$forumManager->get( $record->getForumId() );
			if( $forumRecord->getAccreditation()>$this->app->getUser()->getAccreditation() ){
				$this->httpReponse->redirect404();
			}
            
            $formBuilder = new Forum_PostFormBuilder(null,$this->config);
            $formBuilder->buildInsert($id);
            $formBuilder->setValues(array(
				'textBBCode'=>$this->httpRequette->postData('textBBCode')
            ));

            if(!$formBuilder->getForm()->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('/forum/post/insert-index-'.$id);
            }
            
            $newRecord=$formBuilder->getForm()->getEntity();
            $record->setTextBBCode(html_entity_decode($newRecord->getTextBBCode()));
            $record->setTextHtml(String::parseBBCode($record->getTextBBCode()));
            if( $manager->save($record) ){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-record']); 
				$this->httpRequette->sessionUnset('form'); 
				$this->httpReponse->redirect('/forum/topic/show-'.$record->getTopicId());
			}else{
				$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				$this->httpReponse->redirect('/forum/post/update-index-'.$id);
			}	
            
		}
}

