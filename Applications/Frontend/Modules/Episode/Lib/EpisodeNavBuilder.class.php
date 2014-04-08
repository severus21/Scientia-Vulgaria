<?php
/*
 * name: EpisodeNavBuilder
 * @description :  
 */ 
    
class EpisodeNavBuilder extends NavBuilder{
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
		
		public function buildInsertTop(){
			$httpRequete=new HttpRequette();
			
			$this->nav=new Nav();
			$this->nav->setId('topEpisodeInsertNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEpisodeNav-index'],
														'link'=>'../saison/show-'.$httpRequete->getData('id')]));
			
            return $this->nav->build();
		}
		
		public function buildUpdateTop(){
			$httpRequete=new HttpRequette();
			
			$this->nav=new Nav();
			$this->nav->setId('topEpisodeUpdateNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEpisodeNav-index'],
														'link'=>'../saison/show-'.$httpRequete->getData('id')]));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEpisodeNav-insert'],
														'link'=>'insert-index-'.$httpRequete->getData('id')]));
			
            return $this->nav->build();
		}
		public function buildShowTop(User $user, Episode $obj){
			$this->nav=new Nav();
			$this->nav->setId('topEpisodeShowNav');
			$this->nav->setClass('secondaryTopNav');
													
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEpisodeNav-index'],
														'link'=>'../saison/show-'.$obj->getIdSaison()]));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEpisodeNav-insert'],
														'link'=>'insert-index-'.$obj->getIdSaison()]));
			
            if($obj->getLogin()==$user->getLogin() || $user->getStatut()>=User::STATUT_MODERATEUR){
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEpisodeNav-update'],
														'link'=>'update-index-'.$obj->getRecord()->getId()]));
														
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEpisodeNav-delete'],
														'link'=>'delete-'.$obj->getRecord()->getId()]));
			}											
            return $this->nav->build();
		}
}
