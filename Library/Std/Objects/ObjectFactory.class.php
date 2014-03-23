	<?php
/*
 * name: ObjectFactory
 * @description :  classe usine gérant les objets
 */

class ObjectFactory{
    /*
        Attributs
    */
        protected $managers;
        
        protected $i=0;
        protected $requetes=array();
        protected $whereNeeded=array();
        protected $whereNeededForMulti=array();
        protected $recursiveCallStack=array();
        protected $multiRequetes=array();
        protected $mainClass='';
        
        protected $objects=array();
        protected $multiObjectsStructure=array();
        protected $multiObjects=array();
        
        /*
            Constantes
        */
            const REGEXP='#^id_([a-zA-Z]+)(?:_([a-zA-Z]+))?(_ar)?$#';//$1=table $2=nom optionnel $3=_ar ou null
            const REGEXP2='#^([a-zA-Z]+)_(?:[a-z]{2}$)#'; //pour les attributs dependant des langues ex :nom_fr
    /*
        Méthodes générales
    */
        public function __construct($app='frontend'){
            $this->setManagers($app);
        }
 
        /*
            Getters
        */
            	public function getStructure($className, $parentClassName=''){
					$cache=new Cache();
					$tmp=$cache->getAPCCache('structure_'.$className.'_'.$parentClassName.'_'.$this->mainClass,
												['obj'=>$this,'methode'=>'buildStructure'],
												[$className,$parentClassName]);
					$this->multiObjectsStructure=$tmp['multiObjectsStructure'];
					return $tmp['structure'];
				}
				
				public function getRequetes($structure){
					$cache=new Cache();
					$tmp=$cache->getAPCCache('requetes_'.$structure->getClassName(),
												['obj'=>$this,'methode'=>'createRequetes'],
												$structure);
					
					$this->requetes=$tmp[0];
					$this->whereNeeded=$tmp[1];
					$this->multiRequetes=$tmp[2];
					$this->whereNeededForMulti=$tmp[3];
				}
        /*
            Setters
        */
            public function setManagers($app){
                $this->managers=new Managers(PDOFactory::getMysqlConnexion($app));
            }
    
    /*
        Autres méthodes
    */
		public function buildStructure(array $data){
			//Initialisation 
			$className=ucfirst($data[0])	;
			$parentClassName=ucfirst($data[1]);
			
			$recordClassName=$className.'Record';
			$record=new $recordClassName;
			$reflex=new ReflectionClass($record);
			
			//On met à jour la pile 
			if(array_key_exists($className, $this->recursiveCallStack))
				$this->recursiveCallStack[$className]++;
			else
				$this->recursiveCallStack[$className]=0;
			
			$layer=$this->recursiveCallStack[$className];
			$mainStructure=new ObjectStructure(['className'=>$className, 'layer'=>$layer]);
		
			foreach($reflex->getProperties() as $attribut){
				$att=$attribut->getName();
						
				//Dans le cas où l'attribut fait référence à un autre objet
				if(preg_match(self::REGEXP, $att, $match)){
					if($parentClassName!=$className){
						isset($match[2]) ? $atr=$match[2] : $atr=$match[1]; 
						$tmpStructure=$this->getStructure( ucfirst($match[1]), $className);
						//On différencie le cas des objets multiples ou non 
						if(empty($match[3])){
							$mainStructure->addChild($this->i, $att, $atr, $tmpStructure);
						}else{
							$table=strtolower($match[1]);
							if(array_key_exists($table, $this->multiRequetes))
								 $this->multiRequetes[ $match ]['attribut']=$atr;
							else{
								$mainStructure->addMultiChilds($table, $att, $this->i, $atr, $tmpStructure);
								$this->multiObjectsStructure[$table]=$tmpStructure;
								
								//Pour recueillir les objets à recup uniquement pr le record
								$mainStructure->addAttribute($this->i, $att, $match[1], true);
								
							}
						}
					}
				}elseif(preg_match(self::REGEXP2, $att, $match)){
					$structures[ $layer ][ $this->i ]=$mainStructure;
					$mainStructure->addAttribute($this->i, $att, $match[1]);
				}elseif($att!='erreurs'){
					$structures[ $layer ][ $this->i ]=$mainStructure;
					$mainStructure->addAttribute($this->i, $att, $att);
				}

				$this->i++;
				
			}
			return ['structure'=>$mainStructure, 'multiObjectsStructure'=>$this->multiObjectsStructure];
		}
		

		/*
		 *	Construction des requêtes 
		 */
		public function buildRequetesFromAttributes($className, $attributes, $layer, &$requetes){
			$manager=new Manager();
			
			empty($requetes[ $layer ]) ? $requetes[ $layer ]=['colsNeeded'=>'' , 'requete'=> new Requete] : null;
			foreach($attributes AS $key => $element){
					$table=strtolower($className);
					
					$requetes[ $layer ][ 'colsNeeded' ].=$manager->getBaseFromTable($table).'.'.
														$table.'.'.strtolower($element['attRecordName']).
														' AS "'.$key.'" , ';
			}
		}
		
		public function buildRequetesFromChilds($childs, $parentLayer, $parentTable, &$requetes, &$whereNeeded){
			$manager=new Manager();
			foreach($childs as $key => $child){
				$childStructure=$child['structure'];
				$layer=$childStructure->getLayer();
				$className=$childStructure->getClassName();
				$table=strtolower($className);
				
				empty($requetes[ $layer ]) ? $requetes[ $layer ]=['colsNeeded'=>'' , 'requete'=> new Requete] : null;
				$att=strtolower($child['attRecordName']);
				
				$layer>0 ? $tmpLayer=$layer-1 : $tmpLayer=$layer;
				$requetes[ $tmpLayer ][ 'colsNeeded' ].=$manager->getBaseFromTable($parentTable).'.'.
														$parentTable.'.'.$att.' AS "'.$key.'" , ';
				
				if($layer==$parentLayer){
					$requetes[ $layer ][ 'requete' ]->addJointure( new Jointure(['tableJ'=>$table,
																					'tableO'=>$parentTable,
																					'databaseJ'=>$manager->getBaseFromTable($table),
																					'databaseO'=>$manager->getBaseFromTable($parentTable),
																					'colJ'=>'id',
																					'colO'=>$att,
																					'type'=>'left']));
				}else{
					$requetes[ $layer ][ 'requete' ]->addJointure( new Jointure(['tableJ'=>$table,
																					'tableO'=>$parentTable,
																					'databaseJ'=>$manager->getBaseFromTable($table),
																					'databaseO'=>$manager->getBaseFromTable($parentTable),
																					'colJ'=>'id',
																					'colO'=>$att,
																					'type'=>'right']));
					$requetes[ $layer ][ 'requete' ]->addCol( new MultipleCol(['table'=>$table,
																					'name'=>'id',
																					'logicalOperator'=>'OR']));
					
					$requetes[ $layer ]['index']=['id'=>'', 'value'=>array(),
																'table'=>$table,
																'layer'=>$layer,
																'col'=>count($requetes[ $layer ][ 'requete' ]->getCols())-1 ];
													
					$whereNeeded[ $key ]= ['layer'=>$layer, 'col'=>count($requetes[ $layer ][ 'requete' ]->getCols())-1];
				}	
				
				
				//Attributs
				$this->buildRequetesFromAttributes( $childStructure->getClassName(), $childStructure->getAttributes(), $childStructure->getLayer(), $requetes );
				//Childs
				$this->buildRequetesFromChilds( $childStructure->getChilds(), $childStructure->getLayer(), $table, $requetes, $whereNeeded);
				//MultiChilds
				$this->buildRequetesFromMultiChilds( $childStructure->getMultiChilds(), $table);
			}
		}
		
		public function buildRequetesFromMultiChilds($multiChilds, $parentTable){
			$manager=new Manager();
			
			foreach($multiChilds as $table => $multiChild){
				$mutliChildStructure=$multiChild['structure'];
				$layer=$mutliChildStructure->getLayer();
				$className=$mutliChildStructure->getClassName();
				
				//On ajoute l'attribut aux requetes
				empty($this->requetes[ $layer ]) ? $requetes[ $layer ]=['colsNeeded'=>'' , 'requete'=> new Requete] : null;
				$this->requetes[ $layer ][ 'colsNeeded' ].=$manager->getBaseFromTable($parentTable).'.'.$parentTable.'.'.
															strtolower($multiChild['attRecordName']).' AS "'.
															$multiChild['recordId'].'" , ';
				
				$this->requetes[ $layer ][ 'requete' ]->addJointure( new Jointure(['tableJ'=>$table,
																					'tableO'=>$parentTable,
																					'databaseJ'=>$manager->getBaseFromTable($table),
																					'databaseO'=>$manager->getBaseFromTable($parentTable),
																					'colJ'=>'id',
																					'colO'=>'id',
																					'type'=>'left']));
				
				//On met à jour les multiRequetes
				if(!array_key_exists($table, $this->multiRequetes)){
					$this->buildRequetes($mutliChildStructure, $this->multiRequetes[$table], $this->whereNeededForMulti[$table]);
					//On ajoute la close where
					$this->multiRequetes[$table][0]['requete']->addCol( new MultipleCol(['name'=>'id']));
					
					$this->whereNeededForMulti[ $multiChild['recordId'] ]=[ 'table'=>$table, 'layer'=>$layer, 'col'=>count($this->multiRequetes[$table][0]['requete']->getCols())-1];

				}
				
				
			}
		}
		
		public function buildIndex($structureObject, &$requetes){
			if( !($structureObject instanceof ObjectStructure))
				return null;
			
			for($a=1; $a<count($requetes); $a++){
				$index=&$requetes[$a]['index'];
				$childs=$structureObject->getChilds();
				foreach($childs as $id => $child){
					if( $child['structure']->getLayer()==$index['layer'] && strtolower($child['structure']->getClassName())==$index['table'] ){
						foreach($child['structure']->getAttributes() as $key => $element){
							if($element['attRecordName']=='id')
								$index['id']=$key;
						}
					}
					//On traitel es enfants des enfants 
					$this->buildIndex($child['structure'], $requetes);
				}
			}
		}

		public function buildRequetes($structure, &$requetes='null', &$whereNeeded='null'){
			$requetes=='null' ? $requetes=&$this->requetes : null;
			$whereNeeded=='null' ? $whereNeeded=&$this->whereNeeded : null;
			
			if( is_object($structure) ){
				$table=strtolower($structure->getClassName());
				//Attributs
				$this->buildRequetesFromAttributes( $structure->getClassName(), $structure->getAttributes(), $structure->getLayer(), $requetes );
				//Childs
				$this->buildRequetesFromChilds( $structure->getChilds(), $structure->getLayer(), $table, $requetes, $whereNeeded);
				
				$this->buildIndex($structure, $this->requetes);
				
				//MultiChilds
				$this->buildRequetesFromMultiChilds( $structure->getMultiChilds(), $table);
			}
		}
		
		public function updateWhereNeededForMulti(){
			foreach($this->whereNeededForMulti as $key => $element){
				if(is_string($key) && isset($element)){
					unset($this->whereNeededForMulti[$key]);
					foreach($element as $id=>$el){
						$this->whereNeededForMulti[$id]=$el;
						$this->whereNeededForMulti[$id]['table']=$key;
					}
				}
			}
		}
		
		public function createRequetes($structure){
			$this->buildRequetes($structure);
			//Aux requetes simples
			for($a=0; $a<count($this->requetes); $a++){
				if($this->requetes[$a]['colsNeeded']!='')
					$this->requetes[$a]['colsNeeded']=substr($this->requetes[$a]['colsNeeded'], 0, strlen($this->requetes[$a]['colsNeeded'])-2);
			}
			//Aux requêtes multiples
			foreach($this->multiRequetes as $table => &$requetes){
				for($a=0; $a<count($requetes); $a++){
					if($requetes[$a]['colsNeeded']!='')
						$requetes[$a]['colsNeeded']=substr($requetes[$a]['colsNeeded'], 0, strlen($requetes[$a]['colsNeeded'])-2);
				}
			}

			$this->updateWhereNeededForMulti();
			
			return [$this->requetes, 
						$this->whereNeeded,
						$this->multiRequetes,
						$this->whereNeededForMulti];
		}

		/*
		 * Gestion des données SQL
		 */
		public function hydrateAttributes($object, &$structureObject, &$data){
			if(empty($data))
				return null;
			
			$attributes=$structureObject->getAttributes();
			foreach($attributes as $recordId=>$element){
				if(array_key_exists($recordId, $data) && !empty($data[$recordId])){
					if(!$element['recordOnly'])
						$object->$element['setter']( $data[$recordId] );
					$object->getRecord()->$element['recordSetter']( $data[$recordId] );
				}
			}
			
			
			//On hydrate les enfants
			$childs=$structureObject->getChilds();
			foreach($childs as $recordId => $child){
				if(array_key_exists($recordId, $data) && !empty($data[$recordId]))
					$object->getRecord()->$child['recordSetter']( $data[$recordId] );
				$this->hydrateAttributes( $object->$child['getter'](), $child['structure'], $data);
			}
			
			//On traite les enfants multiples
			$multiChilds=$structureObject->getMultiChilds();
			foreach($multiChilds as $recordId => $multiChild){
				( array_key_exists($recordId, $data) && !empty($data[$recordId]) ) ? $object->getRecord()->$multiChild['recordSetter']( $data[$recordId] ) : null;
			}
		}
		
		public function hydrateMultiObject($object, &$structureObject){
			$multiChilds=$structureObject->getMultiChilds();
			foreach($multiChilds as $recordId => $multiChild){
				$idAr=explode(',', $object->getRecord()->$multiChild['recordGetter']());
				$table=strtolower( $multiChild['structure']->getClassName() );

				for($a=0; $a<count($idAr); $a++){
					$id=$idAr[$a];
					if(array_key_exists($table,$this->multiObjects) && array_key_exists( $id, $this->multiObjects[$table]))
						$object->$multiChild['setter']( $this->multiObjects[$table][$id] );
				}
			}
			
			$childs=$structureObject->getChilds();
			foreach($childs as $key => $child){;
				$this->hydrateMultiObject( $object->$child['getter'](), $child['structure']);
			}
		}
		
		public function hydrateMultiObjects($objects, &$structureObject){
			for($a=0; $a<count($objects); $a++){
				$this->hydrateMultiObject($objects[$a], $structureObject);
			}
		}
		
		public function updateIndex(&$requetes, $layer){
			if($layer==0)
				return null;
			
			$index=&$requetes[$layer]['index'];

			$buffer=$requetes[$layer]['requete']->getCols()[ $index['col'] ]->getValue();
			$index['value']=explode(',', $buffer);
		}
		
		public function updateCols($data){
			if(empty($data))
				return null;
				
			foreach($data as $recordId=>$value){
				//Requêtes simples
				if(array_key_exists($recordId, $this->whereNeeded)){
					$layer=$this->whereNeeded[$recordId]['layer'];
					$idCol=$this->whereNeeded[$recordId]['col'];
					
					$col=&$this->requetes[$layer]['requete']->getCols()[$idCol];
					$flag=$col->getValue();
					empty($flag) ? null : $value=$col->getValue().','.$value;
					$col->setValue( $value );
				}
				//Requêtes multiples
				if(array_key_exists($recordId, $this->whereNeededForMulti)){	
					$table=$this->whereNeededForMulti[$recordId]['table'];
					$layer=$this->whereNeededForMulti[$recordId]['layer'];
					$idCol=$this->whereNeededForMulti[$recordId]['col'];
					
					$col=&$this->multiRequetes[$table][$layer]['requete']->getCols()[$idCol];
					$flag=$col->getValue();
					empty($flag) ? null : $value=$col->getValue().','.$value;
					$col->setValue( $value );
				}
			}
		}
		
		public function initDefaultObject(&$objectStructure){
			$class=$objectStructure->getClassName();
			$classRecord=$class.'Record';
			$object=new $class;
			$object->setRecord( new $classRecord);

			//On initialise ses enfants
			foreach( $objectStructure->getChilds() as $recordId => $child){
				$tmpObject=$this->initDefaultObject( $child['structure'] );
				$object->$child['setter']($tmpObject);
			}
			
			return $object;
		}

		public function initObjects(&$objects, &$structureObject, $i){
			$defaultObject=$this->initDefaultObject($structureObject);
			for($a=0; $a<$i; $a++){
				$objects[$a]=clone $defaultObject;
			}
		}
		
		public function indexById(&$objects, $i=-1){
			$tmpObjects=$objects;
			$objects=array();
			$i==-1 ? $i=count($tmpObjects) : null;
			for($a=0; $a<$i; $a++){
				$objects[ $tmpObjects[$a]->getRecord()->getId() ]=$tmpObjects[$a];
			}
			unset($tmpObjects);
		}
		
		//Order tableau contenant l'ordre souhaité des value de key
		public function indexArray($key, $order, $array){
			if(empty($key) || empty($order) || empty($array))
				return array();

			for($a=0; $a<count($array); $a++){
				$needle=$array[$a][ $key ];
				$position=array_search($needle, $order);
				if( $position!==false )
					$newArray[$position]=$array[ $a ];	
			}
			return $newArray;
		}
		
		public function hydrateObjects(&$requetes, &$objects, &$structureObject, $index=false){
			$manager=$this->managers->getManagerOf( $structureObject->getClassName() );
			$i=0;
			
			//Requetes simples
			for($a=0; $a<count($requetes); $a++){
				
				$tmpData=$manager->getList($requetes[$a]['requete'], $requetes[$a]['colsNeeded'], true);
				$i=count($tmpData);
				if($i==0)
					return false;
					
				//On met à jour l'index du tableau
				$this->updateIndex($requetes, $a);
				if($a>0){	
					$index=$requetes[$a]['index'];
					$tmpData=$this->indexArray($index['id'], $index['value'], $tmpData);
				}	
					
					
				//Init des objets
				empty($objects) ? $this->initObjects($objects, $structureObject, $i) : null;				

				
				
				for($b=0; $b<$i; $b++){
					$data=&$tmpData[$b];
					$object=&$objects[$b];
					
					$this->updateCols($data);
					$this->hydrateAttributes($object, $structureObject, $data);
				}
			}
		}
		
		public function buildMultiObjects(){
			foreach($this->multiRequetes as $table => &$tmpRequetes){
				$this->multiObjects[$table]=array();
				$this->hydrateObjects($tmpRequetes, $this->multiObjects[$table], $this->multiObjectsStructure[$table] );
				$this->indexById( $this->multiObjects[$table]);
			}
		}
		
		public function buildObjectFromRequete($class, $requete=null){
			$class=ucfirst($class);
			$this->mainClass=$class;
			$structureObject=$this->getStructure($class);
			$this->getRequetes($structureObject);

			empty($requete) ?  null : $this->requetes[ 0 ]['requete']->mergeWith( $requete );
			
			$this->hydrateObjects($this->requetes, $this->objects, $structureObject);
			$this->buildMultiObjects();
			$this->hydrateMultiObjects($this->objects, $structureObject);
			
			return $this->objects;
		}
    
    
    
    
    
		/*
		 * Deprecated function
		 */
    
    
    
    
    
    
		//pr compatibilité ascendante
        public function buildObject(Record $record){
			/*$t=microtime(true);
			/*echo'<pre>';
					//	$this->buildObjectFromRequete(new Requete, 'Software');
					//$this->getRequetes( $this->getStructure('Film' ));
			print_r(
				//		$this->multiObjectsStructure
		//	$this->getStructure('Film' )
				//$this->multiRequetes
			//	$this->requetes
				$this->buildObjectFromRequete('Software')
			)
			
			;
			echo '<hr/>';
			//	print_r($this->getStructure('Film' ));
			* */
			/*echo'<pre>';
			$this->buildObjectFromRequete('Software');
			//print_r($this->objects);
			$T=microtime(true);
			echo $T-$t;
			exit;*/
           
           
            $recordClass=get_class($record);
            $objectClass=substr($recordClass, 0, strlen($recordClass)-6);
            $reflex=new ReflectionClass($record);
            $donnees=array('record'=>$record);
            
            //On remplit donnees
            foreach($reflex->getProperties() as $attribut){
                $att=$attribut->getName();
                $getter='get'.ucfirst($att);
                
                //Dans le cas où il faut construire d'autres objets
                if(preg_match(self::REGEXP, $att, $match)){
					//Cas particulier pour l'utilisitaeur utiliser dans différente base
					if($match[1]=='user'){
						$managers=new Managers(PDOFactory::getMysqlConnexion());
						$manager=$managers->getManagerOf($match[1]);
					}else{
						$manager=$this->managers->getManagerOf($match[1]);
					}
					
                    if(isset($match[2])){ $atr=$match[2]; }else{ $atr=$match[1]; }
                    
                    if(isset($match[3])){
                        $requeteObj=new Requete();
                        $requeteObj->addCol(new MultipleCol(array('name'=>'id', 'value'=>$record->$getter())));
                        $recAr=$manager->getList($requeteObj);
                        
                        $objAr=$this->buildMultiObject($recAr);
                        $donnees[$atr]=$objAr;
                    }else{
                        $value=$record->$getter();
                        if(isset($value) && $value>0){
                            $recSc=$manager->get((int)$record->$getter());
                            !$recSc ? null : $donnees[$atr]=$this->buildObject($recSc) ;	
                        }
                    }
                }elseif($att!='id'){
                    $donnees[$att]=$record->$getter();
                }
            }
            return new $objectClass($donnees);
        }
 
        public function buildMultiObject(Array $records){
			/*if( $records[0 ] instanceof EbookRecord){
				$t=microtime(true);
				$tm=memory_get_usage();
				$this->buildMultiObject2($records);
				$T=microtime(true)-$t;
				$Tm=memory_get_usage()-$tm;
				
				$e=microtime(true);
				$em=memory_get_usage();
				$this->buildObjectFromRequete('Software');
				$E=microtime(true)-$e;
				$Em=memory_get_usage()-$em;
				
				echo '<h4>Execution time : </h4>last='.$T.'s and new='.$E.'s<br/>
											diff(last-min)='.(string)($T-$E).'s and quotien=(last/min)='.(string)($T/$E).'';
				echo '<h4>Memory usage : </h4>last='.$Tm.'o and new='.$Em.'o<br/>
											diff(last-min)='.(string)($Tm-$Em).'o and quotien=(last/min)='.(string)($Tm/$Em).'';
				exit;
			}*/
             return $this->buildMultiObject2($records);
        }
        
        public function buildMultiObject2(Array $records){
            $objs=array();
            for($a=0; $a<count($records); $a++){
                $objs[]=$this->buildObject($records[$a]);
            }
            
            return $objs;
        }
    
        //Attention tts les record sont des instances de la même classe, instable.....
        public function buildMultiObject1(Array $records){
            $recordClass=get_class($records[0]);
            $objectClass=substr($recordClass, 0, strlen($recordClass)-6);
            $reflex=new ReflectionClass($recordClass);
            $manager=$this->managers->getManagerOf($objectClass);
            $returns=array();  $objs=array(); $attTable=array();
            
            /*On construit le tableau des attributs provenant d'autres tables
             * 1/3 du temps d'éxecution
             */
            foreach($reflex->getProperties() as $attribut){
                $att=$attribut->getName();
                $getter='get'.ucfirst($att);
                if(preg_match(self::REGEXP, $att, $matchs)){
                    $attTable[$matchs[0]]=$matchs[1];

                    $value='';
                    for($a=0; $a<count($records); $a++){
                        if($records[$a]->$getter()>0){
                            $value.=','.$records[$a]->$getter();
                        }
                    }
                    if($value!=''){
                        $value=substr($value, 1);
                        if(isset($requetes[$matchs[1]])){
                            $requetes[$matchs[1]]->addCol(new MultipleCol(array('name'=>'id', 'value'=>$value, 'logicalOperator'=>'OR')));
                        }else{
                            $req=new Requete();
                            $req->addCol(new MultipleCol(array('name'=>'id', 'value'=>$value)));
                            $requetes[$matchs[1]]=$req;
                        }
                    }
                }
            }
            
            //Dans le cas où des requettes entre plusieurs tables sont nécessaires
            
            if(!empty($requetes)){
                //echo microtime(true);echo '<br/>';
                foreach($requetes as $key =>$requete){
                    $manager=$this->managers->getManagerOf($key);
                    $tmpRecords=$manager->getList($requete);
                    if(!empty($tmpRecords)){
                        $objs[$key]=$this->buildMultiObject1($tmpRecords);
                    }
                    
                }
            }
            
            //on classe les objs par id plutot que de les reparcourir à chaque fois
            $objIndex=array();
            foreach($objs as $key =>$element){
                for($a=0; $a<count($element); $a++){
                    $tmpId=$element[$a]->getRecord()->getId();
                    $objIndex[$key][$tmpId]=$element[$a];
                }
            }
            unset($obj);
            
            //On construit les objets on classe les obj par id
            for($a=0; $a<count($records); $a++){
                $data=['record'=>$records[$a]];
                foreach($reflex->getProperties() as $attribut){
                    $att=$attribut->getName();
                    $getter='get'.ucfirst($att);
                    if(in_array($att, $attTable)){
                        $data[$att]=$objIndex[$attTable[$att]][$records[$a]->$getter()];
                    }else{
                        $data[$att]=$records[$a]->$getter();
                    }
                    
                }
                $returns[]=new $objectClass($data);
            }
            return $returns;
        }
}
