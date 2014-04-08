<?php
/*
 * name: Backend_CategorieNavBuilder
 * @description :  
 */ 
    
class Backend_CategorieNavBuilder extends NavBuilder{
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
			$this->nav->setId('topCategorieIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topCategorieNav-insert'],
														'link'=>'insert-index']));
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));
														
            return $this->nav->build();
		}

		
		public function buildInsertTop(){
			$this->nav=new Nav();
			$this->nav->setId('topCategorieInsertNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topCategorieNav-index'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		
		public function buildUpdateTop(){
			$this->nav=new Nav();
			$this->nav->setId('topCategorieUpdateNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topCategorieNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topCategorieNav-insert'],
														'link'=>'insert-index']));
			
            return $this->nav->build();
		}
}
