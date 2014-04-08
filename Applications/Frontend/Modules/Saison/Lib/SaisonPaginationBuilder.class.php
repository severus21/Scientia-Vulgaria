<?php
/*
 * name: SaisonPaginationBuilder
 * @description : 
 */ 
 
class SaisonPaginationBuilder extends PaginationBuilder{
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
		public function buildShow($idSerie){
            //on prepare la requete
            $requete=new Requete();
            $requete->addCol( new IntCol( ['name'=>'idSerie', 'comparisonOperator'=>'=', 'value'=>$idSerie] ));
			$requete->addOrder('scientiavulgaria.saison.n');
            
            //On creer la pagination
            $pagination=new MosaiquePagination(array(
                'default'=>$this->config['msg']['default-search-empty'],
                'nbrObjectsPerPage'=>$this->config['define']['mosaique-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['mosaique-nbrSelectablePages'],   
                'requete'=>$requete,
                'asc'=>'asc',
                'classObject'=>'saison',
                'page'=>1,
                'header'=>false));                                   

            $pagination->setTesselle(new Tesselle(array(
				'elements'=>[new TitleTesselleElement(['class'=>'n', 'prefix'=>$this->config['msg']['numero'], 'functions'=>['getN'], 'link'=>true]),
							new ImgTesselleElement(['class'=>'miniature', 'functions'=>['getMiniature','getMiniature','generateHtmlPath'], 'link'=>true])
				],
				'class'=>'saison_tesselle'
            )));                                                                                                       
            $this->setPagination($pagination);           
        }
        
}
