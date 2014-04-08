<?php
/*
 * name: Episode
 * @description :  
 */
 
class Episode extends Objects{
    /*
        Attributs
    */
		protected $login;
        protected $idSaison;
        protected $idSerie;
        protected $nom; 
        protected $n;
        protected $resume;
        protected $subtitle;
        protected $files;
        
        
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        public function __clone(){
			$this->files =ArrayUtilities::cloneObjects($this->files );
			parent::__clone();
		}
        /*
            Getters
        */
			public function getLogin(){
				return $this->login;
			}
			
            public function getIdSaison(){
                return $this->idSaison;
            }

            public function getIdSerie(){
                return $this->idSerie;
            }
            
            public function getNom(){
				return $this->nom;
			}
            
            public function getN(){
                return $this->n;
            }
			
			public function getResume(){
				return $this->resume;
			}
			
            public function getSubtitle(){
                return $this->subtitle;
            }
            
            public function getFiles(){
                return $this->files;
            }

        /*
            Setters
        */
			public function setLogin($l){
				$this->login=$l;
			}
			
            public function setIdSaison($id){
                $this->idSaison=(int)$id;
            } 
            
            public function setIdSerie($e){
                $this->idSerie=(int)$e;
            }
            
            public function setNom($nom){
                $this->nom=$nom;
            }   
            
            public function setN($n){
                $this->n=(int)$n;
            }
            
            public function setResume($l){
                $this->resume=$l;
            } 
            
            public function setSubtitle($s){             
                $this->subtitle;
            }
            
            public function setFiles($f){
                $this->files=$f;
            } 
    /*
        Autres méthodes
    */
		public function getShowLink($linkOnly=false, $class='', $value='Voir'){
			$module=strtolower(get_class($this));
			if($linkOnly)
				return '/'.$module.'/show-'.$this->getRecord()->getId();
			else 
				return '<a class="'.$class.'" href="/'.$module.'/show-'.$this->getRecord()->getId().'">'.$value.'</a>';
		}
 }
