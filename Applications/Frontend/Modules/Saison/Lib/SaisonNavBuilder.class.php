<?php
/*
 * name: SaisonNavBuilder
 * @description :  
 */ 
    
class SaisonNavBuilder extends NavBuilder{
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
			$this->nav->setId('topSaisonInsertNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSaisonNav-index'],
														'link'=>'../serie/show-'.$httpRequete->getData('id')]));
			
            return $this->nav->build();
		}
		
		public function buildUpdateTop(){
			$httpRequete=new HttpRequette();
			
			$this->nav=new Nav();
			$this->nav->setId('topSaisonUpdateNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSaisonNav-index'],
														'link'=>'../serie/show-'.$httpRequete->getData('id')]));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSaisonNav-insert'],
														'link'=>'insert-index-'.$httpRequete->getData('id')]));
			
            return $this->nav->build();
		}
		public function buildShowTop(User $user, Saison $obj){
			$this->nav=new Nav();
			$this->nav->setId('topSaisonShowNav');
			$this->nav->setClass('secondaryTopNav');
													
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSaisonNav-index'],
														'link'=>'../serie/show-'.$obj->getIdSerie()]));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSaisonNav-insert'],
														'link'=>'insert-index-'.$obj->getIdSerie()]));
			
            if($obj->getLogin()==$user->getLogin() || $user->getStatut()>=User::STATUT_MODERATEUR){
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSaisonNav-update'],
														'link'=>'update-index-'.$obj->getRecord()->getId()]));
														
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSaisonNav-delete'],
														'link'=>'delete-'.$obj->getRecord()->getId()]));
			}
			
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSaisonNav-insert-episode'],
														'link'=>'../episode/insert-index-'.$obj->getRecord()->getId()]));
														
            return $this->nav->build();
		}
}
