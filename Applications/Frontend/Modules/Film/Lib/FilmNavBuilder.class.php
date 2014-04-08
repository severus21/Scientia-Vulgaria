<?php
/*
 * name: FilmNavBuilder
 * @description :  
 */ 
    
class FilmNavBuilder extends NavBuilder{
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
			$this->nav->setId('topFilmIndexNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topFilmNav-insert'],
														'link'=>'insert-index']));
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));			
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topFilmNav-gestion'],
														'link'=>'gestion']));
														
            return $this->nav->build();
		}
		public function buildGestionTop($formSearch){
			$this->nav=new Nav();
			$this->nav->setId('topFilmGestionNav');
			$this->nav->setClass('secondaryTopNav');	
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topFilmNav-insert'],
														'link'=>'insert-index']));
			$this->nav->addNavElement(new NavElement(['class'=>'form',
														'label'=>$formSearch]));		
			$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topFilmNav-index'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		
		public function buildInsertTop(){
			$this->nav=new Nav();
			$this->nav->setId('topFilmInsertNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topFilmNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topFilmNav-gestion'],
														'link'=>'gestion']));
			
            return $this->nav->build();
		}
		
		public function buildUpdateTop(){
			$this->nav=new Nav();
			$this->nav->setId('topFilmUpdateNav');	
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topFilmNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topFilmNav-insert'],
														'link'=>'insert-index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topFilmNav-gestion'],
														'link'=>'index']));
			
            return $this->nav->build();
		}
		public function buildShowTop(User $user, Film $ebook){
			$this->nav=new Nav();
			$this->nav->setId('topFilmShowNav');
			$this->nav->setClass('secondaryTopNav');
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topFilmNav-index'],
														'link'=>'index']));
														
			$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topFilmNav-insert'],
														'link'=>'insert-index']));
			
            if($ebook->getLogin()==$user->getLogin() || $user->getStatut()>=User::STATUT_MODERATEUR){
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topFilmNav-update'],
														'link'=>'update-index-'.$ebook->getRecord()->getId()]));
														
				$this->nav->addNavElement(new NavElement(['label'=>$this->config['nav']['topFilmNav-delete'],
														'link'=>'delete-'.$ebook->getRecord()->getId()]));
			}
            return $this->nav->build();
		}
}
