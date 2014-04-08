<?php
/*
 * name: NewsNavBuilder
 * @description :  
 */ 
    
class Backend_NewsNavBuilder extends NavBuilder{
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
			$this->nav->setId('topNewsIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topNewsNav-insert'],
														'link'=>'insert-index']));
			/*$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));*/
														
            return $this->nav->build();
		}

		
		public function buildInsertTop(){
			$this->nav=new Nav();
			$this->nav->setId('topNewsInsertNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topNewsNav-index'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		
		public function buildUpdateTop(){
			$this->nav=new Nav();
			$this->nav->setId('topNewsUpdateNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topNewsNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topNewsNav-insert'],
														'link'=>'insert-index']));
			
            return $this->nav->build();
		}
}
