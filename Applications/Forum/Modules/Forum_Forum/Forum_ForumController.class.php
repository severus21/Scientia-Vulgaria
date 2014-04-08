<?php

class Forum_ForumController extends MiddleController{
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
		public function executeIndex(){
			$this->page->addVar('title', 'Forum');
			//Vérification des droits d'acces
			$id=$this->httpRequette->getData('id');
			$forumRecord=$this->managers->getManagerOf('forum')->get( $id );
			if( !is_object($forumRecord) || $forumRecord->getAccreditation()>$this->app->getUser()->getAccreditation() ){
				$this->httpReponse->redirect404();
			}
			
			$cache=new Cache();
			$formBuilder = new Forum_ForumFormBuilder(null, $this->config);
			$formBuilder->buildSearch($id);
			$formSearch=$formBuilder->getForm()->createView($id);
            $searchRequete=$this->executeSearch('index');
            
            //Menu
            $navBuilder=new Forum_ForumNavBuilder(null, $this->config);
			$secondaryTopNav=$navBuilder->buildIndexTop( $this->app->getUser(), $forumRecord, $formSearch);
			
			//Pagination
			$paginationBuilder=new Forum_ForumPaginationBuilder(null, $this->config);
			$paginationBuilder->buildIndexTable($id, $this->app->getUser()->getAccreditation(), $this->app->getUser()->getStatut() );
			   
			$paginationBuilder->getPagination()->getRequete()->addCols($searchRequete->getCols());    
            $paginationBuilder->getPagination()->getRequete()->addJointures($searchRequete->getJointures());   
            $paginationBuilder->getPagination()->getRequete()->addOrders($searchRequete->getOrder());
			
			//On met en cache les premières pages dans le cas general
			$page=$paginationBuilder->getPagination()->getPage();
			$cols=$paginationBuilder->getPagination()->getRequete()->getCols();
			$order=$paginationBuilder->getPagination()->getRequete()->getOrder();
			$a=$this->httpRequette->getExists('asc');
			
			if($page>=1 && $page<=2 && empty($cols) && empty($order) && !$a){
				$content=$cache->getCache('Forum', $this->getModule(), 'indexPagination'.substr($echange,10).$page, (function($arg){
					return $arg['paginationBuilder']->getPagination()->build();
				}), array('paginationBuilder'=>$paginationBuilder));
			}else
				$content=$paginationBuilder->getPagination()->build();
			
			$this->page->addVar('secondaryTopNav', $secondaryTopNav);
			$this->page->addVar('content', $content);
			$this->page->addVar('config', $this->config);
		}
		
		public function executeSearch($page){
            $httpData=strtolower($this->httpRequette->method()).'Data';
			$httpExists=strtolower($this->httpRequette->method()).'Exists';
			
			$id=$this->httpRequette->getData('id');
            $this->httpRequette->$httpExists('recherche') ? $recherche=$this->httpRequette->$httpData('recherche') : $recherche='';
            $this->httpRequette->$httpExists('langue') ? $langue=$this->httpRequette->$httpData('langue') : $langue='';
            
            if( empty($recherche) && empty($langue) )
				return new Requete();
            
            $formBuilder = new Forum_ForumFormBuilder(null, $this->config);
            $formBuilder->buildSearch($id);
            $formBuilder->setValues(array(
                'recherche'=> $recherche,
                'langue'=>$langue,
                'id'=>$id
            ));
           
            if(!$formBuilder->getForm()->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect($page);
            }
            
            $requeteBuilder=new Forum_ForumRequeteBuilder();
            $requeteBuilder->buildFromForm($formBuilder->getForm());
            $requete= $requeteBuilder->getRequete();   
            
            if($recherche!=''){
				$requete->addCol(new StringCol(['name'=>'titre', 'value'=>$recherche, 'comparisonOperator'=>'LIKE', 'strict'=>false]));
				$requete->addOrders(['titre']);
			}    
			
			$this->httpRequette->sessionUnset('form');
            return $requete;
        }
    
}
