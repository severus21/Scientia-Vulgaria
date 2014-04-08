<?php
/*
 * name: Tmpfile
 * @description : 
 */

class Tmpfile extends File{ 
    /*
        Attributs
    */
        protected $ttl;//Time to live
        
        /*
            Constantes
        */
            const TTL=172800;
            const DIR='../Web/Files/Tmpfiles';
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
				$this->ttl=$ttl;
			}
    /*
        Autres méthodes
    */
}
