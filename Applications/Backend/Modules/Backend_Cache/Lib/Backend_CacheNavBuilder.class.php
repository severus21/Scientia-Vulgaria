<?php
/*
 * name: Backend_CacheNavBuilder
 * @description :  
 */ 
    
class Backend_CacheNavBuilder extends NavBuilder{
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
		public function buildIndexTop(){
			$this->nav=new Nav();
			$this->nav->setId('topBackend_CacheIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topBackend_CacheNav-list-hard'],
														'link'=>'list']));			
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topBackend_CacheNav-drop-men'],
														'link'=>'drop-mem']));
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topBackend_CacheNav-drop-hard'],
														'link'=>'drop']));
														
            return $this->nav->build();
		}
}
