<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

abstract class FileController extends MiddleController{
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
        public function executeDownload(){
			$id=null;
			$name='noname';
            $this->httpRequette->getExists('id') ? $id=$this->httpRequette->getData('id'): null;
            $this->httpRequette->getExists('name') ? $name=$this->httpRequette->getData('name'): null;
            empty($id) ?  $this->httpReponse->redirect404() : null;
            
            $manager=$this->managers->getManagerOf($this->getModule());
            $record=$manager->get($id);
            empty($record) ?  $this->httpReponse->redirect404() : null;
            $objFac=new ObjectFactory();
            $obj=$objFac->buildObject($record);
            
            !is_file($obj->getPath()) ? $this->httpReponse->redirect404() : null;
            
            $name.='.'.$obj->getExt();
            header("Content-Type: application/force-download; name=".$name); 
            header("Content-Description: File Transfer");
            header("Content-Transfer-Encoding: binary"); 
            header("Content-Length:".$obj->getSize()); 
            header('Content-Disposition: attachment; filename='.$name.';'); 
            header("Expires: 0"); 
            header("Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0"); 
            header("Pragma: no-cache"); 
            
			readfile($obj->getPath());  
            exit;
        }
        
        public function executeInsert($dataServ=null){
            $dir=$dataServ['dir'];
            $redirect=$dataServ['redirect'];
            $class=substr(get_class($this), 0 , (strlen(get_class($this))-10));
            $allowAllTypes= array_key_exists('allowAllTypes', $dataServ) ? $dataServ['allowAllTypes'] : false;
             
            if(!is_dir($dir)){
                throw new RuntimeException('Is not a valid dir'.$dir);
            }
            
            //Si remote upload
			if(array_key_exists('url', $dataServ)){
					if( substr($dataServ['url'],0 ,43 )=='http://scientiavulgaria.org/Files/Tmpfiles/'){
						$objFact=new ObjectFactory();
						$requete=new Requete();
						$path='../Web/Files/Tmpfiles/'.substr($dataServ['url'],43);
						$requete->addCol( new StringCol(['name'=>'path', 'value'=>$path, 'strict'=>true]) );
						$tmpfile=$objFact->buildObjectFromRequete('Tmpfile', $requete);
						if(!empty($tmpfile)){
							$tmpfile=$tmpfile[0];
							$f=['size'=>$tmpfile->getSize(),
								'error'=>0,
								'type'=>$tmpfile->getType()
							]; 
						}else{
							$f=['size'=>-1,
								'error'=>-1,
								'type'=>"x-empty"];
						}
					}else{
						$tmpRessource=tmpfile();
						$curl=curl_init($dataServ['url']);

				
						//Temps max d'execution de Curl
						curl_setopt($curl, CURLOPT_TIMEOUT, 36000);
						//User agent à spécifier
						//curl_setopt($curl, CURLOPT_USERAGENT, );
						//Fichier temporaire
						curl_setopt($curl, CURLOPT_FILE, $tmpRessource);
						//Vitesse de transfert minimale, 100Ko/s
						curl_setopt($curl, CURLOPT_LOW_SPEED_LIMIT, 102400);
						//Temps pendant lequel la vitesse peut être inférieur la vitesse minimal, 10min
						curl_setopt($curl, CURLOPT_LOW_SPEED_LIMIT, 600);
						
						$flagCurl=curl_exec($curl);
						curl_close($curl);
						
						$tmpUri=stream_get_meta_data($tmpRessource)['uri'];

						$f=['size'=>filesize($tmpUri),
							'error'=>0,
							'tmp_name'=>$tmpUri,
							'type'=>trim(explode(':', explode(';', exec('file -i '.$tmpUri) )[0] )[1]) //var/www/scientiavulgaria/testetstest: video/x-flv; charset=binary
						];  
				}
			}elseif(array_key_exists('file', $dataServ)){
				$f=$dataServ['file'];
			}else{
				$this->app->getUser()->setFlash('File or url invalid');
                if($redirect)
                    $this->httpReponse->redirect('insert-index');
                else
                    return false;
			}
				
			if( $allowAllTypes ){
			   if(in_array($f['type'], $class::getFobiddenTypes()) ){
					$this->app->getUser()->setFlash($this->config['flash']['forbidden-type'].' :'.$f['type']);
					if($redirect)
						$this->httpReponse->redirect('insert-index');
					else
						return false;
				}
			}elseif( !in_array($f['type'], $class::getAllowedTypes()) ){
				$this->app->getUser()->setFlash($this->config['flash']['invalid-type'].' :'.$f['type']);
                if($redirect)
                    $this->httpReponse->redirect('insert-index');
                else
                    return false;
            }
           
			$path=$dir.'/'.String::uniqStr().'.'.$class::type2Ext($f['type']);

			//On vérifie l'inoffensivité du fichier
			if(empty($tmpfile)){
				$scanResult=exec('clamscan '.$f['tmp_name'].'  2>&1', $output);
				$idInfected=array_search('----------- SCAN SUMMARY -----------', $output)+5;
				
				if ( (count($output)<$idInfected) || $output[$idInfected]!='Infected files: 0' ){
					$log=new Log();
					$log->log('File', 'infected_files', $this->app->getUser()->getLogin().'|=|infected file detected', Log::GRAN_MONTH);
					$this->app->getUser()->setFlash($this->config['flash']['infected-type']);
					if($redirect)
						$this->httpReponse->redirect('insert-index');
					else
						return false;
				}
			}
			
			$flagCp=true;
			if(!empty($tmpfile)){
				$flagCp=copy($tmpfile->getPath(), $path);
            }elseif(array_key_exists('file', $dataServ)){
				echo is_uploaded_file($f['tmp_name'])	;
				
				if(!is_uploaded_file($f['tmp_name'])){
					$flagCp=copy($f['tmp_name'], $path);
				}elseif( ( $f['error']!=0 || !move_uploaded_file($f['tmp_name'], $path) ) )
					$flagCp=false;
				
			}elseif(array_key_exists('url', $dataServ))
				(!$flagCurl || !rename($f['tmp_name'], $path)) ? $flagCp=false : null;

				
			if(!$flagCp){
				if($redirect)
					$this->httpReponse->redirect('insert-index');
				else
					return false;
			}
			
            
            $file=new File(array(
                'path'=>$path,
                'type'=>$f['type'],
                'size'=>$f['size'],
                'ext'=>$class::type2Ext($f['type']),
                'login'=>$this->app->getUser()->getLogin()
                ));
            $fileProcess=new FileProcess($file);
            $file->setMd5($fileProcess->hashFile('md5'));
            $file->setSha512($fileProcess->hashFile('sha512'));
            return $file;
        }
}
