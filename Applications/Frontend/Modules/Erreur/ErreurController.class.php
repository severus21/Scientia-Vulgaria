<?php
/*
 * name: ErreurController
 * @description : 
 */

class ErreurController extends MiddleController{ 
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
		public function execute404(){
			$this->page->addVar('msg', $this->config['view']['E404']);
		}
		
		public function execute507(){
			$log=new Log();
			$log->log('Erreurs', 'serveur', '507 : Insufficient storage');
			$this->page->addVar('msg', $this->config['view']['E507']);
		}
    
}
