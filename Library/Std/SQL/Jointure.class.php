<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class Jointure{
    /*
        Attributs
    */
		protected $databaseJ;
		protected $databaseO;
        protected $tableJ;
        protected $tableO;
        protected $colO; //Colonne de la table de gauche
        protected $colJ; //Colonne de la table de droite
        protected $comparisonOperator='=';
        protected $type=' INNER '; // left, right inner(='')
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
            public function getDatabaseO(){
                return $this->databaseO;
            }
            
            public function getTableO(){
                return $this->tableO;
            }
            
            public function getDatabaseJ(){
                return $this->databaseJ;
            }
            
            public function getTableJ(){
                return $this->tableJ;
            }
            
            public function getColJ(){
                return $this->colJ;
            }
            
            public function getColO(){
                return $this->colO;
            }
            
            public function getType(){
				return $this->type;
			}
        /*
            Setters
        */
            public function setDatabaseO($t){
                if(is_string($t)){
                    $this->databaseO=$t;
                }else{
                    throw new RuntimeException('databaseO must be a string');
                }
            }
            
            public function setTableO($t){
                if(is_string($t)){
                    $this->tableO=$t;
                }else{
                    throw new RuntimeException('tableO must be a string');
                }
            }
            
            public function setDatabaseJ($t){
                if(is_string($t)){
                    $this->databaseJ=$t;
                }else{
                    throw new RuntimeException('databaseJ must be a string');
                }
            }
            
            public function setTableJ($t){
                if(is_string($t)){
                    $this->tableJ=$t;
                }else{
                    throw new RuntimeException('tableJ must be a string');
                }
            }
            
            public function setColJ($c){
                if(is_string($c)){
                    $this->colJ=$c;
                    return true;
                }else{
                    throw new RuntimeException('colJ must be a string');
                }
            }
            
            public function setColO($c){
                if(is_string($c)){
                    $this->colO=$c;
                    return true;
                }else{
                    throw new RuntimeException('colO must be a string');
                }
            }
            
            public function setComparisonOperator($o){
				 if($o=='='  || $o=='>' || $o=='>=' || $o=='<' || $o=='<=' || $o=='<>' || $o=='!=' || $o=='LIKE' || $o=='IN' || $o=='NOT IN'){
                    $this->comparisonOperator=$o;
                }else{
                    throw new RuntimeException('operator invalid');
                }
			}
			
			public function setType($t){
				switch($t){
					case 'left':
						$this->type=' LEFT ';
					break;
					case 'right':
						$this->type=' RIGHT ';
					break;
					default:
						$this->type=' INNER '; 
					break;
					
				}
			}
    /*
        Autres méthodes
    */
        
        public function genRequete($tableO){
			if($tableO!=$this->tableJ){
				$tableO = !empty( $this->tableO) ? $this->tableO : $tableO;
				$databaseJ = !empty($this->databaseJ) ? $this->databaseJ.'.' : '';
				$databaseO = !empty($this->databaseO) ? $this->databaseO.'.' : '';
				return $this->type.' JOIN '.$databaseJ.$this->tableJ.' ON '.$databaseO.$tableO.'.'.$this->colO.' '.$this->comparisonOperator.' '.$databaseJ.$this->tableJ.'.'.$this->colJ.' ';
			}else
				return '';
        }
}
