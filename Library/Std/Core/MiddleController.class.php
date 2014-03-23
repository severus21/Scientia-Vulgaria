<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

abstract class MiddleController extends BackController{   
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
        public function executeGestion(){
			$cache=new Cache();
            $formSearch=$this->executeSearchIndex('gestion');
            $searchRequete=$this->executeSearch('gestion');
			
			//On construit le menu
			$navClassBuilder=$this->getModule().'NavBuilder';
			$navBuilder=new $navClassBuilder(null, $this->config);
			$nav=$navBuilder->buildGestionTop($formSearch);
			
            //On construit la pagination
            $pagClassBuilder=$this->getModule().'PaginationBuilder';
            $paginationBuilder=new $pagClassBuilder(null, $this->config);
            $echange=$paginationBuilder->getEchangeFunction();
            $login=$this->app->getUser()->getLogin();
            if($echange!='')
                $paginationBuilder->$echange($login);
            else
                $paginationBuilder->buildGestionMosaique($login);
                
            $paginationBuilder->getPagination()->getRequete()->addCols($searchRequete->getCols());    
            $paginationBuilder->getPagination()->getRequete()->addJointures($searchRequete->getJointures());   
            $paginationBuilder->getPagination()->getRequete()->addOrders($searchRequete->getOrder()); 
            
            //Mise en cache de la pagination de base pour les 2 premières pages 
			$page=$paginationBuilder->getPagination()->getPage();
			$cols=$paginationBuilder->getPagination()->getRequete()->getCols();
			$order=$paginationBuilder->getPagination()->getRequete()->getOrder();
			$a=$this->httpRequette->getExists('asc');
			
			$cacheDepth = array_key_exists('gestion-cache-depth', $this->config['define']) ? $this->config['define']['gestion-cache-depth'] : 2;
			if($page>=1 && $page<=2 && empty($cols) && empty($order) && !$a){
				$content=$cache->getCache('Frontend', $this->getModule(), 'gestionagination'.substr($echange,10).$page, (function($arg){
					return $arg['paginationBuilder']->getPagination()->build();
				}), array('paginationBuilder'=>$paginationBuilder));
			}else{
				$content=$paginationBuilder->getPagination()->build();
			}
            $this->page->addVar('content',  $content);
            $this->page->addVar('secondaryTopNav',  $nav);
        }   
        
        public function executeIndex(){
			$cache=new Cache();
            $formSearch=$this->executeSearchIndex('index');
            $searchRequete=$this->executeSearch('index');     
            
            //On construit le menu
			$navClassBuilder=$this->getModule().'NavBuilder';
			$navBuilder=new $navClassBuilder(null, $this->config);
			$nav=$navBuilder->buildIndexTop($formSearch);
            
            //On construit la pagination
            $pagClassBuilder=$this->getModule().'PaginationBuilder';
            $paginationBuilder=new $pagClassBuilder(null, $this->config);
            $echange=$paginationBuilder->getEchangeFunction();
            if($echange!='')
                $paginationBuilder->$echange();
            else
                $paginationBuilder->buildIndexMosaique();
               
			if(!empty($searchRequete)){
				$paginationBuilder->getPagination()->getRequete()->addCols($searchRequete->getCols());    
				$paginationBuilder->getPagination()->getRequete()->addJointures($searchRequete->getJointures());   
				$paginationBuilder->getPagination()->getRequete()->addOrders($searchRequete->getOrder()); 
			}
			
			//On met en cache les premières pages dans le cas general
			$page=$paginationBuilder->getPagination()->getPage();
			$cols=$paginationBuilder->getPagination()->getRequete()->getCols();
			$order=$paginationBuilder->getPagination()->getRequete()->getOrder();
			$a=$this->httpRequette->getExists('asc');

			$cacheDepth = array_key_exists('index-cache-depth', $this->config['define']) ? $this->config['define']['index-cache-depth'] : 2;
			if($page>=1 && $page<=$cacheDepth && empty($cols) && empty($order) && !$a){
				$content=$cache->getCache('Frontend', $this->getModule(), 'indexPagination'.substr($echange,10).$page, (function($arg){
					return $arg['paginationBuilder']->getPagination()->build();
				}), array('paginationBuilder'=>$paginationBuilder));
			}else{
				$content=$paginationBuilder->getPagination()->build();
			}
			$this->page->addVar('config', $this->config);
            $this->page->addVar('content',  $content);
            $this->page->addVar('secondaryTopNav',  $nav);
        }
    
        public function executeInsertIndex(){
            $cache=new Cache();
            
            //On construit le menu
            $nav=$cache->getCache('Frontend', $this->getModule(), 'insertIndexNav', (function($arg){
				$navClassBuilder=$arg['module'].'NavBuilder';
				$navBuilder=new $navClassBuilder(null, $arg['config']);
				return $navBuilder->buildInsertTop();
			} ), array('module'=>$this->getModule(), 'config'=>$this->config));
            $this->page->addVar('secondaryTopNav', $nav);
            
            //On construit le formulaire
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
					$formBuilderClass=$this->getModule().'FormBuilder';
					$formBuilder = new $formBuilderClass(null, $this->config);
					$formBuilder->buildInsert();
					$form=$formBuilder->getForm()->createView();
                $this->page->addVar('form', $form);
            }
        }
        
        public function executeUpdateIndex(){
           $cache=new Cache();
           
            //On construit le menu
            $nav=$cache->getCache('Frontend', $this->getModule(), 'updateIndexNav', (function($arg){
				$navClassBuilder=$arg['module'].'NavBuilder';
				$navBuilder=new $navClassBuilder(null, $arg['config']);
				return $navBuilder->buildUpdateTop();
			} ), array('module'=>$this->getModule(), 'config'=>$this->config));
            $this->page->addVar('secondaryTopNav', $nav);
            
            //On construit le form
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }elseif($this->httpRequette->getExists('id')){
                $formBuilder = new $builderClasse(null, $this->config);
                $formBuilder->buildUpdate($this->httpRequette->getData('id'));
                $this->page->addVar('form', $formBuilder->getForm()->createView());
            }
        }
    
		public function executeSearchIndex($action){
            $builderClasse=$this->getModule().'FormBuilder';
            
            if($this->httpRequette->sessionExists('form')){
                $form=$this->httpRequette->sessionData('form');
                $this->httpRequette->sessionUnset('form');
            }else{
					$formBuilderClass=$this->getModule().'FormBuilder';
					$formBuilder = new $formBuilderClass(null, $this->config);
					$formBuilder->buildSearch($action);
					$form=$formBuilder->getForm()->createView();
            }
            return $form;
        }
        
		public function executeShow($flagPag=true, $flagNav=true){
			$manager=$this->managers->getManagerOf($this->getModule());
			$id=$this->httpRequette->getData('id');    
			$record=$manager->get($id);
			if(!is_object($record)){
				$this->httpReponse->redirect404();
			}
			
			$objFac=new ObjectFactory();
			$obj=$objFac->buildObject($record);  
			$this->page->addVar(lcfirst($this->getModule()), $obj);
			
			//On construit le menu
			if($flagNav){
				$navClassBuilder=$this->getModule().'NavBuilder';
				$navBuilder=new $navClassBuilder(null, $this->config);
				$nav=$navBuilder->buildShowTop($this->app->getUser(), $obj);
				$this->page->addVar('secondaryTopNav', $nav);
			}
			
			//la pagination
			if($flagPag){
				$paginationClassBuilder=$this->getModule().'PaginationBuilder';
				$paginationBuilder= new $paginationClassBuilder(null, $this->config);
				$paginationBuilder->buildRelatedTable($this->getRelated($obj));
				$this->page->addVar('related',  $paginationBuilder->getPagination()->build());
			}
			
			//la config
			$this->page->addVar('config',  $this->config);
		}
}
