<?php
/*
 * 
 * name: Manager
 * @description :  Manager est la classe mère de tout les managers.
 * 
 * 
 */

class Manager{
    /*
        Attributs
    */
         protected $dao;
         protected $classeRecord;
         protected $table;
         
         protected $tables=array(); //liste de toutes les tables utilisées et des bases de données associées
         protected $databases=array(); //liste de toutes les bases utilisées
         
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        public function __construct($dao=null){
			if(empty($dao))
				$this->dao=PDOFactory::getMysqlConnexion();
			else
				$this->dao = $dao;
            
            $this->setClasse();
            $this->setClasseRecord();
            $this->setTable();
            $this->dao->exec('SET NAMES utf8');
            
            $this->getTables();
        }
        
        /*
            Getters
        */
            public function getClasseRecord(){
                return $this->classeRecord;
            }

            public function getTable(){
                return $this->table;
            }
            
            public function getTablesFrom($dataBase){
				$tables=array();
				$show=$this->dao->prepare('SHOW tables FROM '.$dataBase);
				$show->execute();
				while($table=$show->fetch(PDO::FETCH_ASSOC))
					$tables[]=$table;		
				return $tables;
			}
			
			public function getTables(){
				$cache=new Cache();
				if(empty($this->tables)){
					$buffer=$cache->getAPCCache('tables_and_bases',
												['obj'=>$this,'methode'=>'setTables'],
												null);
					$this->tables=unserialize($buffer);
				}
				return $this->tables;
			}
			
			public function getBaseFromTable($table){
				if(array_key_exists($table, $this->tables))
					return $this->tables[ $table ];
				else
					throw new Exception('cette table : '.$table.' n\'existe pas');
			}
        /*
            Setters
        */
            public function setClasse(){
                $this->classe=mb_substr(get_class($this), 0, strlen(get_class($this))-7);
            }
            
            public function setClasseRecord(){
                $this->classeRecord=$this->classe.'Record';
            }
            
            public function setTable($table=''){
				$table!='' ? $this->table=$table :  $this->table=strtolower($this->classe);
            }
            
            public function setDatabase(){
				 $this->databases=['scientiavulgaria', 'forum'];
			}
            
            public function setTables(){
				$tables=array();
				$this->setDatabase();
				
				for($a=0; $a<count($this->databases); $a++){
					$tmpTables=$this->getTablesFrom( $this->databases[$a] );
					for($b=0; $b<count($tmpTables); $b++){
						$tmpTable=each($tmpTables[$b])['value'];
						
						$tables[ $tmpTable ]=$this->databases[$a];
					}
				}
				$this->tables=$tables;
				return serialize($tables);
			}
    
    /*
        Autres méthodes
    */
		public function describeTable($database, $table){
			$info=array();
			$describe=$this->dao->prepare('DESCRIBE '.$database.'.'.$table.' ');
			$describe->execute();
			while($buffer=$describe->fetch(PDO::FETCH_ASSOC))
				$info[]=$buffer;		
			return $info;
		}
		
		protected function decodeString(&$data){
			if(!empty($data)){
				foreach($data as $key =>$element){
					if(is_string($element)){
						// on transforme les caractères echappés
						$eC=['\\\\', '\\\'', "\\'", '\\"', "\\\""];
						$ueC=['\\', "'", "'", '"', '"'];
						$data[$key]=str_replace($eC, $ueC, $data[$key]);
					}
				}
			}
		}
        /*
         * 
         * name: requeteProcess
         * @param : $requete, $info(null), $debut(null), $limite(null), $order(null), $classement(null), $classeAPI
         * Info ['int'=>['colonne'=>['0'=>value,'1'=>'operator']......],'string'=>['colonne'=>'value','strict'=>true|false], 'multiple'=>['value'] ]
         * @return : 
         *          
         */     
        
        protected function requeteProcess($requete, $requeteObj){
            $jointures=$requeteObj->getJointures();
            $cols= $requeteObj->getCols();$order= $requeteObj->getOrder();
            $limite= $requeteObj->getLimite(); $offset= $requeteObj->getOffset();

            if(!empty($jointures)){
                foreach($jointures as $jointure){
                    $requete.=$jointure->genRequete($this->table);
                }
                
            }
           
            if(!empty($cols)){
                $requete.=' WHERE ';
                foreach($cols as $col){
                    $requete.=$col->genRequete($this->table);
                }   
            }
            
            
            if(!empty($order) && !empty($order[0])){
                $requete.=' ORDER BY ';
                for($a=0; $a<count($order); $a++){
                    $requete.=$order[$a].',';
                }
                $requete=substr($requete, 0, strlen($requete)-1);
                $requete.=' '.$requeteObj->getAsc().' ';
            }else{
                $requete.=' ORDER BY '.$this->table.'.id ';
                $requete.=' '.$requeteObj->getAsc().' ';
            }
            
            
            
            if(isset($limite) && isset($offset)){
                $requete.='LIMIT '.$requeteObj->getLimite().' OFFSET  '.$requeteObj->getOffset().' ';   
            }	//echo '|'.$requete.'|';echo '<br/>';
           
            $pdo=$this->dao->prepare($requete);
        
            if(!empty($cols)){
                $values=array();
                foreach($cols as $col){
                    if(!($col instanceof MultipleCol)){
                        $values[]=$col->getValue();
                    }
                }
            }
            try{
				isset($values) ? $pdo->execute($values) : $pdo->execute();
			}catch(PDOException $e){
				$log=new Log();
				$log->log('Mysql', 'requeteProcess', $e->getMessage());
			}
            return $pdo;
        }
       
        /*
         * 
         * name: insert
         * @param : $donnees(array)
         * @return : $id(int)
         *          
         */ 
        public function insert($donnees){
            $attributs='';
            $values='';
            
            foreach($donnees as $attribut => $value){
                if(isset($value)){
                    $attributs.=(string)$attribut.',';
                    $values.=':'.(string)$attribut.',';
                }
            }
            $attributs=substr($attributs, 0, (strlen($attributs)-1)); 
            $values=substr($values, 0, (strlen($values)-1));

            $requete='INSERT INTO '.$this->table.' ('.$attributs.') VALUES ('.$values.')';
            $insert=$this->dao->prepare($requete);
            foreach($donnees as $attribut => $value){
                if(isset($value)){
                    if(is_string($value)){
                        $insert->bindValue(':'.$attribut.'', $value, PDO::PARAM_STR);
                    }else{
                        $insert->bindValue(':'.$attribut.'', $value, PDO::PARAM_INT);
                    } 
                }
            }

            if($insert->execute()){
				$id=(int)$this->dao->lastInsertId();
                $insert->closeCursor();

                return $id;
            }else{
                $insert->closeCursor();
                return false;
            }
        }
        
        /*
         * 
         * name: count
         * @param : Requete
         * @return : int
         *          
         */ 
        public function count($requeteObj){
            empty($requeteObj) ? $requeteObj=new Requete() : null;
            $requete='SELECT COUNT(*) AS nombre FROM '.$this->table.' ';
            
            $count=$this->requeteProcess($requete, $requeteObj);
            $data=$count->fetch();
            $count->closeCursor();
            
            return $data['nombre'];
        }
        
        /*
         * 
         * name: delete
         * @param : $id(int)
         * @return : bool
         *          
         */ 
        public function delete($id){
            if(!is_numeric($id)){
                throw new Exception('L\'id n\'est pas un nombre');
            }
            $delete=$this->dao->prepare('DELETE FROM '.$this->table.' WHERE id=:id');
            $delete->bindValue(':id', $id, PDO::PARAM_INT);
            $delete->execute();
           
            if($delete->execute()){
                $delete->closeCursor();
                return true;
            }else{
                $delete->closeCursor();
                return false;
            }
        }
        public function deleteList($requeteObj){
            $requete='DELETE FROM '.$this->table;
            $deleteList=$this->requeteProcess($requete, $requeteObj);
            $deleteList->execute();
           
            if($deleteList->execute()){
                $deleteList->closeCursor();
                return true;
            }else{
                $deleteList->closeCursor();
                return false;
            }
        }
        
        /*
         * 
         * name: get
         * @param : $id(int)
         * @return : mixte(record|bool)
         *          
         */ 
        public function get($id, $attributs='*'){
            if(!is_numeric($id)){
                throw new Exception('L\'id n\'est pas un nombre');
            }
            $get=$this->dao->prepare('SELECT  '.$attributs.' FROM '.$this->table.' WHERE id=:id');
            $get->bindValue(':id', $id, PDO::PARAM_INT);
            $get->execute();
            
            while($data=$get->fetch()){
				$this->decodeString($data);
                $rec= new $this->classeRecord($data);
            }
            $get->closeCursor();
            
            if(isset($rec)){
                return $rec;
            }else{
                return false;
            }
        }       
        
        /*
         * 
         * name: getList
         * @param : Requete
         * @return : array records
         *          
         */ 
        public function getList($requeteObj=null, $attributs=null, $onlyData=false){
            empty($requeteObj) ? $requeteObj=new Requete() : null;
			empty($attributs) ? $attributs='*' :null; 
			$requete='SELECT '.$attributs.' FROM '.$this->table.' ';
            $records=array();
            $dataAr=array();
            $getList=$this->requeteProcess($requete, $requeteObj);
            while($data=$getList->fetch(PDO::FETCH_ASSOC)){
				$this->decodeString($data);
				if($onlyData)
					$dataAr[]=$data;
				else
					$records[]= new $this->classeRecord($data);
            }
            $getList->closeCursor();
            
            if($onlyData)
				return $dataAr;
			else
				return $records;
        }
        
        /*
         * 
         * name: update
         * @param : $donnees
         * @return : bool
         *          
         */ 
        public function update($donnees){
            if(empty($donnees) || empty($donnees['id']))
                return false;
                
            $modifications='';
            foreach($donnees as $attribut => $value){
                if($attribut!='id'){
                    $modifications.=$attribut.'=:'.$attribut.', ';
                }
                $modifications=substr($modifications, 0, strlen($modifications)-1);
            }

            $requete='UPDATE '.$this->table.' SET '.$modifications.' WHERE id=:id';
            $update=$this->dao->prepare($requete);
            $update->bindValue(':id', $donnees['id'], PDO::PARAM_INT);
            
            
            foreach($donnees as $attribut => $value){
                if($attribut!='id'){
                    if(is_string($value)){
                        $update->bindValue(':'.$attribut.'', $value, PDO::PARAM_STR);
                    }elseif(is_numeric($value)){
                        $update->bindValue(':'.$attribut.'', (int)$value, PDO::PARAM_INT);
                    }else{//dans le cas d'un champs vide
                        $update->bindValue(':'.$attribut.'', 0, PDO::PARAM_INT);
                    }
                }
            }
                         
            if($update->execute()){
                $update->closeCursor();
                return true;
            }else{
                $update->closeCursor();
                return false;
            }
        }
        
        /*
         * 
         * name: update
         * @param : $objet
         * @return : mixte(int|bool)
         *          
         */ 
        public function save($objet){
            if ($objet->isValid()){
                return $objet->isNew() ? $this->insert($objet->obj2ar()) : $this->update($objet->obj2ar());
            }else{
                return false;
            }
        }
        
        
}
