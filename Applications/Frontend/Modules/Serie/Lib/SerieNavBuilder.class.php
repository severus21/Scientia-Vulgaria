<?php
/*
 * name: SerieNavBuilder
 * @description :  
 */ 
    
class SerieNavBuilder extends NavBuilder{
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
			$this->nav->setId('topSerieIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topSerieNav-insert'],
														'link'=>'insert-index']));
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));			
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topSerieNav-gestion'],
														'link'=>'gestion']));
														
            return $this->nav->build();
		}
		public function buildGestionTop($formSearch){
			$this->nav=new Nav();
			$this->nav->setId('topSerieGestionNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topSerieNav-insert'],
														'link'=>'insert-index']));
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));		
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topSerieNav-index'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		
		public function buildInsertTop(){
			$this->nav=new Nav();
			$this->nav->setId('topSerieInsertNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSerieNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSerieNav-gestion'],
														'link'=>'gestion']));
			
            return $this->nav->build();
		}
		
		public function buildUpdateTop(){
			$this->nav=new Nav();
			$this->nav->setId('topSerieUpdateNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSerieNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSerieNav-insert'],
														'link'=>'insert-index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSerieNav-gestion'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		public function buildShowTop(User $user, Serie $obj){
			$this->nav=new Nav();
			$this->nav->setId('topSerieShowNav');
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSerieNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSerieNav-insert'],
														'link'=>'insert-index']));
			
            if($obj->getLogin()==$user->getLogin() || $user->getStatut()>=User::STATUT_MODERATEUR){
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSerieNav-update'],
														'link'=>'update-index-'.$obj->getRecord()->getId()]));
														
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSerieNav-delete'],
														'link'=>'delete-'.$obj->getRecord()->getId()]));
			}
			
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topSerieNav-insert-saison'],
														'link'=>'../saison/insert-index-'.$obj->getRecord()->getId()]));
														
            return $this->nav->build();
		}
}
