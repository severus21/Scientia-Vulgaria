<?php
/*
 * name: Backend_CachePaginationBuilder
 * @description : 
 */ 
 
class Backend_CachePaginationBuilder extends PaginationBuilder{
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
        public function buildIndexMosaique($objects&){
            //on prepare la requete
            $requete=new Requete();
            
            //On creer la pagination
            $pagination=new MosaiquePagination(array(
                'default'=>$this->config['msg']['default-search-empty'],
                'nbrObjectsPerPage'=>$this->config['define']['mosaique-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['mosaique-nbrSelectablePages'],   
                'objects'=>$objects,
                'classObject'=>'cacheElement',
                'page'=>1));                                   

            $pagination->setTesselle(new Tesselle(array(
				'elements'=>[new TitleTesselleElement(['class'=>'title', 'functions'=>['getNom','getContent'], 'link'=>true]),
							new ImgTesselleElement(['class'=>'miniature', 'functions'=>['getMiniature','getMiniature','generateHtmlPath'], 'link'=>true]),
							new TitleTesselleElement(['class'=>'categorie','functions'=>['getCategorie','getValue']]),
							new TitleTesselleElement(['class'=>'langue','functions'=>['getLangue']]),
							new BlockTesselleElement(['class'=>'newline']),
							new TitleTesselleElement(['class'=>'saga','functions'=>['getSaga']])
				],
				'class'=>'film_tesselle'
            )));                                                                                                       
            $this->setPagination($pagination);           
        }
    
        public function buildIndexTable(){
            //on prepare la requete
            $requete=new Requete();
            
            //On creer la pagination
            $pagination=new TablePagination(array(
                'default'=>$this->config['msg']['default-search-empty'],
                'nbrObjectsPerPage'=>$this->config['define']['table-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['table-nbrSelectablePages'],   
                'requete'=>$requete,
                'classObject'=>'film',
                'page'=>1));                                   

			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-nom'],
                                                    'functions'=>['getNom','getContent'],
                                                    'order'=>'content.content'))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-categorie'],
                                                    'functions'=>['getCategorie','getValue'],
                                                    'order'=>'id_filmCategorie_categorie')));                                                
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-realisateur'],
                                                    'functions'=>['getRealisateur'],
                                                    'order'=>'realisateur'))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-langue'],
                                                    'functions'=>['getLangue'],
                                                    'order'=>'langue')));
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-subtitle'],
                                                    'functions'=>['getSubtitle'],
                                                    'order'=>'subtitle')));
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-resume'],
                                                    'functions'=>['getResume']))); 
			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-showlink'],
                                                    'functions'=>['getShowLink'])));                                           
            $this->setPagination($pagination);                                                
        }
}
