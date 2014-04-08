<?php
/*
 * name: UserNavBuilder
 * @description :  
 */ 
    
class UserNavBuilder extends NavBuilder{
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
		public function buildGestionTop(){
			$this->nav=new Nav();
			$this->nav->setId('topUserGestionNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topUserNav-profile'],
														'link'=>'update-index',
														'childs'=>array(
				new NavElement(['label'=>$this->config['nav']['topUserNav-update-modify'],
								'link'=>'update-index']))]));
            return $this->nav->build();
		}
}
