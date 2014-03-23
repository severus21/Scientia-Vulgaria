<?php
/*
 * name: Objects
 * @description :  
 */
 
 abstract class Objects{
    /*
        Attributs
    */
		protected $id;
        protected $record;
        protected $erreurs =null;
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        public function __construct(Array $donnees=null){
            if(!empty($donnees)){
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
        
        public function __clone(){
			$this->record=clone($this->record);
		} 
        /*
            Getters
        */	
			public function getId(){
                return $this->id;
            }

            public function getRecord(){
                return $this->record;
            }
 
            public function getManager(){
                return $this->manager;
            } 
            
            public function getErreurs(){
                if(isset($this->erreurs)){
                    $erreurs='<ul>';
                    for($a=0; $a<count($this->erreurs); $a++){
                        $erreurs.='<li>'.$this->erreurs[$a].'</li>';
                    }
                    $erreurs.='</ul>';
                    return $erreurs;
                }
                return '';
            }  
        /*
         * Setters
         */
			public function setId($id){
				if(is_numeric($id)){
					$this->id = (int) $id;
					return true;
				}else{
					return false;
				}
            }
            
            
            public function setRecord(&$record=null){
                if(is_object($record)){
                    $this->record=$record;
                    is_numeric($this->record->getId()) ? $this->setId($this->record->getId()) : null	;
                    return true;
                }elseif(is_array($record)){
                    $class=get_class($this).'Record';
                    $this->record=new $class($record);
                    return true;
                }
                return false;
            }
    
            public function setManager(&$manager){
                 if(is_object($manager)){
                    $this->manager=$manager;
                    return true;
                }elseif(is_array($record)){
                    $class=get_class($this).'Manager';
                    $this->manager=new $class($manager);
                    return true;
                }
                return false;
            }
    /*
     * Autres méthodes 
     */
     
     /*
         * 
         * name: obj2ar
         * @param: 
         * @return: $array(array))
         * description: 
         */
        public function obj2ar(){
            $classRef=new ReflectionClass(get_class($this));
            $properties = $classRef->getProperties();
            $array=[];
            for($a=0 ; $a<count($properties) ; $a++){
                $property=$properties[$a]->getName();
                
                if($property=='record' || $property=='objects'){
                    $tmp=$this->$property;
                    if(isset($tmp)){
                        $array[$property]=$tmp->obj2ar();
                    }
                }elseif($property!='manager'){
                    $array[$property]=$this->$property;
                }
            }
            return $array;
        }
        
        
        public function getCurrentClass(){
			return get_class($this);
		}
}
