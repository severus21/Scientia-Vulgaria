<?php
/*
 * name: Forum_PostNavBuilder
 * @description :  
 */ 
    
class Forum_PostNavBuilder extends NavBuilder{
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
		
		public function buildShowHeader($id, $topicRecord){
			$this->nav=new Nav();
			$this->nav->setClass('headerShowPost');	
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['headerShowPost-update'],
														'link'=>'/forum/post/update-index-'.$id]));
														
			if($topicRecord->getId_post_firstPost()!=$id){	
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['headerShowPost-delete'],
															'link'=>'/forum/post/delete-'.$id]));
			}												
            return $this->nav->build();
		}
}	

