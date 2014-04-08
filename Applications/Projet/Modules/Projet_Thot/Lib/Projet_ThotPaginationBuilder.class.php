<?php
/*
 * name: Projet_ThotPaginationBuilder
 * @description : 
 */ 
 
class Projet_ThotPaginationBuilder extends PaginationBuilder{
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
        public function buildListMosaique(){
            //on prepare la requete
            $requete=new Requete();
            
            //On creer la pagination
            $pagination=new MosaiquePagination(array(
                'default'=>$this->config['msg']['default-search-empty'],
                'nbrObjectsPerPage'=>$this->config['define']['mosaique-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['mosaique-nbrSelectablePages'],   
                'requete'=>$requete,
                'classObject'=>'ThotExemple',
                'page'=>1));                                   

            $pagination->setTesselle(new Tesselle(array(
				'elements'=>[new TitleTesselleElement(['class'=>'numero','functions'=>['getNumero'],
														'link'=>true]),
							new ImgTesselleElement(['class'=>'miniature', 
														'functions'=>['getImage','generateHtmlPath'],
														'link'=>true,
														'height'=>$this->config['define']['height-miniature'],
														'weight'=>$this->config['define']['weight-miniature']]),
							new TitleTesselleElement(['class'=>'c','functions'=>['getC']])
				],
				'class'=>'thot-exemple_tesselle'
            )));                                                                                                       
            $this->setPagination($pagination);           
        }
}
