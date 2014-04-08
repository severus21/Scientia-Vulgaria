d<?php
/*
 * name: Forum_TopicPaginationBuilder
 * @description : 
 */ 
 
class Forum_TopicPaginationBuilder extends PaginationBuilder{
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
        public function buildShowMosaique($topicRecord, $currentUser){
            //on prepare la requete
            $requete=new Requete();
            $requete->addCol(new IntCol(['name'=>'topicId', 'value'=>$topicRecord->getId(), 'comparisonOperator'=>'=']));
            //On creer la pagination
            $pagination=new MosaiquePagination(array(
                'default'=>$this->config['msg']['default-search-empty'],
                'nbrObjectsPerPage'=>$this->config['define']['mosaique-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['mosaique-nbrSelectablePages'],   
                'requete'=>$requete,
                'classObject'=>'post',
                'app'=>'forum',
                'asc'=>'desc',
                'change'=>false,
                'page'=>1));                                   

            $pagination->setTesselle(new Tesselle(array(
				'elements'=>[new TextTesselleElement(['class'=>'user_info',
														'functions'=>['getCreateur','getLogin']]),
							new TextTesselleElement(['class'=>'header',
														'functions'=>['buildShowHeader'],
														'args'=>[0=>['currentUser'=>$currentUser,
																		'topicRecord'=>$topicRecord]]]),
							new TextTesselleElement(['class'=>'text','functions'=>['getTextHtml']])
							//new TextTesselleElement(['class'=>'text','functions'=>['getTextBBCode']])
				],
				'class'=>'post_tesselle'
            )));                                                                                                       
            $this->setPagination($pagination);       
        }
    
}
