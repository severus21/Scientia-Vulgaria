<?php
/*
 * name: Archive
 * @description : 
 */

class Archive extends File{ 
    /*
        Attributs
    */
        /*
            Constantes
        */
            const DIR='../Web/Files/Archives';
    /*
        Méthodes générales
    */
        /*
            Getters
        */ 
            static public function getAllowedTypes(){
                return array('application/zip', 'application/x-7z-compressed', 'application/x-rar-compressed', 'application/x-rar',
                'application/x-gzip' ,'application/x-bzip', 'application/x-iso9660-image');
            }
        /*
            Setters
        */
    /*
        Autres méthodes
    */
        static public function type2Ext($t){
            $table=array('application/zip'=>'zip', 'application/x-7z-compressed'=>'7z', 'application/x-rar-compressed'=>'rar',
                'application/x-rar'=>'rar', 'application/x-gzip'=>'gzip' ,'application/x-bzip'=>'bzip', 'application/x-iso9660-image'=>'iso');
             if(array_key_exists($t, $table)){
                return $table[$t];
            }
            throw new RuntimeException('Extension inconnue');
        }
}
