<?php
/*
 * name: Forum_TopicNavBuilder
 * @description :  
 */ 
    
class Forum_TopicNavBuilder extends NavBuilder{
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
		public function buildShowTop($user, $topic, $forumRecord){
			$this->nav=new Nav();
			$this->nav->setId('topForumTopicIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			
			$this->nav->addNavElement(new NavElement(['class'=>'left',
															'label'=>$this->config['nav']['topTopicNav-index'],
															'link'=>'/forum/forum/index-'.$forumRecord->getId()]));
			
			if($user->getLogin()==$topic->getCreateur()->getLogin() || $user->getStatut()>=User::STATUT_MODERATEUR){
				$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topTopicNav-update'],
														'link'=>'update-index-'.$topic->getRecord()->getId()]));
				$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topTopicNav-delete'],
														'link'=>'delete-'.$topic->getRecord()->getId()]));
			}
														
            return $this->nav->build();
		}
}
