<?php
/*
 * name: EbookNavBuilder
 * @description :  
 */ 
    
class EbookNavBuilder extends NavBuilder{
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
			$this->nav->setId('topEbookIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topEbookNav-insert'],
														'link'=>'insert-index']));
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));			
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topEbookNav-gestion'],
														'link'=>'gestion']));
														
            return $this->nav->build();
		}
		public function buildGestionTop($formSearch){
			$this->nav=new Nav();
			$this->nav->setId('topEbookGestionNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topEbookNav-insert'],
														'link'=>'insert-index']));
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));		
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topEbookNav-index'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		
		public function buildInsertTop(){
			$this->nav=new Nav();
			$this->nav->setId('topEbookInsertNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEbookNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEbookNav-gestion'],
														'link'=>'gestion']));
			
            return $this->nav->build();
		}
		
		public function buildUpdateTop(){
			$this->nav=new Nav();
			$this->nav->setId('topEbookUpdateNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEbookNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEbookNav-insert'],
														'link'=>'insert-index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEbookNav-gestion'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		public function buildShowTop(User $user, Ebook $ebook){
			$this->nav=new Nav();
			$this->nav->setId('topEbookShowNav');
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEbookNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEbookNav-insert'],
														'link'=>'insert-index']));
			
            if($ebook->getLogin()==$user->getLogin() || $user->getStatut()>=User::STATUT_MODERATEUR){
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEbookNav-update'],
														'link'=>'update-index-'.$ebook->getRecord()->getId()]));
														
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topEbookNav-delete'],
														'link'=>'delete-'.$ebook->getRecord()->getId()]));
			}
            return $this->nav->build();
		}
}
