<?php
/*
 * name: BackendNavBuilder
 * @description :  
 */ 
    
class BackendNavBuilder extends NavBuilder{
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
		public function buildTop(User $user){
			$this->nav=new Nav();
			$this->nav->setId('topMainNav');
			$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topMainMenu-accueil'],
														'link'=>'/']));
			
            //si l'utilisateur est connecté
            if($user->getLogin()!=''){
				$this->nav->addNavElement(new NavElement(['class'=>'left',
														'label'=>$this->config['nav']['topMainMenu-support'],
														'link'=>'/support/rapport']));
														
				$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topMainMenu-deconnexion'],
														'link'=>'/user/deconnexion']));
														
				$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topMainMenu-compte'],
														'link'=>'/user/gestion']));
			}else{
				$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topMainMenu-inscription'],
														'link'=>'/user/insert-index']));
														
				$this->nav->addNavElement(new NavElement(['class'=>'right',
														'label'=>$this->config['nav']['topMainMenu-connexion'],
														'link'=>'/user/connexion']));
            }
            return $this->nav->build();
		}
		
		public function buildLeft(User $user){
			$this->nav=new Nav();
			$this->nav->setId('leftMainNav');
			$navElements=array();//Tableau  1 dim consituer des navElements des categories eux meme disposant de leur enfant dans module et tc
			//On construit les references
			$manager=new ReferenceManager(PDOFactory::getMysqlConnexion());
			$requete=new Requete();
			$requete->addCol(new IntCol(['name'=>'statut', 'value'=>$user->getStatut(), 'comparisonOperator'=>'<=', 'logicalOperator'=>'&&']));
			$requete->addCol(new IntCol(['name'=>'accreditation', 'value'=>$user->getAccreditation(), 'comparisonOperator'=>'<=', 'logicalOperator'=>'&&']));
			$requete->setOrder(['categorie_'.$_SESSION['lang'], 'module_'.$_SESSION['lang'], 'label_'.$_SESSION['lang']]);
			
			$records=$manager->getList($requete);
			$objectFactory=new ObjectFactory();
			$refs=$objectFactory->buildMultiObject($records);
			//On parcour les references et on construit le nav
            if(!empty($refs)){
				$modules=array();
                foreach($refs as $ref){
					if(!array_key_exists($ref->getCategorie(),$navElements)){
						$navElements[$ref->getCategorie()]=new NavElement(['label'=>$ref->getCategorie()]);
						$modules=array();//on reset module
					}
					$primaryNavElement=$navElements[$ref->getCategorie()];
					
					if(!in_array($ref->getModule(),$modules)){
						$secondaryNavElement=new NavElement(['label'=>$ref->getModule()]);
						$primaryNavElement->addChild($secondaryNavElement);
						$modules[]=$ref->getModule();
					}
					empty($secondaryNavElement) ? $secondaryNavElement=$primaryNavElement->getChildByLabel($ref->getModule()) :null;

					$tertiaryNavElement=new NavElement(['label'=>$ref->getLabel(), 'link'=>$ref->getLink()]);
					$secondaryNavElement->addChild($tertiaryNavElement);   
				}
            }
           // echo print_r($navElements);
            foreach($navElements as $navElement)
				$this->nav->addNavElement($navElement);
            return $this->nav->build();
		}
}
