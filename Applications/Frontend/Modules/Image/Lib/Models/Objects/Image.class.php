<?php
/*
 * name: Image
 * @description :  
 */

 class Image extends File{
    /*
        Attributs
    */ 
        protected $miniature;
        protected $x;
        protected $y;
        
        
        /*
            Constantes
        */
            const DIR='../Web/Files/Images';
    /*
        Méthodes générales
    */
        public function __clone(){
			if(isset($this->miniature))
				$this->miniature =clone($this->miniature );
			parent::__clone();
		}
        /*
            Getters
        */ 
            public function getMiniature(){
                return $this->miniature;
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
            public function setMiniature(Image $miniature){
                $this->miniature = $miniature;
            }
            
            public function setX($x){
                $this->x=(int)$x;
            }
            
            public function setY($y){
                $this->y=(int)$y;
            }   
            
            static public function getAllowedTypes(){
                return array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');
            }
    /*
        Autres méthodes
    */
        static public function type2Ext($t){
            $table=array('image/jpeg'=>'jpeg', 'image/jpg'=>'jpeg', 'image/gif'=>'gif', 'image/png'=>'png');
            if(array_key_exists($t, $table)){
                return $table[$t];
            }
            throw new RuntimeException('Extension inconnue');
        }
}
