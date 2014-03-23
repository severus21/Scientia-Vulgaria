<?php
/*
 * name: User
 * @description :  
 */

class User extends Objects{
    /*
        Attributs
    */  
        protected $login=null;
        protected $password;
        protected $accreditation=1;
        protected $statut=1;
        protected $nom;
        protected $prenom;
        protected $anniversaire;
        protected $email;
        protected $dateCreation;
        protected $flash;
        
        protected $ttlAccess=0; //Temps avant la prochaine autentification pr la zone admin
        protected $levelAccess=0; //Niveau d'authentification avec time to live : ttlAccess 
        
        
        protected $urlStack=array();//Utiliser pour garder une trace notament pr les reauthentifications admin

        /*
            Constantes
        */
			//Attention, les noms sont donnés dans la config du module user...
            //Accreditation
            const ACCREDITATION_STANDARDE=1;
            const ACCREDITATION_INITIE=3;
            
            //Statut
            const STATUT_VISITEUR=1;
            const STATUT_MEMBRE=3;
            const STATUT_MODERATEUR=5;
            const STATUT_ADMINISTRATEUR=7;
            
            //TTL_ACCESS en seconde
            const TTL_ACCESS=300;
            
            //levelAccess
            const LEVEL_0=0; //Aucune auth suplementaire
            const LEVEL_1=1; //Auth par mdp / ttl
            const LEVEL_2=2; //Auth par mdp + mdp zone admin  / ttl
            const LEVEL_3=3; //Auth level2 + certificat / ttl
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
            
            public function getFlash($del=false){
				isset($this->flash) ? $tmp=$this->flash : $tmp=null;
				if($del){
					$this->flash=null;
				}
				return $tmp;
			}
			
			public function getTtlAccess(){
				return $this->ttlAccess;
			}
			
			public function getLevelAccess(){
				return $this->levelAccess;
			}
			
			public function getUrlStack(){
				return $this->urlStack;
			}
            
        /*
            Setters
        */
            public function setLogin($login){
                    $this->login =$login;
            }
            
            public function setPassword($password){
                $this->password=$password;
            }
            
            public function setAccreditation($accreditation){
                $this->accreditation=(int)$accreditation;
            }
            
            public function setStatut($statut){
                $this->statut=(int)$statut;
            }
            
            public function setNom($nom){
                $this->nom=$nom;
            }
            
            public function setPrenom($prenom){
                $this->prenom=$prenom;
            }
            
            public function setAnniversaire($anniversaire){ 
                $this->anniversaire=$anniversaire;
            }
          
            public function setEmail($email){
                $this->email=htmlentities($email);
            }
            
            public function setDateCreation($dateCreation){
                $this->dateCreation=$dateCreation;
            }
            
            public function setFlash($flash){
				empty($this->falsh) ? $this->flash=$flash : $this->flash.='<br/>'.$flash;
            }
            
            public function setTtlAccess($ttl){
				if(is_numeric($ttl)){
					$this->ttlAccess=(int)$ttl;
				}
			}
			
            public function setLevelAccess($ttl){
				if(is_numeric($ttl)){
					$this->levelAccess=(int)$ttl;
				}
			}
			
			public function setUrlStack($u){
				$this->urlStack=$u;
			}
    /*
        Autres méthodes
    */
		public function addUrl($url){
			if(!preg_match("#^/erreur/#i", $url))
				$this->urlStack[]=$url;
		}
		
		public function getLastUrl($i){
			for($a=0; $a<$i; $a++)
				array_pop($this->urlStack);
			return array_pop($this->urlStack);
		}
		
		public function getAccreditationOptions($def=""){
			$AppConfig=new AppConfig('Frontend','User', null);
			$config=$AppConfig->load();
		
			$optGrp=new OptgroupField();
			for($a=0; $a<100; $a++){
				if(array_key_exists('a'.$a, $config['field'])){
					$selected = ($def==$a) ? true : false;
					$optGrp->addOption( new OptionField(array('value'=>(string)$a, 'label'=>$config['field']['a'.$a], 'selected'=>$selected)) );  
				}
			} 

			return [$optGrp];
		}
		
		public function getStatutOptions($def=""){
			$AppConfig=new AppConfig('Frontend','User', null);
			$config=$AppConfig->load();
		
			$optGrp=new OptgroupField();
			for($a=0; $a<100; $a++){
				if(array_key_exists('s'.$a, $config['field'])){
					$selected = ($def==$a) ? true : false;
					$optGrp->addOption( new OptionField(array('value'=>(string)$a, 'label'=>$config['field']['s'.$a], 'selected'=>$selected)) );  
				}
			} 

			return [$optGrp];
		}
}
