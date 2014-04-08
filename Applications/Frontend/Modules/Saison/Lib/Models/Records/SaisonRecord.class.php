<?php
/*
 * name: SaisonRecord
 * @description :  
 */
 
class SaisonRecord extends Record{
    /*
        Attributs
    */
		protected $login;
		protected $idSerie;
		protected $n;
		protected $resume;
		protected $nbrEpisodes;
		protected $date;
		protected $id_image_miniature;
		
        /*
            Constantes
        */
			const RESUME_INVALID="resume";
			const N_INVALID="n";
			const NBR_EPIDOSES_INVALID="nbrEpisodes";
			const DATE_INVALIDE="date";
    /*
        Méthodes générales
    */
        /*
            Getters
		*/
			public function getLogin(){
				return $this->login;
			}
			
			public function getIdSerie(){
				return $this->idSerie;
			}
			
			public function getN(){
				return $this->n;
			}
			
			public function getResume(){
				return $this->resume;
			}
			
			public function getNbrEpisodes(){
				return $this->nbrEpisodes;
			}
			
			public function getDate(){
				return $this->date;
			}
			
			public function getId_image_miniature(){
				return $this->id_image_miniature;
			}
        /*
            Setters
        */
			public function setLogin($l){//pas besoin de traitement supplémentaire car le login provient d'un objet user
                if(isset($l))
                    $this->login=$l;
                
            }
			
			public function setIdSerie($id){//Donné par le serveur
				if(is_numeric($id))
					$this->idSerie=$id;
				else
					throw new RuntimeException('must be an integer');
			}
			
			public function setN($n){
				if(is_numeric($n))
					$this->n=(int)$n;
				else 
					$this->erreurs[]=self::N_INVALID;
			}
			
			public function setResume($l){
                if(is_string($l) && strlen($l)<=2000){
                    $this->resume=htmlentities($l, ENT_HTML5, 'utf-8', false);
                }else{
                    $this->erreurs[]=self::RESUME_INVALID;
                }
            } 
			
			public function setNbrEpisodes($n){
				if(is_numeric($n))
					$this->nbrEpisodes=(int)$n;
				else 
					$this->erreurs[]=self::N_INVALID;
			}
			
			public function setDate($d){//vérifier par le form ou donnée par le serveur
                if(isset($d))
                    $this->date=$d;
                else
					$this->erreurs[]=self::DATE_INVALID;
            }
			
			public function setId_image_miniature($i){
				if(is_numeric($i)){
                    $this->id_image_miniature=$i;
                }else{
                    throw new RuntimeException('must be an integer');
                }
			}
    /*
        Autres méthodes
    */
		public function isValidForm(){
            return ( isset($this->date) && isset($this->n) && isset($this->nbrEpisodes) && isset($this->resume) );
        }
        
        public function isValid($form=false){
            return ( $this->isValidForm() && isset($this->idSerie) && isset($this->id_image_miniature) );
        }
}
