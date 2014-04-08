<?php
/*
 * name: Backend_CategoriePaginationBuilder
 * @description : 
 */ 
 
class Backend_CategoriePaginationBuilder extends PaginationBuilder{
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
		public function buildIndexTable($classObj, $app, $lang){
			return $this->buildTable($classObj, $app, $lang);
		}
		
        public function buildTable($classObj, $app, $lang){
            //on prepare la requete
            $requete=new Requete();
            
            //On creer la pagination
            $pagination=new TablePagination(array(
                'default'=>$this->config['msg']['default-search-empty'],
                'nbrObjectsPerPage'=>$this->config['define']['table-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['table-nbrSelectablePages'],   
                'requete'=>$requete,
                'classObject'=>$classObj,
                'app'=>strtolower($app),
                'page'=>1));                                   

			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-nom-def'],
                                                    'functions'=>['getRecord','getNom_fr'],
                                                    'order'=>'nom_fr'))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-value-def'],
                                                    'functions'=>['getRecord','getValue_fr'],
                                                    'order'=>'value_'.$lang)));
			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-nom'],
                                                    'functions'=>['getRecord','getNom_'.$lang],
                                                    'order'=>'nom_'.$lang))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-value'],
                                                    'functions'=>['getRecord','getValue_'.$lang],
                                                    'order'=>'value_'.$lang)));
			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-updatelink'],
                                                    'functions'=>['getUpdateLink'],
                                                    'maxLength'=>100)));                                           
			$pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-deletelink'],
                                                    'functions'=>['getDeleteLink'],
                                                    'maxLength'=>100)));//A cause de la longueure des liens                                           
            $this->setPagination($pagination);                                                
        }
        
}

