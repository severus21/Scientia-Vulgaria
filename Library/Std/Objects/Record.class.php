<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
    Description :
        Implémentation d'un constructeur qui hydratera l'objet si un tableau de valeurs lui est fourni ;
        Implémentation d'une méthode qui permet de vérifier si l'enregistrement est nouveau ou pas. 
*/

abstract class Record{
    /*
        Attributs
    */
        protected $id;
        protected $erreurs =null;
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        public function __construct(array $donnees = array()){
            if (!empty($donnees)){
                $this->hydrate($donnees);
            }
        }
        
        public function hydrate(array $donnees){
            foreach ($donnees as $attribut => $valeur){
                $methode = 'set'.ucfirst($attribut);
                
                if (is_callable(array($this, $methode))){
                    $this->$methode($valeur);
                }
            }
        }
        
        /*
            Getters
        */
            public function getErreurs($print=false){//si printf =false alors retour pour formulaire sinon pour un affichafe directe
                if(isset($this->erreurs) && $print==true){
                    $erreurs='<ul>';
                    for($a=0; $a<count($this->erreurs); $a++){
                        $erreurs.='<li>'.$this->erreurs[$a].'</li>';
                    }
                    $erreurs.='</ul>';
                    return $erreurs;
                }else{
                    $erreurs=array();
                    $matchs='';
                    for($a=0; $a<count($this->erreurs); $a++){
                        preg_match('#^([a-z]+)|=|.+#i', $this->erreurs[$a], $matchs);
                        $erreurs[]=$matchs[1];
                    }
                    return $erreurs;
                }
            }
            
            public function getId(){
                return $this->id;
            }

        /*
            Setters
        */
            public function setId($id){
				if(is_numeric($id)){
					$this->id = (int) $id;
					return true;
				}else{
					return false;
				}
            }
            
    
    /*
        Autres méthodes
    */
        
        
        public function isNew(){
            return empty($this->id);
        }
        
        public function isValid(){
            
        }
        /*
         * 
         * name: obj2ar
         * @param: 
         * @return: $array(array))
         * description: converti un objet en tableau pour enregistrement
         */
        public function obj2ar(){
            $classRef=new ReflectionClass(get_class($this));
            $properties = $classRef->getProperties();
            $array=[];

            for($a=0 ; $a<count($properties) ; $a++){
                $property=$properties[$a]->getName();
                if($property!="erreurs" && $property!="indexTab"){
                    $methode = 'get'.ucfirst($property);
                    $array[$property]=$this->$methode();
                }
            }
            return $array;
        }
		
		
		/*
		 * 
		 * name: mergeRecord
		 * @param
		 * @return
		 * @description 
		 */
		public function mergeRecord(Record $secondary){
			$classRef=new ReflectionClass(get_class($this));
            $properties = $classRef->getProperties();
           
			for($a=0; $a<count($properties); $a++){
				$property=$properties[$a]->getName();
				$getter='get'.ucfirst( $property );
				$buffer=$secondary->$getter();
				
				!empty($buffer) ? $this->$property=$buffer  : null;
			}
		}
}
