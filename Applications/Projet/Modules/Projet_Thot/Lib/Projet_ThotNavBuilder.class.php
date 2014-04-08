<?php
/*
 * name: Projet_ThotNavBuilder
 * @description :  
 */ 
    
class Projet_ThotNavBuilder extends NavBuilder{
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
			$this->nav->setId('topProjet_ThotIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['list'],
														'link'=>'list']));	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['sources'],
														'link'=>'sources']));	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['doc'],
														'link'=>'doc']));	
														
            return $this->nav->build();
		}
	
		public function buildInsertExempleTop(){
			$this->nav=new Nav();
			$this->nav->setId('topProjet_ThotIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['index'],
														'link'=>'index']));	
														
            return $this->nav->build();
		}
		
		public function buildInsertDatabaseTop(){
			$this->nav=new Nav();
			$this->nav->setId('topProjet_ThotIndexNav');
			$this->nav->setClass('secondaryTopNav');	
				
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['index'],
														'link'=>'index']));	
														
            return $this->nav->build();
		}
		
		public function buildUpdateExempleTop(){
			$this->nav=new Nav();
			$this->nav->setId('topProjet_ThotIndexNav');
			$this->nav->setClass('secondaryTopNav');	
												
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['index'],
														'link'=>'index']));	
														
            return $this->nav->build();
		}
	
		public function buildListTop($formSearch){
			$this->nav=new Nav();
			$this->nav->setId('topProjet_ThotIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['index'],
														'link'=>'index']));	
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));
					
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['insert-database'],
														'link'=>'insert-index-database']));
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['insert-exemple'],
														'link'=>'insert-index-exemple']));
			
            return $this->nav->build();
		}
}
