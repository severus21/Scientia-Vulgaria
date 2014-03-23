<?php
/*
 * name: UserRecord
 * @description :  
 */

class UserRecord extends Record{
    /*
        Attributs
    */
        protected $login;
        protected $password;
        protected $accreditation=1;
        protected $statut=1;
        protected $nom;
        protected $prenom;
        protected $anniversaire;
        protected $email;
        protected $dateCreation;
        
        
        /*
            Constantes
        */
            const INVALID_LOGIN='login|=|Seul les caractères : [a-z] [A-Z] [0-9] _ @ sont autorisés,  4 à 20 caractères';
            const INVALID_PASSWORD='password|=|Seul les caractères : [a-z] [A-Z] [0-9] _ @ sont autorisés,  4 à 30 caractères';
            const INVALID_NOM='nom|=|Seul les caractères :[a-z][ A-Z] \' - sont autorisés,  3 à 20 caractères';
            const INVALID_PRENOM='prenom|=|Seul les caractères :[a-z][ A-Z] \' - sont autorisés,  3 à 20 caractères';
            const INVALID_ANNIVERSAIRE='anniversaire|=|Format date de naissance invalide';
            const INVALID_EMAIL='email|=|Format email non respecté';
            
    /*
        Méthodes générales
    */
       
        /*
            Getters
        */
            public function getLogin(){
                return $this->login;
            }
            
            public function getPassword(){
                return $this->password;
            }
            
            public function getAccreditation(){
                return $this->accreditation;
            }
            
            public function getStatut(){
                return $this->statut;
            }
            
            public function getNom(){
                return $this->nom;
            }
            
            public function getPrenom(){
                return $this->prenom;
            }
            
            public function getAnniversaire(){
                return $this->anniversaire;
            }
            
            public function getEmail(){
                return $this->email;
            }
            
            public function getDateCreation(){
                return $this->dateCreation;
            }
            
            
    
    
        /*
            Setters
        */
            public function setLogin($login){
                if (!preg_match('#^[a-zA-Z0-9_@]{4,20}$#i',$login)){
                    $this->erreurs[] = self::INVALID_LOGIN;
                    return false;
                }else{
                    $this->login =htmlentities($login, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }
            }
            
            public function setPassword($password){
                if(isset($password)){
                    $this->password=htmlentities($password, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }else{
                    $this->erreurs[] = self::INVALID_PASSWORD;
                    return false;
                }
            }
            
            public function setAccreditation($accreditation){
                if(is_numeric($accreditation)){
                    $this->accreditation=(int)$accreditation;
                    return true;
                }else{
                    return false;
                }
            }
            
            public function setStatut($statut){
                if(is_numeric($statut)){
                    $this->statut=(int)$statut;
                    return true;
                }else{
                    return false;
                }
            }
            
            public function setNom($nom){
                if(!preg_match('#^[a-zA-Z` -]{3,20}$#i', $nom)){
                    $this->erreurs[] = self::INVALID_NOM;
                    return false;
                }else{
                    $this->nom=htmlentities($nom, ENT_QUOTES, 'utf-8', false);
                    return true;
                }
            }
            
            public function setPrenom($prenom){
                if(!preg_match('#^[a-zA-Z` -]{3,20}$#i', $prenom)){
                    $this->erreurs[] = self::INVALID_PRENOM;
                    return false;
                }else{
                    $this->prenom=htmlentities($prenom, ENT_QUOTES, 'utf-8', false);;
                    return true;
                }
            }
            
            public function setAnniversaire($anniversaire){ 
                if(is_string($anniversaire)){
                    $this->anniversaire=$anniversaire;
                    return true;
                }elseif(is_array($anniversaire)){
                    if(1<=$anniversaire['j'] && $anniversaire['j']<=31 && 1<=$anniversaire['m'] && $anniversaire['m']<=12 && 
                    (date('Y')-100)<=$anniversaire['a'] && $anniversaire['a']<=(date('Y')-10)){
                        if($anniversaire['j']<10){
                            $anniversaire['j']='0'.$anniversaire['j'];
                        }
                        if($anniversaire['m']<10){
                            $anniversaire['m']='0'.$anniversaire['m'];
                        }
                        $this->anniversaire=$anniversaire['a'].'-'.$anniversaire['m'].'-'.$anniversaire['j'];
                        return true;
                    }else{
                        $this->erreurs[]=self::INVALID_ANNIVERSAIRE;
                    return false;
                    }
                }else{
                    $this->erreurs[]=self::INVALID_ANNIVERSAIRE;
                    return false;
                }
                
            }
          
            public function setEmail($email){
                if(!preg_match('#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#', $email)){
                    $this->erreurs[]=self::INVALID_EMAIL;
                    return false;
                }else{
                    $this->email=htmlentities($email, ENT_NOQUOTES, 'utf-8', false);
                    return true;
                }
            }
            
            public function setDateCreation($dateCreation){
                $this->dateCreation=$dateCreation;
            }
    /*
        Autres méthodes
    */
        public function isValidForm(){
            return (isset($this->login) && isset($this->password) && isset($this->accreditation) &&
            isset($this->statut) && isset($this->nom) && isset($this->prenom) && isset($this->anniversaire) &&
            isset($this->email));
        }
        
        public function isValid(){
            return $this->isValidForm();
        }
}
