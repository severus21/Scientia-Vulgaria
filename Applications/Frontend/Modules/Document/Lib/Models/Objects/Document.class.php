<?php
/*
 * name: Document
 * @description :  
 */

class Document extends File{
    /*
        Attributs
    */ 
        /*
            Constantes
        */
            const DIR='../Web/Files/Documents';
    /*
        Méthodes générales
    */
        
        /*
            Getters
        */
            static public function getAllowedTypes(){
                return array('application/pdf', 'application/epub+zip');
            }
        /*
            Setters
        */

            
        
    /*
        Autres méthodes
    */
        static public function type2Ext($t){
            $table=array('application/pdf'=>'pdf', 'application/epub+zip'=>'epub');
             if(array_key_exists($t, $table)){
                return $table[$t];
            }
            throw new RuntimeException('Extension inconnue');
        }
        
        public function getShowLink($class='', $value='Voir'){ 
			return '<a class="'.$class.'" href="../../../document/show-'.$this->getRecord()->getId().'-'.$this->getExt().'">'.$value.'</a>';
		}
        
        
}

