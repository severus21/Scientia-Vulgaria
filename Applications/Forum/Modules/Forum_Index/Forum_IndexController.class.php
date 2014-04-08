<?php

class Forum_IndexController extends BackController{
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
		public function executeIndex(){
			$this->page->addVar('title', 'Forum');
			
			$manager=$this->managers->getManagerOf('forum');
			$requete=new Requete;
			
			//On prépare la requête
			$requete->addOrders( ["forum.ordre", "forumcategorie.nom_{$_SESSION['lang']}", "forum.name"] );
			$requete->addJointure( new Jointure(['tableJ'=>'forumcategorie', 'colO'=>'id_forumcategorie_categorie', 'colJ'=>'id']) );
			$requete->addCol(
				new IntCol([
					'name'=>'accreditation',
					'value'=>$this->app->getUser()->getAccreditation(),
					'comparisonOperator'=>'<=',
					'logicalOperator'=>'AND'
				]) 
			);
			
			//$records=$manager->getList($requete);
			$objFact=new ObjectFactory('forum');
			$forums=$objFact->buildObjectFromRequete('forum', $requete);
			$this->page->addVar('forums', $forums);
			$this->page->addVar('config', $this->config);
		}
}
