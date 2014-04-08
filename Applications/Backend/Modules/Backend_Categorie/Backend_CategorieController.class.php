<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class Backend_CategorieController extends MiddleController{
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
		
		
		public function executeDelete(){
			ignore_user_abort(true);
            $manager=$this->managers->getManagerOf('Categorie');
			$id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record))
				$this->httpReponse404();
            
            $manager->delete($id);
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-delete']);
            $this->httpReponse->redirect('index');
        }
       
		public function executeIndex(){
            $this->page->addVar('title', 'Categorie'); 
            $formSearch=$this->executeSearchIndex('index');
            $searchRequete=$this->executeSearch('index');     
            
            if($this->httpRequette->postExists('categorie')){
				$tmp=explode('.',$this->httpRequette->postData('categorie'));
				$app=$tmp[0];
				$classObj=preg_replace('#categorie#', 'Categorie', $tmp[1]);
				if($this->httpRequette->postExists('lang') && $this->httpRequette->postData('lang')!="")
					$lang=$this->httpRequette->postData('lang');
				else
					$lang=$this->httpRequette->sessionData('lang');
				$this->httpReponse->redirect('index-'.$app.'-'.$classObj.'-'.$lang);
			}elseif($this->httpRequette->getExists('classObj') && $this->httpRequette->getExists('apdpl') && $this->httpRequette->getExists('lang')){
				$app=$this->httpRequette->getData('apdpl');
				$classObj=$this->httpRequette->getData('classObj');
				$lang=$this->httpRequette->getData('lang');
			}
			
			
				
			
            //On construit le menu
			$navBuilder=new Backend_CategorieNavBuilder(null, $this->config);
			$formBuilder=new Backend_CategorieFormBuilder(null, $this->config);
			$formBuilder->buildSearch();
			$nav=$navBuilder->buildIndexTop($formBuilder->getForm()->createView());
            
            if(!empty($classObj) && !empty($app)){
				//On construit la pagination
				$paginationBuilder=new Backend_CategoriePaginationBuilder(null, $this->config);
				$paginationBuilder->buildIndexTable($classObj, $app, $lang);
				   
				if(!empty($searchRequete)){
					$paginationBuilder->getPagination()->getRequete()->addCols($searchRequete->getCols());    
					$paginationBuilder->getPagination()->getRequete()->addJointures($searchRequete->getJointures());   
					$paginationBuilder->getPagination()->getRequete()->addOrders($searchRequete->getOrder()); 
				}
				$content=$paginationBuilder->getPagination()->build();
			}else
				$content=$this->config['msg']['unknow-categorie'];
			
			
			$this->page->addVar('config', $this->config);
            $this->page->addVar('content',  $content);
            $this->page->addVar('secondaryTopNav',  $nav);
        }
	 
		public function executeInsertIndex(){
            $this->page->addVar('title', 'Ajout news');
            parent::executeInsertIndex();
        }
        
		public function executeInsert(){
			ignore_user_abort(true);

            $formBuilder = new Backend_CategorieFormBuilder(null, $this->config);
            $formBuilder->buildInsert();
            $formBuilder->setValues(array(
                'table'=>$this->httpRequette->postData('table'),
                'nom_fr'=>$this->httpRequette->postData('nom_fr'),
                'value_fr'=>$this->httpRequette->postData('value_fr')
            ));

            if(!$formBuilder->getForm()->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index');
            }
			$record=$formBuilder->getForm()->getEntity();
			$table=explode('.', $this->httpRequette->postData('table'))[1];
			$table=preg_replace('#categorie#', 'Categorie', $table); 
            $manager=$this->managers->getManagerOf($table);
            
            if(!$record->setId($manager->save($record))){
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('insert-index');
            }
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect('index');
        }    
        
		public function executeUpdateIndex(){
            $this->page->addVar('title', 'Update news');
            //On construit le menu
            $navBuilder=new Backend_CategorieNavBuilder(null, $this->config);
            $nav=$navBuilder->buildInsertTop();
            $this->page->addVar('secondaryTopNav', $nav);            
            //On construit les formulaires
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }elseif($this->httpRequette->getExists('id')){
				$id=$this->httpRequette->getData('id');
				$table=$this->httpRequette->getData('table');
				$table=preg_replace('#categorie#', 'Categorie', $table); 
				$manager=$this->managers->getManagerOf($table);
				
                
                $formBuilder = new Backend_CategorieFormBuilder(null, $this->config);
                $formBuilder->buildUpdate($manager->get($id), $table);
                $form=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                $this->page->addVar('form', $form);
            }
        } 
       
        public function executeUpdate(){
			ignore_user_abort(true);
            $id=$this->httpRequette->getData('id');
            $table=$this->httpRequette->getData('table');
            $manager=$this->managers->getManagerOf($table);
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
            
            $formBuilder = new Backend_CategorieFormBuilder(null, $this->config);
            $formBuilder->buildUpdate($record, $table);
            $array=[];
            
            
            $langueManager=new LangueManager(PDOFactory::getMysqlConnexion());
            $records=$langueManager->getList();
            
			for($i=0; $i<count($records); $i++){
				$array['nom_'.$records[$i]->getValue_fr()]=$this->httpRequette->postData('nom_'.$records[$i]->getValue_fr());
				$array['value_'.$records[$i]->getValue_fr()]=$this->httpRequette->postData('value_'.$records[$i]->getValue_fr());
			}
            $formBuilder->setValues($array);

            if(!$formBuilder->getForm()->isValid()){     
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('update-index-'.$id.'-'.$table);
            }   
            $record->mergeRecord( $formBuilder->getForm()->getEntity() );
            
            if($record->setId($manager->save($record))){
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('update-index-'.$id.'-'.$table);
            }
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect($this->app->getUser()->getLastUrl(2));
        }
        
        public function executeSearch(){
		}
  
}
