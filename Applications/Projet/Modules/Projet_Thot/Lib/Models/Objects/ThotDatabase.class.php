<?php
/*
 * name: ThotDatabase
 * @description :  
 */
 
class ThotDatabase extends Objects{
    /*
        Attributs
    */
        protected $nom;
        protected $type;
        protected $nbr=0;
 
        /*
            Constantes
        */ 
    /*
        Méthodes générales
    */
        
        /*
            Getters
        */            
            public function getNom(){
				return $this->nom;
			}
            
            public function getType(){
                return $this->type;
            }
            
            public function getNbr(){
                return $this->nbr;
            }
        /*
            Setters
        */
           public function setNom($n){
				$this->nom=$n;
			}
            
            public function setType($t){
                $this->type=$t;
            }
            
            public function setNbr($n){
                $this->nbr=$n;
            }      
    /*
        Autres méthodes
    */
		public function incrNbr(){
			$this->nbr++;
		}
		
		public function getDatabaseOptions($value=null,$default=null){
			if(empty($value) && ( empty($default) || $default->getNom()=="" || $default->getValue()=="") ){
				$cache=new Cache();
				
				$tmp=$cache->getAPCCache('Projet_Thot_Database_Opt', (function($arg){
					$db=new ThotDatabase();
					return $db->getDatabaseOptionsDyn( null, $arg['default'] );
				}), ['default'=>$default]);
				return $tmp;
			}
			return $this->getDatabaseOptionsDyn($value, $default);
		}
		
		public function getDatabaseOptionsDyn($value=null,$default=null){//default de type database
			$catManager=new ThotDatabaseManager(PDOFactory::getMysqlConnexion());
			$catRequete=new Requete(array(['order'=>['nom']]));
			
			$records=$catManager->getList($catRequete);
			$objectFactory=new ObjectFactory();
			$cat=$objectFactory->buildMultiObject($records);
			if(isset($default))
				$cat=array_merge([$default],$cat);
			
			$options=array();
			foreach($cat as $rec){
				(isset($value) && $rec->getNom()==$value) ? $selected=true : $selected=false; 
				$options[]=new OptionField(array('value'=>(string)$rec->getId(), 'label'=>$rec->getNom(), 'selected'=>$selected));              
			}
			return $options;
		}
 }
