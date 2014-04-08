<?php
/*
 * name: TmpfileController
 * @description : 
 */

class TmpfileRecord extends FileRecord{ 
    /*
        Attributs
    */
        protected $ttl;//Time to live
        
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        /*
            Getters
        */
            public function getTtl(){
                return $this->ttl;
            }
            
        /*
            Setters
        */
            public function setTtl($ttl){
                 if(is_numeric($ttl) && $ttl>0){
                    $this->ttl=$ttl;
                    return true;
                }else{
                    throw new RuntimeException('Time to live must be a postive integer');
                }
			}
    /*
        Autres méthodes
    */
		public function isValid(){
			return isset($this->ttl) && parent::isValid();
		}
}
