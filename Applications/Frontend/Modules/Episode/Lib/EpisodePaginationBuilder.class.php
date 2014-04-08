<?php
/*
 * name: EpisodePaginationBuilder
 * @description : 
 */ 
 
class EpisodePaginationBuilder extends PaginationBuilder{
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
		public function buildShow($idSaison){
            //on prepare la requete
            $requete=new Requete();
            $requete->addCol( new IntCol( ['name'=>'idSaison', 'comparisonOperator'=>'=', 'value'=>$idSaison] ));
			$requete->addOrder('scientiavulgaria.episode.n');
            
            //On creer la pagination
            $pagination=new MosaiquePagination(array(
				'header'=>false,
                'nbrObjectsPerPage'=>$this->config['define']['mosaique-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['mosaique-nbrSelectablePages'],   
                'requete'=>$requete,
                'asc'=>'asc',
                'classObject'=>'episode',
                'page'=>1,
                'header'=>false));                                   

            $pagination->setTesselle(new Tesselle(array(
				'elements'=>[
							new TitleTesselleElement(['class'=>'n', 'prefix'=>$this->config['msg']['numero'], 'functions'=>['getN'], 'link'=>true])
							//,
						//	new TitleTesselleElement(['class'=>'nom', 'functions'=>['getNom'], 'link'=>true]),
						//	new TitleTesselleElement(['class'=>'subtitle', 'functions'=>['getSubtitle'], 'link'=>true])
				],
				'class'=>'episode_tesselle'
            )));                                                                                                       
            $this->setPagination($pagination);           
        }
        
}
