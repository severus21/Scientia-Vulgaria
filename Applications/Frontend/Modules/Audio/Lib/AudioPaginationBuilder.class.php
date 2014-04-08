<?php
/*
 * name: AudioPaginationBuilder
 * @description : 
 */ 
 
class AudioPaginationBuilder extends PaginationBuilder{
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
            $requete->addCol(new IntCol(array('name'=>'id_audio_streaming', 'value'=>-1, 'comparisonOperator'=>'>')));
            
            //On creer la pagination
            $pagination=new MosaiquePagination(array(
                'default'=>'Aucun résultat ne correspond à votre recherche',
                'nbrObjectsPerPage'=>50,
                'nbrSelectablePages'=>11,   
                'requete'=>$requete,
                'classObject'=>'Audio',
                'page'=>1));                                   

            $pagination->setTesselle(new Tesselle(array(
				'elements'=>[new TextTesselleElement(['functions'=>['getDescription']])],
				'class'=>'audio_tesselle'
            )));                                                      
            $this->setPagination($pagination);           
        }
    
        public function buildIndexTable(){
            //on prepare la requete
            $requete=new Requete();
            $requete->addCol(new IntCol(array('name'=>'id_audio_streaming', 'value'=>-1, 'comparisonOperator'=>'>')));
            
            //On creer la pagination
            $pagination=new TablePagination(array(
                'default'=>'Aucun résultat ne correspond à votre recherche',
                'nbrObjectsPerPage'=>50,
                'nbrSelectablePages'=>11,   
                'requete'=>$requete,
                'classObject'=>'Audio',
                'page'=>1));                                   

            $pagination->addCell(new TextCell(array('name'=>'Decscription',
                                                            'functions'=>['getDescription'])));
            $pagination->addCell(new TextCell(array('name'=>'Size',
                                                            'functions'=>['getSize'])));                                           
            $this->setPagination($pagination);                                                
        }
        
        public function buildGestionMosaique($login){
            $this->buildIndexMosaique();
            $this->pagination->getRequete()->addCol(new StringCol(array(
                'name'=>'login', 
                'value'=>$login, 
                'comparisonOperator'=>'=',
                'logicalOperator'=>'OR')));
        }
        
        public function buildGestionTable($login){
            $this->buildIndexTable();
            $this->pagination->getRequete()->addCol(new StringCol(array(
                'name'=>'login', 
                'value'=>$login, 
                'comparisonOperator'=>'=',
                'logicalOperator'=>'OR')));
        }

}
