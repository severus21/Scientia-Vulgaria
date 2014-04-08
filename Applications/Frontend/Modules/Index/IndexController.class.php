<?php
/*
	Auteur : Oceane21
	Version : 1.0.0
	Projet : Scientia Vulgaria Project
*/

class IndexController extends BackController{
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
		public function buildNews(){
			$requete=new Requete();
			$requete->addCol( new IntCol(['name'=>'statut', 'comparisonOperator'=>'<=', 'value'=>$this->app->getUser()->getStatut()]));
			$requete->addCol( new IntCol(['name'=>'accreditation', 'comparisonOperator'=>'<=', 'value'=>$this->app->getUser()->getAccreditation()]));
			$requete->addCol( new StringCol(['name'=>'app', 'comparisonOperator'=>'=', 'value'=>'frontend', 'strict'=>true]));
			
			$objFac=new ObjectFactory();
			$news=$objFac->buildObjectFromRequete('news', $requete);
			$this->page->addVar('news', $news);
		}
		public function executeIndex(){
			$this->page->addVar('title', 'Accueil');
			$this->buildNews();
		}
}
