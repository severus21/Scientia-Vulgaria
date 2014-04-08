<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class Projet_ThotController extends BackController{
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
			$this->page->addVar('title', 'Thot');
			$this->page->addVar('config', $this->config);
			
			$navBuilder=new Projet_ThotNavBuilder(null, $this->config);
            $this->page->addVar('secondaryTopNav', $navBuilder->buildIndexTop());
		}
		public function executeInsertIndexExemple(){
            $this->page->addVar('title', '');
			$navBuilder=new Projet_ThotNavBuilder(null, $this->config);
            $this->page->addVar('secondaryTopNav', $navBuilder->buildInsertExempleTop());
            
            //On construit le formulaire
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
				$formBuilder = new Projet_ThotFormBuilder(null, $this->config);
				$formBuilder->buildInsertExemple();
				$form=$formBuilder->getForm()->createView();
                $this->page->addVar('form', $form);
            }
        }
           
		public function executeUpdateIndexExemple(){
            $this->page->addVar('title', '');
			$navBuilder=new Projet_ThotNavBuilder(null, $this->config);
            $this->page->addVar('secondaryTopNav', $navBuilder->buildUpdateExempleTop());
            
            $objFact=new ObjectFactory();
            $manager=$this->managers->getManagerOf('ThotExemple');
            $obj=$objFact->buildObject($manager->get( $this->httpRequette->getData('id') ));
            
            //On construit le formulaire
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
				$formBuilder = new Projet_ThotFormBuilder(null, $this->config);
				$formBuilder->buildUpdateExemple($obj);
				$form=$formBuilder->getForm()->createView();
                $this->page->addVar('form', $form);
            }
            $this->page->addVar('obj', $obj);
            $this->page->addVar('config', $this->config);
        }
           
		public function executeInsertIndexMultiExemple(){
            $this->page->addVar('title', '');
			$navBuilder=new Projet_ThotNavBuilder(null, $this->config);
            $this->page->addVar('secondaryTopNav', $navBuilder->buildInsertExempleTop());
            
            //On construit le formulaire
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
				$formBuilder = new Projet_ThotFormBuilder(null, $this->config);
				$formBuilder->buildInsertMultiExemple();
				$form=$formBuilder->getForm()->createView();
                $this->page->addVar('form', $form);
            }
        }
        
        
        
 
		public function executeInsertIndexDatabase(){
            $this->page->addVar('title', '');
			$navBuilder=new Projet_ThotNavBuilder(null, $this->config);
            $this->page->addVar('secondaryTopNav', $navBuilder->buildInsertDatabaseTop());
            
            //On construit le formulaire
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
				$formBuilder = new Projet_ThotFormBuilder(null, $this->config);
				$formBuilder->buildInsertDatabase();
				$form=$formBuilder->getForm()->createView();
                $this->page->addVar('form', $form);
            }
        }
        
		public function executeInsertDatabase(){
			ignore_user_abort(true);

            $formBuilder = new Projet_ThotFormBuilder(null, $this->config);
            $formBuilder->buildInsertDatabase();
            $formBuilder->setValues(array(
                'nom'=>$this->httpRequette->postData('nom'),
                'type'=>$this->httpRequette->postData('type')    
            ));
         
            if( !$formBuilder->getForm()->isValid() ){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index-database');
            }
            $record=$formBuilder->getForm()->getEntity();
            
            
            
            $manager=$this->managers->getManagerOf('ThotDatabase');
         
            $record->setId($manager->save($record));
            $i=$record->getId();   
            if(!is_numeric($i)){
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);exit;
                $this->httpReponse->redirect('insert-index-database');
            }
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect('index');
        }
        
		public function executeInsertExemple(){
			ignore_user_abort(true);
            $f_image=$this->httpRequette->fileData('image');

            $formBuilder = new Projet_ThotFormBuilder(null, $this->config);
            $formBuilder->buildInsertExemple();
            $formBuilder->setValues(array(
                'id_thotDatabase_database'=>$this->httpRequette->postData('id_thotDatabase_database'),
                'c'=>$this->httpRequette->postData('c')
            ));
         
            if(!$formBuilder->getForm()->isValid() || empty($f_image)){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index-exemple');
            }
            $record=$formBuilder->getForm()->getEntity();
            $imageController = new ImageController($this->app, 'Image', 'insert');
            
            $manager=$this->managers->getManagerOf('ThotExemple');

			//Ajout de la miniature
            $image=$imageController->executeInsert(['file'=>$f_image, 
													'description'=>$this->config['define']['default-description-image'], 
													'redimention'=>false]);
													
			if(!$image)
                $this->httpReponse->redirect('insert-index-exemple');

            $record->setId_image_image($image->getRecord()->getId());
            $record->setLogin($this->app->getUser()->getLogin());
            
            if(!$record->isValid()){
				$imageController->executeDelete($image->getRecord()->getId());
                $this->httpReponse->redirect('insert-index-exemple');
            }
             
             
            $record->setId($manager->save($record));
            $i=$record->getId();   
            if(!is_numeric($i)){
				$imageController->executeDelete($image->getRecord()->getId());
				
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('insert-index-exemple');
            }
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect('list');
        }
       
		public function executeUpdateExemple(){
			ignore_user_abort(true);
			$manager=$this->managers->getManagerOf('ThotExemple');
            $id=$this->httpRequette->getData('id');
            $record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
			
			
            $formBuilder = new Projet_ThotFormBuilder(null, $this->config);
            $formBuilder->buildUpdateExemple($record);
            $formBuilder->setValues(array(
                'c'=>$this->httpRequette->postData('c')
            ));
         
            if(!$formBuilder->getForm()->isValid() ){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('update-index-exemple');
            }
			$record->mergeRecord( $formBuilder->getForm()->getEntity() );
          
             
            $record->setId($manager->save($record));
            $i=$record->getId();   
            if(!is_numeric($i)){
				$imageController->executeDelete($image->getRecord()->getId());
				
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $this->httpReponse->redirect('update-index-exemple');
            }
            
            $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect('list');
        }
       
		public function executeInsertMultiExemple(){
			ignore_user_abort(true);
			$f_file=$this->httpRequette->fileData('file');

      
            $formBuilder = new Projet_ThotFormBuilder(null,$this->config);
            $formBuilder->buildInsertMultiExemple();
            $formBuilder->setValues(array(
                'id_thotDatabase_database'=>$this->httpRequette->postData('id_thotDatabase_database')
            ));

            if(!$formBuilder->getForm()->isValid()  || empty($f_file) || $f_file['error']!=0){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index-multi-exemple');
            }
            $record=$formBuilder->getForm()->getEntity();
            $imageController = new ImageController($this->app, 'Image', 'insert');
            
            
            
            $manager=$this->managers->getManagerOf('ThotExemple');
            $zipArchive=new ZipArchive();
            
            $path=sys_get_temp_dir().'/Thot';
            mkdir($path);
            $zipArchive->open($f_file['tmp_name']);
            $zipArchive->extractTo($path);
            $record->setLogin($this->app->getUser()->getLogin());
            
            foreach(scandir($path) as $key => $element){
				$tmpPath=$path.'/'.$element;
				if($element !='.' && $element !='..'  && is_file($path.'/'.$element)){
					$newRecord=clone($record);
					
					$file=['tmp_name'=>$tmpPath,
						'type'=>mime_content_type($tmpPath),
						'size'=>filesize($tmpPath),
						'error'=>0];
					$image=$imageController->executeInsert(['file'=>$file, 
													'description'=>$this->config['define']['default-description-image'], 
													'redimention'=>false]);
					$record->setId_image_image($image->getRecord()->getId());
					
					if($image)
						$manager->save($record);
				}
				if(!is_dir($tmpPath))
					unlink($tmpPath);
				else
					rmdir($tmpPath);
			}
			rmdir($path);
            
			$this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);  
            $this->httpRequette->sessionUnset('form');
            $this->httpReponse->redirect('index');
        }
        
        
		public function executeList(){
            $formSearch=$this->executeSearchIndex('list');
            $searchRequete=$this->executeSearch('list');     
            
            //On construit le menu
			$navBuilder=new Projet_ThotNavBuilder(null, $this->config);
			$nav=$navBuilder->buildListTop($formSearch);
            
            //On construit la pagination
            $paginationBuilder=new Projet_ThotPaginationBuilder(null, $this->config);
            $echange=$paginationBuilder->getEchangeFunction();
            if($echange!='')
                $paginationBuilder->$echange();
            else
                $paginationBuilder->buildListMosaique();
               
			if(!empty($searchRequete)){
				$paginationBuilder->getPagination()->getRequete()->addCols($searchRequete->getCols());    
				$paginationBuilder->getPagination()->getRequete()->addJointures($searchRequete->getJointures());   
				$paginationBuilder->getPagination()->getRequete()->addOrders($searchRequete->getOrder()); 
			}
			$content=$paginationBuilder->getPagination()->build();
			
			$this->page->addVar('config', $this->config);
            $this->page->addVar('content',  $content);
            $this->page->addVar('secondaryTopNav',  $nav);
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
        
        public function executeSearch(){
			$httpData=strtolower($this->httpRequette->method()).'Data';
			$httpExists=strtolower($this->httpRequette->method()).'Exists';

            $this->httpRequette->$httpExists('id_thotDatabase_database') ? $database=$this->httpRequette->$httpData('id_thotDatabase_database') : $database='';
            
			if(empty($database))
				return new Requete();
            
            $formBuilder = new Projet_ThotFormBuilder(null, $this->config);
            $formBuilder->buildSearch();
            
            $formBuilder->setValues(array(
                'id_thotDatabase_database'=> $database
            ));
            
            if(!$formBuilder->getForm()->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect($page);
            }
            $requeteBuilder=new Projet_ThotRequeteBuilder();
            $requeteBuilder->buildFromForm($formBuilder->getForm());
            $requete= $requeteBuilder->getRequete();
				

            $this->httpRequette->sessionUnset('form');
            return $requete;
        }
    
		public function executeExport(){
			$id=$this->httpRequette->getData('id');
			$manager=$this->managers->getManagerOf('thotDatabase');
			$record=$manager->get($id);
            if(!is_object($record)){
                $this->httpReponse->redirect404();
            }
            
            $requete=new Requete();
            $requete->addCol(new StringCol(['name'=>'c', 'logicalOperator'=>'&&', 'comparisonOperator'=>'!=', 'value'=>"", 'strict'=>true ]));
            $requete->addCol(new IntCol(['name'=>'id_thotDatabase_database', 'logicalOperator'=>'&&', 'value'=>$record->getId() ]));
			
			$ObjFact=new ObjectFactory();
			$exemples=$ObjFact->buildObjectFromRequete('thotExemple',$requete);
			$nbr=count($exemples);
			
			$path=sys_get_temp_dir().'/ThotExport';
            mkdir($path);
            $archivePath=$path.'/'.(string)time();
            $zipArchive=new ZipArchive();
            $zipArchive->open($archivePath, ZipArchive::CREATE);
			
			
			//Header
			$buffer="NN".(string)$nbr.":";
			
			//Body
			for($a=0; $a<$nbr; $a++){
				$c=$exemples[$a]->getC();
				
				$codes=['o','B','d','e','a','m','n','t','x','u'];
		
				if(strlen($c)==1){
					//$zipArchive->addFile($exemples[$a]->getImage()->getPath());
					//$name=preg_replace('#.+/([0-9]+.[a-z]+)$#', "$1", $exemples[$a]->getImage()->getPath());
					//$buffer.="\n".(string)$exemples[$a]->getId()."#".$exemples[$a]->getLogin()."#".$name."#".(string)$this->getCode($c)."#";
					
					
					$n=16;
					$p=16;
					$image=imagecreatefrompng($exemples[$a]->getImage()->getPath());
					$x=imagesx($image);
					$y=imagesy($image);
					$default="0";
					
					for($i=0 ; $i<$n ; $i++)
						for($j=0 ; $j<$p ; $j++)
							($i<$x)&&($j<$y) ? $buffer.=(string)imagecolorat($image, $i, $j).":" : $default.":"; 
						
					$key=array_search($exemples[$a]->getC(), $codes);
					for($k=0 ; $k<10; $k++){
						$buffer.=($k==$key) ? (string)1 : (string)0;
						$buffer.=":"; 
					}
					
				}
			}

			$zipArchive->addFromString('db',$buffer);
			$zipArchive->close();
			
			header("Content-Type: application/zip; name=".$record->getNom()); 
            header("Content-Description: File Transfer");
            header("Content-Transfer-Encoding: binary"); 
            header("Content-Length:".filesize($archivePath)); 
            header('Content-Disposition: attachment; filename='.$record->getNom().'.zip;'); 
            header("Expires: 0"); 
            header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0"); 
            header("Pragma: no-cache"); 
            
			readfile($archivePath);  
            exit;
		}
		
		public function getCode($c){
			$codes=['a','z','e','r','t','y','u','i','o','p','q','s','d','f','g','h','j','k','l','m',
			'w','x','c','v','b', 'n', 'A', 'Z', 'E', 'R','T','Y','U','I','O','P','Q','S','D','F','G','H','J','K','L','M',
			'W','C','V','B','N','0','1','2','3','4','5','6','7','8','9'];
			return array_search($c, $codes);
		}
}
