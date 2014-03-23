<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

abstract class RequeteBuilder{
    /*
        Attributs
    */
        private $requete;
        /*
            Constantes
        */
        
    /*
        Méthodes générales
    */
        public function __construct($s=null){
            if(isset($s)){
                $this->setRequete($s);
            }else{
                $this->requete=new Requete();
            }
        }
        /*
            Getters
        */
            public function getRequete(){
                return $this->requete;
            }

        /*
            Setters
        */
            public function setRequete($s){
                if(isset($s) &&  $s instanceof Requete){
                    $this->requete=$s;
                }else{
                    throw new RuntimeException('requete must be an instance of StructureSearch');
                }
            }
    
    /*
        Autres méthodes
    */
        /*
         *DESC et ASC via une chesckbox coché si DESC
         *LIMIT et OFFSET définie par la pagination
         *ORDER par un champ order    
         * COLS en fct des type
         * recherche non reserver aux requetes complexes
         */
        public function buildFromForm($form){
            $requete=new Requete();
            $fields=$form->getFields();
            for($a=0; $a<count($fields) ; $a++){
                $field=$fields[$a];
                if($field->getName()=='desc' || $field->getName()=='DESC'){
                    $field->getValue()=='true' ? $requete->setAsc('DESC') : null;
                }elseif($field->getName()=='order'){
					$value=$field->getValue();
					if(!empty($value)){     
                        $requete->addOrder($value);
                    }
				}else{
                    switch(get_class($field)){
                        case 'SelectField' :
                            $type='String'; 
                        break;
                        case 'DateField' :
                            $type='String'; 
                        break;
                        case 'EmailField' :
                            $type='String';
                        break;
                        case 'StringField' :
                            $type='String';
                        break;
                        case 'TextField' :
                            $type='String';
                        break;
                        case 'NumberField' :
                            $type='Int';
                        break;
                        default : 
                            $type=null;
                        break;
                    }
                    $classCol=$type.'Col';
                    $value=$field->getValue();
                    if(!empty($value) && isset($type) && $field->getName()!='recherche'){
                        count($requete->getCols())==0 ? $op='' : $op='&&';     
                        $type=='String' ? $compOp='LIKE' : $compOp='=';   
                         
                        $col=new $classCol(['name'=>$field->getName(),
                            'value'=>$value,
                            'logicalOperator'=>$op,
                            'comparisonOperator'=>$compOp
                        ]);  
                        
                        $type=='Select' ? $col->setStrict(true) :null;          
                        $requete->addCol($col);
                    }
                }
            }
            $this->requete=$requete;
        }
}
