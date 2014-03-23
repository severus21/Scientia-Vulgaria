<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class Requete{
    /*
        Attributs
    */
        protected $cols=array();
        protected $limite;
        protected $offset;
        protected $order=array();
        protected $asc=''; 
        protected $jointures=array();
        protected $tableAlreadyJoined=array();
        /*
            Constantes
        */
        
    /*
        Méthodes générales
    */
        public function __construct(array $donnees = array()){
            if (!empty($donnees)){
                $this->hydrate($donnees);
            }
        }
        
        public function hydrate(array $donnees){
            foreach ($donnees as $attribut => $valeur){
                $methode = 'set'.ucfirst($attribut);
                
                if (is_callable(array($this, $methode))){
                    $this->$methode($valeur);
                }
            }
        }
        /*
            Getters
        */
            public function getAsc(){
                return $this->asc;
            }
            
            public function getLimite(){
                return $this->limite;
            }
            
            public function getOffset(){
                return $this->offset;
            }
        
            public function getOrder(){
                return $this->order;
            }
            
            public function getCols(){
                return $this->cols;
            }
            
            public function getJointures(){
                return $this->jointures;
            }
        /*
            Setters
        */
            public function setAsc($a='ASC'){
                if($a==='ASC' || $a==='DESC' || $a==='asc' || $a==='desc'){
                    $this->asc=strtoupper($a);
                }else{
                    $this->asc='';
                }
            }
    
            public function setLimite($l){
                if(is_numeric($l) && $l>=0){
                    $this->limite=(int)$l;
                }else{
                    throw new RuntimeException('limite must be a positve integer');
                }
            }
            
            public function setOffset($o){
                if(is_int($o) && $o>=0){
                    $this->offset=(int)$o;
                }else{
                    throw new RuntimeException('offset must be a positve integer');
                }
            }
            
            public function setCols($cols){
                if(is_array($cols)){
                    $this->cols=$cols;
                }else{
                    throw new RuntimeException('cols of StructureSearch excepted');
                }
            }
    
            public function setOrder($order){
                if(is_array($order)){
                    $this->order=$order;
                }
            }
            
            public function setJointures($j){
                if(isset($j)){
                    $this->jointures=$j;
                }else{
                    throw new RuntimeException('jointures must be an array');
                }
            }
    
    /*
        Autres méthodes
    */
        public function addCol(Col $obj){
            if(!empty($obj)){
                $op=$obj->getLogicalOperator();
                empty($op) && !empty($this->cols) ? $obj->setLogicalOperator('AND') :null;
                !empty($op) && empty($this->cols) ? $obj->setLogicalOperator('') : null;
                $this->cols[]=$obj;
            }
        }
        
        public function addJointure(Jointure $join){
			$table=$join->getTableJ();
			if(!in_array($table, $this->tableAlreadyJoined)){
				$this->tableAlreadyJoined[]=$table;
				$this->jointures[]=$join;
			}
        }
        
        public function addCols($cols){
            if(!empty($cols)){
                $op=$cols[0]->getLogicalOperator();
                empty($op) && !empty($this->cols) ? $cols[0]->setLogicalOperator('AND') :null;
                !empty($op) && empty($this->cols) ? $cols[0]->setLogicalOperator('') : null;
                $this->cols=array_merge($this->cols, (array)$cols);
            }
        }
        
        public function addJointures($jointures){
			for($a=0; $a<count($jointures); $a++){
				$this->addJointure($jointures[$a]);
			}
        }
        
        public function addOrder($or){
			if(!empty($or) && is_string($or)){
				$this->order[]=$or;
			}
		}
		
		public function addOrders($orders=array()){
			if(!empty($orders) ){
				$this->order=array_merge($this->order, $orders);
			}
		}

		public function mergeWith(Requete $requete){
			$this->addCols( $requete->getCols() );
			$this->addJointures( $requete->getJointures() );
			$this->addOrders( $requete->getOrder() );
			
			//Premier prioritaire
			empty($this->offset) ? $this->offset=$requete->getOffset() : null;
			empty($this->limite) ? $this->limite=$requete->getLimite() : null;
			empty($this->asc) ? $this->asc=$requete->getAsc() : null;
		}
}
