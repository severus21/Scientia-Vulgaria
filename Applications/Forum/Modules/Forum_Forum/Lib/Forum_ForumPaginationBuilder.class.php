<?php
/*
 * name: Forum_ForumPaginationBuilder
 * @description : 
 */ 
 
class Forum_ForumPaginationBuilder extends PaginationBuilder{
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
        public function buildIndexTable($id, $accreditation, $statut){
            //on prepare la requete
            $requete=new Requete();
            $requete->addJointure(new Jointure(['tableJ'=>'forum', 'colO'=>'forumid', 'colJ'=>'id']));
            $requete->addCol(new IntCol(['name'=>'forumid', 'value'=>$id, 'comparisonOperator'=>'=']));
			$requete->addCol(new IntCol(['table'=>'forum', 'name'=>'accreditation', 'value'=>$accreditation, 'comparisonOperator'=>'<=']));
			$requete->addCol(new IntCol(['table'=>'forum', 'name'=>'view_statut', 'value'=>$statut, 'comparisonOperator'=>'<=']));
            
            //On creer la pagination
            $pagination=new TablePagination(array(
                'default'=>$this->config['msg']['default-search-empty'],
                'nbrObjectsPerPage'=>$this->config['define']['table-nbrObjectsPerPage'],
                'nbrSelectablePages'=>$this->config['define']['table-nbrSelectablePages'],   
                'requete'=>$requete,
                'classObject'=>'topic',
                'app'=>'forum',
                'change'=>false,
                'page'=>1));                               

            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-titre'],
                                                    'functions'=>['getShowLink'],
                                                    'maxLength'=>100,
                                                    'order'=>'titre'))); 
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-createur'],
                                                    'functions'=>['getCreateur','getLogin'],
                                                    'order'=>'frontend.user.login')));                                                
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-vues'],
                                                    'functions'=>['getVues'],
                                                    'order'=>'vues')));
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-posts'],
                                                    'functions'=>['getPosts'],
                                                    'order'=>'posts')));
            $pagination->addCell(new TextCell(array('name'=>$this->config['msg']['table-th-dateCreation'],
                                                    'functions'=>['getDateCreation'],
                                                    'order'=>'dateCreation',)));                                      
            $this->setPagination($pagination);                                                
	}
}
