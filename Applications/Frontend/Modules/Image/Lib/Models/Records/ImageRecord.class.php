<?php
/*
 * name: ImageRecord
 * @description :  
 */

class ImageRecord extends FileRecord{
    /*
        Attributs
    */ 
        protected $id_image_miniature=null;
        protected $x;
        protected $y;
        /*
            Constantes
        */
           
            const INVALID_ID_IMAGE_MINIATURE='id_image_miniature|=|Doit être un int'; 
            const INVALID_X='x|=|Doit être un nombre';     
            const INVALID_Y='y|=|Doit être un nombre';            
            
    /*
        Méthodes générales
    */
        
        /*
            Getters
        */
            public function getAllowedTypes(){
                return $this->allowedTypes;
            }

            public function getId_image_miniature(){
                return $this->id_image_miniature;
            }
            
            public function getX(){
                return $this->x;
            }
            
            public function getY(){
                return $this->y;
            }
        /*
            Setters
        */      
            public function setId_image_miniature($id){
                if(is_numeric($id)){
                    $this->id_image_miniature=$id;
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_ID_IMAGE_MINIATURE;
                    return false;
                }
            }
            
            public function setX($x){
                if(is_numeric($x)){
                    $this->x=(int)$x;
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_X;
                    return false;
                }
            }
            
            public function setY($y){
                if(is_numeric($y)){
                    $this->y=(int)$y;
                    return true;
                }else{
                    $this->erreurs[]=self::INVALID_Y;
                    return false;
                }
            }
            
        
    /*
        Autres méthodes
    */
        public function isValid(){
            return ($this->isValidForm() && isset($this->x) && isset($this->y));
        }
}
