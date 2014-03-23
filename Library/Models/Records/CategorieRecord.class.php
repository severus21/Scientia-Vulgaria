<?php
/*
 * name: CategorieRecord
 * @description :  
 */
 
class CategorieRecord extends Record{
    /*
        Attributs
    */
       
        public $nom_fr;
        public $value_fr;
        public $nom_uk;
        public $value_uk;
       /* public $nom_es;
        public $value_es;
        public $nom_it;
        public $value_it;
        public $nom_ru;
        public $value_ru;*/
        
        /*
            Constantes
        */
            const NOM_INVALID='nom';
            const VALUE_INVALID='value';
    /*
        Méthodes générales
    */
        /*
            Getters
        */
            public function getNom_fr(){
                return $this->nom_fr;
            }           
            
            public function getValue_fr(){
                return $this->value_fr;
            } 
            
            public function getNom_uk(){
                return $this->nom_uk;
            }           
            
            public function getValue_uk(){
                return $this->value_uk;
            }
            
            public function getNom_es(){
                return $this->nom_es;
            }           
            
            public function getValue_es(){
                return $this->value_es;
            }
            
            public function getNom_it(){
                return $this->nom_it;
            }           
            
            public function getValue_it(){
                return $this->value_it;
            }
            
            public function getNom_ru(){
                return $this->nom_ru;
            }           
            
            public function getValue_ru(){
                return $this->value_ru;
            }
        /*
            Setters
        */
            public function setNom_fr($n){
                if(is_string($n) && strlen($n)<257){
                    $this->nom_fr=htmlentities($n, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::NOM_INVALID;
                }
            } 
            
            public function setValue_fr($v){
                if(is_string($v) && strlen($v)<257){
                    $this->value_fr=htmlentities($v, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::VALUE_INVALID;
                }
            }
            public function setNom_uk($n){
                if(is_string($n) && strlen($n)<257){
                    $this->nom_uk=htmlentities($n, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::NOM_INVALID;
                }
            } 
            
            public function setValue_uk($v){
                if(is_string($v) && strlen($v)<257){
                    $this->value_uk=htmlentities($v, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::VALUE_INVALID;
                }
            }
            
            public function setNom_es($n){
                if(is_string($n) && strlen($n)<257){
                    $this->nom_es=htmlentities($n, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::NOM_INVALID;
                }
            } 
            
            public function setValue_es($v){
                if(is_string($v) && strlen($v)<257){
                    $this->value_es=htmlentities($v, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::VALUE_INVALID;
                }
            }
            
            public function setNom_it($n){
                if(is_string($n) && strlen($n)<257){
                    $this->nom_it=htmlentities($n, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::NOM_INVALID;
                }
            } 
            
            public function setValue_it($v){
                if(is_string($v) && strlen($v)<257){
                    $this->value_it=htmlentities($v, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::VALUE_INVALID;
                }
            }
            
            public function setNom_ru($n){
                if(is_string($n) && strlen($n)<257){
                    $this->nom_ru=htmlentities($n, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::NOM_INVALID;
                }
            } 
            
            public function setValue_ru($v){
                if(is_string($v) && strlen($v)<257){
                    $this->value_ru=htmlentities($v, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[]=self::VALUE_INVALID;
                }
            }
    /*
        Autres méthodes
    */
        public function isValidForm(){
            return (isset($this->nom_fr) && isset($this->value_fr));
        }
        
        public function isValid(){
            return $this->isValidForm();
        }
        
 }
