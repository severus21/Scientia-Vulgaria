<?php
/*
 * name: ContentRecord
 * @description :  
 */

class ContentRecord extends Record{
    /*
        Attributs
    */
        protected $content;
        /*
            Constantes
        */
            const INVALID_CONTENT='content|=|Caractères ou longueur invalide';
    /*
        Méthodes générales
    */
  
        /*
            Getters
        */
            public function getContent(){
                return $this->content;
            }
        /*
            Setters
        */
            public function setContent($content, $min=0, $max=null, $regExp='#.+#'){
                $max===null ? $max=strlen($content) : null;
                $min===null ? $min=0 : null;
                $regExp===null ? $regExp='#.+#' :null ;
                
                if(empty($content) || !preg_match($regExp, $content) || strlen($content)>$max || strlen($content)<$min){
                    $this->erreurs[] = self::INVALID_CONTENT;
                    return false;
                }else{
                    $this->content=stripslashes(trim(str_replace('\r\n', '<br/>', htmlentities($content, ENT_NOQUOTES, 'utf-8', false))));
                    return true;
                }
            }
    
    /*
        Autres méthodes
    */
        public function isValidForm(){
            return isset($this->content);
        }
        
        public function isValid(){
            return $this->isValidForm();
        }
}
