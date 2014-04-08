<?php

class Forum_TopicController extends MiddleController{
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
		public function executeDelete(){
			$manager=$this->managers->getManagerOf('topic');
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
            
            //On supprime les posts
            $requete=new Requete();
            $requete->addCol( new IntCol(['name'=>'topicId', 'value'=>$record->getId()]));
            $managerPost=$this->managers->getManagerOf('post');
            $postRecords=$managerPost->getList($requete);
            
            $postController=new Forum_PostController($this->app, null, 'delete');
            for($a=0; $a<count($postRecords) ; $a++)
				$postController->executeDelete( $postRecords[$a]->getId(), true );
            
			//On met à jours le nbr de topic
			$forumRecord->setTopics( $forumRecord->getTopics()-1 );
			$forumRecord->decrPosts();
			$forumManager->save($forumRecord);
			
			//On supprime le topic 
			$manager->delete($record->getId());
            
            $this->app->getUser()->setFlash( $this->config['flash']['delete-sucessful']);
            $this->httpReponse->redirect( '/forum/forum/index-'.$forumRecord->getId() );
		}
			
		public function executeInsertIndex(){
            $id=$this->httpRequette->getData('id');
            $forumManager=$this->managers->getManagerOf('forum');
			$forumRecord=$forumManager->get( $id );
			if( $forumRecord->getAccreditation()>$this->app->getUser()->getAccreditation() || $forumRecord->getView_statut()>$this->app->getUser()->getStatut() ){
				$this->httpReponse->redirect404();
			}
            
            //On construit le formulaire
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
				$formBuilder = new Forum_TopicFormBuilder(null, $this->config);
				$formBuilder->buildInsert( $this->httpRequette->getData('id') );
				$form=$formBuilder->getForm()->createView();
                $this->page->addVar('form', $form);
            }
        }
		
		public function executeInsert(){
			$manager=$this->managers->getManagerOf('topic');
			
			$id=$this->httpRequette->getData('id');
			
			$managerForum=$this->managers->getManagerOf('forum');
			$forumRecord=$managerForum->get( $id );
			if( !is_object($forumRecord) || $forumRecord->getAccreditation()>$this->app->getUser()->getAccreditation() ||
$forumRecord->getPost_statut()>$this->app->getUser()->getStatut() ){
				$this->httpReponse->redirect404();
			}
			
			$formBuilder = new Forum_TopicFormBuilder(null,$this->config);
            $formBuilder->buildInsert($id);
            $formBuilder->setValues(array(
				'titre'=>$this->httpRequette->postData('titre'),
				'langue'=>$this->httpRequette->postData('langue'),
                'text'=>$this->httpRequette->postData('text')
            ));

            if(!$formBuilder->getForm()->isValid()){
                
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('/forum/topic/insert-index-'.$id);
            }
            
            $record=$formBuilder->getForm()->getEntity();
            $record->setId_user_createur( $this->app->getUser()->getRecord()->getId() );
            $record->setForumId( $id );
            $record->setVues(0);
            $record->setPosts(0);
 
            if(!$record->isValid()){
                $this->httpReponse->redirect('/forum/topic/insert-index-'.$id);
            }
            $record->setDateCreation( date('Y-m-d H:i:s') );
            $record->setId($manager->save($record));

            //on ajoute le premier et dernier posts
            $postController=new Forum_PostController($this->app, null, 'insert');
            $postRecord=$postController->executeInsert(['topicRecord'=>$record, 'textBBCode'=>$this->httpRequette->postData('text')]);
			if(!$postRecord){
				$this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
				$this->httpReponse->redirect('insert-index-'.$id);
			}
           
            $record->setId_post_firstPost( $postRecord->getId() );
			$record->setId_post_lastPost( $record->getId_post_firstPost() );
            $manager->save($record);     
            
            $i=$record->getId();   
            if(!is_numeric($i)){
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('insert-index-'.$id);
            }
            //On met à jour le forum 
            
            $forumRecord->incrTopics();
            $forumRecord->incrPosts();
            $managerForum->save($forumRecord);
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);   
            $this->httpReponse->redirect('/forum/forum/index-'.$id);
		}
		
		public function executeShow($flagPag = true, $flagNav = true){
			$manager=$this->managers->getManagerOf('topic');
			$postManager=$this->managers->getManagerOf('post');
            $id=$this->httpRequette->getData('id');
            
            $record=$manager->get($id);
            if(!is_object($record) || $record->getPosts()<1){
                $this->httpReponse->redirect404();
            }
            
            $forumManager=$this->managers->getManagerOf('forum');
			$forumRecord=$forumManager->get( $record->getForumId() );
			if( $forumRecord->getAccreditation()>$this->app->getUser()->getAccreditation() || $forumRecord->getView_statut()>$this->app->getUser()->getStatut() ){
				$this->httpReponse->redirect404();
			}
			
            $record->incrVues();
            $manager->save($record);
            
            $objFac=new ObjectFactory('forum');
            $topic=$objFac->buildObject($record);
            
            
			/*
			 * 
			 * Attention utiliser la classe pagination pour l'affichage des posts
			 * 
			 * 
			 */
			$cache=new Cache();
            $paginationBuilder=new Forum_TopicPaginationBuilder(null, $this->config);
			$paginationBuilder->buildShowMosaique($record, $this->app->getUser());
			$page=$paginationBuilder->getPagination()->getPage();
			$cols=$paginationBuilder->getPagination()->getRequete()->getCols();
			$order=$paginationBuilder->getPagination()->getRequete()->getOrder();
			$o=$this->httpRequette->getExists('order');
			$a=$this->httpRequette->getExists('asc');
			/*if($page>=1 && $page<=2 && empty($cols) && empty($order) && !$o && !$a){
				$content=$cache->getCache('Frontend', $this->getModule(), 'indexPagination'.substr($echange,10).$page, (function($arg){
					return $arg['paginationBuilder']->getPagination()->build();
				}), array('paginationBuilder'=>$paginationBuilder));
			}else{
				$content=$paginationBuilder->getPagination()->build();
			}*/
            $content=$paginationBuilder->getPagination()->build(); 
            
            $this->page->addVar('content', $content);
            
            $postController=new Forum_PostController($this->app,'Forum_Post', 'insertIndex');
            $this->page->addVar('form', $postController->executeInsertIndex($record->getId(), '/forum/topic/show-'.$record->getId())); 
		
			$navBuilder=new Forum_TopicNavBuilder(null, $this->config);
			$this->page->addVar('secondaryTopNav', $navBuilder->buildShowTop($this->app->getUser(), $topic, $forumRecord) );
		}

		public function executeUpdateIndex(){
			$manager=$this->managers->getManagerOf('topic');
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
            
            //On construit le formulaire
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
				$formBuilder = new Forum_TopicFormBuilder(null, $this->config);
				$formBuilder->buildUpdate( $this->httpRequette->getData('id') );
				$form=$formBuilder->getForm()->createView();
                $this->page->addVar('form', $form);
            }
        }

		public function executeUpdate(){
			$manager=$this->managers->getManagerOf('topic');
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
            
            $formBuilder = new Forum_TopicFormBuilder(null,$this->config);
            $formBuilder->buildInsert($id);
            $formBuilder->setValues(array(
				'titre'=>$this->httpRequette->postData('titre'),
				'langue'=>$this->httpRequette->postData('langue')
            ));

            if(!$formBuilder->getForm()->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('/forum/topic/insert-index-'.$id);
            }
            
            $newRecord=$formBuilder->getForm()->getEntity();
            $record->setTitre( $newRecord->getTitre() );
            $record->setLangue( $newRecord->getLangue() );
            
            //On sauvegarde l'objet ainsi modifié
			if( $manager->save($record) ){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);
				$this->httpRequette->sessionUnset('form');  
				$this->httpReponse->redirect('/forum/topic/show-'.$id);
			}else{
				$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				$this->httpReponse->redirect('update-index-'.$id);
			}	
            
		}

}
