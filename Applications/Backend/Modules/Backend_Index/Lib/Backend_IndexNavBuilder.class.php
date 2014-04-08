<?php
/*
 * name: Backend_AuthNavBuilder
 * @description :  
 */ 
    
class Backend_AuthNavBuilder extends NavBuilder{
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
		public function buildIndexTop($formSearch){
			$this->nav=new Nav();
			$this->nav->setId('topBackend_AuthIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topBackend_AuthNav-insert'],
														'link'=>'insert-index']));
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));			
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topBackend_AuthNav-gestion'],
														'link'=>'gestion']));
														
            return $this->nav->build();
		}
		public function buildGestionTop($formSearch){
			$this->nav=new Nav();
			$this->nav->setId('topBackend_AuthGestionNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topBackend_AuthNav-insert'],
														'link'=>'insert-index']));
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));		
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topBackend_AuthNav-index'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		
		public function buildInsertTop(){
			$this->nav=new Nav();
			$this->nav->setId('topBackend_AuthInsertNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topBackend_AuthNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topBackend_AuthNav-gestion'],
														'link'=>'gestion']));
			
            return $this->nav->build();
		}
		
		public function buildUpdateTop(){
			$this->nav=new Nav();
			$this->nav->setId('topBackend_AuthUpdateNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topBackend_AuthNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topBackend_AuthNav-insert'],
														'link'=>'insert-index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topBackend_AuthNav-gestion'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		public function buildShowTop(User $user, Backend_Auth $ebook){
			$this->nav=new Nav();
			$this->nav->setId('topBackend_AuthShowNav');
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topBackend_AuthNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topBackend_AuthNav-insert'],
														'link'=>'insert-index']));
			
            if($ebook->getLogin()==$user->getLogin() || $user->getStatut()>=User::STATUT_MODERATEUR){
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topBackend_AuthNav-update'],
														'link'=>'update-index-'.$ebook->getRecord()->getId()]));
														
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topBackend_AuthNav-delete'],
														'link'=>'delete-'.$ebook->getRecord()->getId()]));
			}
            return $this->nav->build();
		}
}
