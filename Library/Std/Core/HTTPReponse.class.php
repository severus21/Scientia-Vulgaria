<?php
/*
 * 
 * name: HTTPReponse
 * @description permet :
 *         d'assigner une page à la réponse ;
 *          d'envoyer la réponse en générant la page ;
 *          de rediriger l'utilisateur ;
 *          de le rediriger vers une erreur 404 ;
 *          d'ajouter un header spécifique.
 * 
 */

class HttpReponse extends ApplicationComponent{
    /*
        Attributs
    */
        protected $page;
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        /*
            Getters
        */
            public function getPage(){
                return $this->page;
            }
    
        /*
            Setters
        */
            public function setPage(Page $page){
                $this->page = $page;
            }
    
    /*
        Autres méthodes
    */
        public static function addHeader($header){
            header($header);
        }
        
        public static function redirect($location){
            header('Location: '.$location);
            exit;
        }
        
        public static function redirect404(){
            self::addHeader('HTTP/1.0 404 Not Found');
            self::redirect('../../../erreur/404');
        }
        
        //Erreur perso : sessio untrust
        public static function redirect700(){
			self::addHeader('HTTP/1.0 700 Session Broken');
            self::redirect('../../../erreur/700');
		}
        
        public function send(){
            exit($this->page->getGeneratedPage());
        }
}
