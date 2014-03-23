<?php
/*
 * name: 
 * @description :  
 */
 
class ArrayUtilities{
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
		static public function  cloneObjects(&$arrayOfObjects){
			if(empty($arrayOfObjects))
				return array();
			
			$new = array();
			foreach ($arrayOfObjects as $k => $v) {
				$new[$k] = clone $v;
			}
			return $new;
		}
}
        
