<?php
/*
 * name: SeriePaginationBuilder
 * @description : 
 */ 
 
class SeriePaginationBuilder extends PaginationBuilder{
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
        public function buildIndexMosaique(){
            //on prepare la requete
            $requete=new Requete();
            
            //On creer la pagination
            $pagination=new MosaiquePagination(array(
                'default'=>$this->config['msg']['default-search-empty'],
                'nbrObjectsPerPage'=>$this->config['define']['mosaique-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['mosaique-nbrSelectablePages'],   
                'requete'=>$requete,
                'classObject'=>'serie',
                'page'=>1));                                   

            $pagination->setTesselle(new Tesselle(array(
				'elements'=>[new TitleTesselleElement(['class'=>'title', 'functions'=>['getNom'], 'link'=>true]),
							new ImgTesselleElement(['class'=>'miniature', 'functions'=>['getMiniature','getMiniature','generateHtmlPath'], 'link'=>true]),
							new TitleTesselleElement(['class'=>'categorie','functions'=>['getCategorie','getValue']]),
							new TitleTesselleElement(['class'=>'langue','functions'=>['getLangue']])
				],
				'class'=>'serie_tesselle'
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
                'classObject'=>'serie',
                'page'=>1));                                   

			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-nom'],
                                                    'functions'=>['getNom'],
                                                    'order'=>'nom'))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-categorie'],
                                                    'functions'=>['getCategorie','getValue'],
                                                    'order'=>'id_serieCategorie_categorie')));                                                
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-realisateur'],
                                                    'functions'=>['getRealisateur'],
                                                    'order'=>'realisateur'))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-resume'],
                                                    'functions'=>['getResume']))); 
			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-showlink'],
                                                    'functions'=>['getShowLink'])));                                           
            $this->setPagination($pagination);                                                
        }
        
        public function buildGestionMosaique($login){
            $this->buildIndexMosaique();
            $this->pagination->getRequete()->addCol(new StringCol(array(
                'name'=>'login', 
                'value'=>$login, 
                'strict'=>true,
                'comparisonOperator'=>'=')));
        }
        
        public function buildGestionTable($login){
            $this->buildIndexTable();
            $this->pagination->getRequete()->addCol(new StringCol(array(
                'name'=>'login', 
                'value'=>$login, 
                'strict'=>true,
                'comparisonOperator'=>'=')));
        }
        
        public function buildRelatedTable($requete){
            $pagination=new TablePagination(array(
                'default'=>$this->config['msg']['default-related-empty'],
                'nbrObjectsPerPage'=>$this->config['define']['related-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['related-nbrSelectablePages'],   
                'requete'=>$requete,
                'classObject'=>'serie',
                'change'=>false,
                'page'=>1));   
               
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-nom'],
                                                    'functions'=>['getNom'],
                                                    'order'=>'nom'))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-categorie'],
                                                    'functions'=>['getCategorie','getValue'],
                                                    'order'=>'id_seriecategorie_categorie')));                                                
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-realisateur'],
                                                    'functions'=>['getRealisateur'],
                                                    'order'=>'realisateur')));
			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-langue'],
                                                    'functions'=>['getLangue'],
                                                    'order'=>'langue'))); 
			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-showlink'],
                                                    'functions'=>['getShowLink'])));  
            $this->setPagination($pagination);                                                
        }
        
}
