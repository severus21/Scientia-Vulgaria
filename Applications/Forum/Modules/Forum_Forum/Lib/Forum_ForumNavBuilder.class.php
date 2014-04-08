<?php
/*
 * name: Forum_ForumNavBuilder
 * @description :  
 */ 
    
class Forum_ForumNavBuilder extends NavBuilder{
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
		public function buildIndexTop($user, $forumRecord, $formSearch){
			$this->nav=new Nav();
			$this->nav->setId('topForumTopicIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			
			$this->nav->addNavElement(new NavElement(['class'=>'left',
															'label'=>$this->config['nav']['topForumNav-index'],
															'link'=>'/forum/']));
			
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));
			
			if($user->getStatut()>=$forumRecord->getPost_statut()){
				$this->nav->addNavElement(new NavElement(['class'=>'left',
															'label'=>$this->config['nav']['topForumNav-insert'],
															'link'=>'/forum/topic/insert-index-'.$forumRecord->getId()]));
			}
														
            return $this->nav->build();
		}
}
