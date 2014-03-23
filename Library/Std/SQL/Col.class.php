<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

abstract class Col{
    /*
        Attributs
    */
        protected $table;
        protected $name;
        protected $value;
        protected $comparisonOperator='=';
        protected $logicalOperator;
        protected $requete;
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
            public function getTable(){
                return $this->table;
            }
            
            public function getName(){
                return $this->name;
            }
            public function getComparisonOperator(){
                return $this->comparisonOperator;
            }
            public function getLogicalOperator(){
                return $this->logicalOperator;
            }
            
            public function getValue(){
                return $this->value;
            }

            public function getRequete(){
                return $this->requete;
            }       
        /*
            Setters
        */
            public function setTable($n){
                if(is_string($n))
                    $this->table=$n;
                else
                    throw new RuntimeException('table must be a string');
            }
            
            public function setName($n){
                if(is_string($n))
                    $this->name=$n;
                else
                    throw new RuntimeException('name must be a string');
            }
            
            public function setComparisonOperator($o){
                if($o=='='  || $o=='>' || $o=='>=' || $o=='<' || $o=='<=' || $o=='<>' || $o=='!=' || $o=='LIKE' || $o=='IN' || $o=='NOT IN'){
                    $this->comparisonOperator=$o;
                }else{
                    throw new RuntimeException('operator invalid');
                }
            }
            
            public function setLogicalOperator($o){
                if($o==''  || $o=='AND'  || $o=='&&' || $o=='OR' || $o=='||' || $o=='XOR' || $o=='NOT' || $o=='!' || $o=='AND NOT' || $o=='OR NOT'){   
                    $this->logicalOperator=$o;
                }else{
                    throw new RuntimeException('operator unexcepted : '.$o);
                }
            }
            
            public function setValue($v){
                if(isset($v)){
                    $this->value=$v;
                }
            }
    
            public function setRequete($r){
                if(is_object($r)){
                    $this->requete=$r;
                }else{
                    throw new RuntimeException('objet requete invalid');
                }
            }
            
    /*
        Autres méthodes
    */
        
        public function genRequete($tableO=''){
            empty($this->table) ? $this->setTable($tableO) : null; //Compatibilité ascendante
            return ' '.$this->logicalOperator.' '.$this->table.'.'.$this->name.' '.$this->comparisonOperator.' ? ';
        }
}
