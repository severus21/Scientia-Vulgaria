<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
    Description :
        Page permet :
        d'ajouter une variable à la page;
        d'assigner une vue à la page ;
        de générer la page avec le layout de l'application.
*/

class Page extends ApplicationComponent{
    /*
        Attributs
    */
        protected $contentFile;
        protected $vars = array();
        
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
            public function setContentFile($contentFile)
            {
                if (!is_string($contentFile) || empty($contentFile))
                {
                    throw new InvalidArgumentException('La vue spécifiée est invalide');
                }
            
                $this->contentFile = $contentFile;
            }
    
    /*
        Autres méthodes
    */
        public function addVar($var, $value){
            if (!is_string($var) || is_numeric($var) || empty($var))
            {
                throw new InvalidArgumentException('Le nom de la variable doit être une chaine de caractère non nulle');
            }
            
            $this->vars[$var] = $value;
        }
        
        public function getGeneratedPage(){ 
            if (!file_exists($this->contentFile)){
                throw new RuntimeException('La vue spécifiée n\'existe pas'.$this->contentFile);
            }
           
            if(isset($_SESSION['user'])){
                $user=new User();
            }

            
            extract($this->vars);
            
            ob_start();
                require $this->contentFile;
            $content = ob_get_clean();
            
            ob_start();
                require '../Applications/'.$this->app->getNom().'/Templates/layout.php';
            return ob_get_clean();
        }
        
        
}
