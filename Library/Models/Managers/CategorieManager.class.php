<?php
/*
 * name: SoftwareCategorieManager
 * @description :  
 */

class CategorieManager extends Manager{
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
		public function isCategorieTable($database, $table){
			$cols=$this->describeTable($database, $table);
			for($i=0; $i<count($cols); $i++){
				if(!preg_match('#(:?nom_[a-zA-Z]{2}|value_[a-zA-Z]{2}|id)#', $cols[$i]['Field']))
					return false;
			}	
			return true;
		}
		
		public function getCategorieTables(){
			$tables=array();
			$categorieTables=array();
			
			$tables['scientiavulgaria']=$this->getTablesFrom('scientiavulgaria');
			$tables['forum']=$this->getTablesFrom('forum');
			
			foreach($tables as $nameDatabase => $tmpDatabase){
				for($i=0; $i<count($tmpDatabase); $i++){
					$tmpTable=$tmpDatabase[$i]['Tables_in_'.$nameDatabase];
					if($this->isCategorieTable($nameDatabase, $tmpTable))
						$categorieTables[$nameDatabase][]=$tmpTable;
				}
			}
			return $categorieTables;
		}
		
		public function addLang($code){//Code = fr|uk...
			$tables=$this->getCategorieTables();
			$flag=true;
			foreach($tables as $database => $tmp){
				for($i=0; $i<count($tmp); $i++){
					$table=$tmp[$i];
					$alter=$this->dao->prepare('ALTER TABLE  '.$database.'.'.$table.' ADD nom_'.$code.' varchar(256) NOT NULL');
					$flag=$alter->execute();
					if($flag){
						$alter=$this->dao->prepare('ALTER TABLE  '.$database.'.'.$table.' ADD value_'.$code.' varchar(256) NOT NULL');
						$alter->execute();
					}
				}
			}
			return $flag;		
		}
}
