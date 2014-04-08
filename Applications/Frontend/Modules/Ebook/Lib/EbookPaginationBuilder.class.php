<?php
/*
 * name: EbookPaginationBuilder
 * @description : 
 */ 
 
class EbookPaginationBuilder extends PaginationBuilder{
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
                'classObject'=>'ebook',
                'page'=>1));                                   

            $pagination->setTesselle(new Tesselle(array(
				'elements'=>[new TitleTesselleElement(['class'=>'titre','functions'=>['getNom'], 'link'=>true]),
							new ImgTesselleElement(['class'=>'miniature','functions'=>['getMiniature','getMiniature','generateHtmlPath'], 'link'=>true]),
							new TitleTesselleElement(['class'=>'auteur','functions'=>['getAuteur']]),
							new TitleTesselleElement(['class'=>'langue','functions'=>['getLangue']]),
							new BlockTesselleElement(['class'=>'newline']),
							new TitleTesselleElement(['class'=>'genre','functions'=>['getGenre','getValue']]),
							new TitleTesselleElement(['class'=>'etiquette','functions'=>['getEtiquette','getValue']])
				],
				'class'=>'ebook_tesselle'
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
                'classObject'=>'ebook',
                'page'=>1));                                   

            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-titre'],
                                                    'functions'=>['getNom',],
                                                    'order'=>'nom'))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-auteur'],
                                                    'functions'=>['getAuteur'],
                                                    'order'=>'auteur')));                                                
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-genre'],
                                                    'functions'=>['getGenre','getValue'],
                                                    'order'=>'id_ebookCategorie_genre')));
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-langue'],
                                                    'functions'=>['getLangue'],
                                                    'order'=>'langue')));
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-etiquette'],
                                                    'functions'=>['getEtiquette','getValue'],
                                                    'order'=>'id_etiquette_etiquette'))); 
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
                'classObject'=>'ebook',
                'change'=>false,
                'page'=>1));   
               
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-titre'],
                                                    'functions'=>['getNom'],
                                                    'order'=>'nom'))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-auteur'],
                                                    'functions'=>['getAuteur'],
                                                    'order'=>'auteur')));                                                
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-genre'],
                                                    'functions'=>['getGenre','getValue'],
                                                    'order'=>'id_ebookCategorie_genre')));
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-langue'],
                                                    'functions'=>['getLangue'],
                                                    'order'=>'langue')));
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-etiquette'],
                                                    'functions'=>['getEtiquette','getValue'],
                                                    'order'=>'id_etiquette_etiquette'))); 
			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-showlink'],
                                                    'functions'=>['getShowLink'])));  
            $this->setPagination($pagination);                                                
        }
        
}
