<?php
/*
 * name: ObjectStructure
 * @description :  
 */

class ObjectStructure{
	/*
        Attributs
    */
        protected $layer;
        protected $className;
        protected $childs=array();
        protected $multiChilds=array();
        protected $attributes=array();
        
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
 
        /*
            Getters
        */
			public function getLayer(){
				return $this->layer;
			}
			
			public function getClassName(){
				return $this->className;
			}
        
			public function getAttributes(){
				return $this->attributes;
			}
			
			public function getChilds(){
				return $this->childs;
			}
			
			public function getMultiChilds(){
				return $this->multiChilds;
			}
        /*
            Setters
        */
            public function setLayer($layer){
				if(is_int($layer))
					$this->layer=$layer;
				else
					throw new RuntimeException('Layer must be an int');
            }
            
            public function setClassName($c){
				$this->className=$c;
			}
			
			public function setChilds($c){
				$this->childs=$c;
			}
			public function setMultiChilds($c){
				$this->multiChilds=$c;
			}
			
			public function setAttributes($a){
				$this->attributes=$a;
			}
    
    /*
        Autres méthodes
    */
			public function addAttribute($labelId, $attRecordName, $attName, $recordOnly=false){
				$this->attributes[ $labelId ]= ['attRecordName'=>$attRecordName,
												'recordSetter'=>'set'.ucfirst($attRecordName),
												'recordGetter'=>'get'.ucfirst($attRecordName)];
				if(!$recordOnly){
					$this->attributes[ $labelId ]['attName']=$attName;											
					$this->attributes[ $labelId ]['setter']='set'.ucfirst($attName);
					$this->attributes[ $labelId ]['getter']='get'.ucfirst($attName);
					$this->attributes[ $labelId ]['recordOnly']= false;
				}else{
					$this->attributes[ $labelId ]['recordOnly']=true; 
				}
			}
			
			public function setValueAtt($labelId, $value){
				if(!empty($value))
					$this->attributes[ $labelId ]['value']=$value;
			}
			
			public function addChild($labelId, $attRecordName, $attName, $structure){
				$this->childs[ $labelId ]= ['attRecordName'=>$attRecordName,
											'recordSetter'=>'set'.ucfirst($attRecordName),
											'recordGetter'=>'get'.ucfirst($attRecordName),
											'attName'=>$attName,
											'setter'=>'set'.ucfirst($attName),
											'getter'=>'get'.ucfirst($attName),
											'structure'=>$structure];
			}

			public function addMultiChilds($table, $attRecordName, $recordId, $attName, $structure){
				$this->multiChilds[ $table ]=['attRecordName'=>$attRecordName,
											'recordSetter'=>'set'.ucfirst($attRecordName),
											'recordGetter'=>'get'.ucfirst($attRecordName),
											'recordId'=>$recordId,
											'attName'=>$attName,
											'setter'=>'set'.ucfirst($attName),
											'getter'=>'get'.ucfirst($attName),
											'structure'=>$structure];
			}
}
