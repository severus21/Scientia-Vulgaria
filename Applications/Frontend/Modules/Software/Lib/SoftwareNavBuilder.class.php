<?php
/*
 * name: SoftwareNavBuilder
 * @description :  
 */ 
    
class SoftwareNavBuilder extends NavBuilder{
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
			$this->nav->setId('topSoftwareIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topSoftwareNav-insert'],
														'link'=>'insert-index']));
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));			
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topSoftwareNav-gestion'],
														'link'=>'gestion']));
														
            return $this->nav->build();
		}
		public function buildGestionTop($formSearch){
			$this->nav=new Nav();
			$this->nav->setId('topSoftwareGestionNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topSoftwareNav-insert'],
														'link'=>'insert-index']));
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));		
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topSoftwareNav-index'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		
		public function buildInsertTop(){
			$this->nav=new Nav();
			$this->nav->setId('topSoftwareInsertNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSoftwareNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSoftwareNav-gestion'],
														'link'=>'gestion']));
			
            return $this->nav->build();
		}
		
		public function buildUpdateTop(){
			$this->nav=new Nav();
			$this->nav->setId('topSoftwareUpdateNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSoftwareNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSoftwareNav-insert'],
														'link'=>'insert-index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSoftwareNav-gestion'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		public function buildShowTop(User $user, Software $ebook){
			$this->nav=new Nav();
			$this->nav->setId('topSoftwareShowNav');
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSoftwareNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSoftwareNav-insert'],
														'link'=>'insert-index']));
			
            if($ebook->getLogin()==$user->getLogin() || $user->getStatut()>=User::STATUT_MODERATEUR){
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSoftwareNav-update'],
														'link'=>'update-index-'.$ebook->getRecord()->getId()]));
														
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSoftwareNav-delete'],
														'link'=>'delete-'.$ebook->getRecord()->getId()]));
			}
            return $this->nav->build();
		}
}
