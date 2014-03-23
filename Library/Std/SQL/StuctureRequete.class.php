<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class StuctureRequete{
    /*
        Attributs
    */
		$intCols=array();
		$stringCols=array();
		$multipleCols=array();
		$dateCols=array(); //Type date non implémenté
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
			public function getIntCols(){
				return $this->intCols;
			}
			
			public function getStringCols(){
				return $this->stringCols;
			}
			
			public function getMultipleCols(){
				return $this->multipleCols;
			}
			
			public function getDateCols(){
				return $this->dateCols;
			}
        /*
            Setters
        */
        
    
    /*
        Autres méthodes
    */
		public function addIntCol(IntCol $obj){
			$this->intCols[]=$obj;
		}
		
		public function addStringCol(StringCol $obj){
			$this->stringCols[]=$obj;
		}
		
		public function addMultipleCol(MultipleCol$obj){
			$this->multipleCols[]=$obj;
		}
		
		public function addDateCol(DateCol $obj){
			$this->dateCols[]=$obj;
		}
        

}
