<?php
/*
 * name: Saison
 * @description :  
 */
 
class Saison extends Objects{
    /*
        Attributs
    */
		protected $idSerie;
		protected $login;
		protected $n;
		protected $resume;
		protected $nbrEpisodes;
		protected $date;
		protected $miniature;
		
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
		public function __clone(){
			$this->miniature =clone($this->miniature );
			parent::__clone();
		}
        /*
            Getters
		*/
			public function getIdSerie(){
				return $this->idSerie;
			}
			
			public function getLogin(){
				return $this->login;
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
			
			public function getMiniature(){
				return $this->miniature;
			}
        /*
            Setters
        */
			public function setIdSerie($id){
				$this->idSerie=$id;
			}
			
			public function setLogin($l){
				$this->login=$l;
			}
			
			public function setN($n){
				$this->n=(int)$n;
			}
			
			public function setResume($l){
                $this->resume=$l;
            } 
			
			public function setNbrEpisodes($n){
				$this->nbrEpisodes=(int)$n;
			}
			
			public function setDate($d){
				$this->date=$d;
            }
			
			public function setMiniature($m){
				$this->miniature=$m;
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
