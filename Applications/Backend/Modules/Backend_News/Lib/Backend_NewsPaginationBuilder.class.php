<?php
/*
 * name: NewsPaginationBuilder
 * @description : 
 */ 
 
class Backend_NewsPaginationBuilder extends PaginationBuilder{
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
        /*public function buildIndexMosaique(){
            //on prepare la requete
            $requete=new Requete();
            
            //On creer la pagination
            $pagination=new MosaiquePagination(array(
                'default'=>$this->config['msg']['default-search-empty'],
                'nbrObjectsPerPage'=>$this->config['define']['mosaique-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['mosaique-nbrSelectablePages'],   
                'requete'=>$requete,
                'classObject'=>'news',
                'page'=>1));                                   

            $pagination->setTesselle(new Tesselle(array(
				'elements'=>[new TitleTesselleElement(['class'=>'title', 'functions'=>['getNom','getContent'], 'link'=>true]),
							new ImgTesselleElement(['class'=>'miniature', 'functions'=>['getMiniature','getMiniature','generateHtmlPath'], 'link'=>true]),
							new TitleTesselleElement(['class'=>'categorie','functions'=>['getCategorie','getValue']]),
							new TitleTesselleElement(['class'=>'langue','functions'=>['getLangue']]),
							new BlockTesselleElement(['class'=>'newline']),
							new TitleTesselleElement(['class'=>'saga','functions'=>['getSaga']])
				],
				'class'=>'news_tesselle'
            )));                                                                                                       
            $this->setPagination($pagination);           
        }*/
    
		//Fork de this buildTable
		public function buildIndexTable(){
			return $this->buildTable();
		}
		
        public function buildTable(){
            //on prepare la requete
            $requete=new Requete();
            
            //On creer la pagination
            $pagination=new TablePagination(array(
                'default'=>$this->config['msg']['default-search-empty'],
                'nbrObjectsPerPage'=>$this->config['define']['table-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['table-nbrSelectablePages'],   
                'requete'=>$requete,
                'classObject'=>'news',
                'page'=>1));                                   

			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-nbr'],
                                                    'functions'=>['getId'],
                                                    'order'=>'id'))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-app'],
                                                    'functions'=>['getApp'],
                                                    'order'=>'app')));                                                
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-accreditation'],
                                                    'functions'=>['getAccreditation'],
                                                    'order'=>'news.accreditation'))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-statut'],
                                                    'functions'=>['getStatut'],
                                                    'order'=>'news.statut')));
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-text'],
                                                    'functions'=>['getTextHtml'],
                                                    'maxLength'=>5000)));
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-date'],
                                                    'functions'=>['getDateCreation'],
                                                    'order'=>'news.dateCreation'))); 
			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-updatelink'],
                                                    'functions'=>['getUpdateLink'],
                                                    'maxLength'=>75)));                                           
			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-deletelink'],
                                                    'functions'=>['getDeleteLink'],
                                                    'maxLength'=>75)));//A cause de la longueure des liens                                           
            $this->setPagination($pagination);                                                
        }
        
}

