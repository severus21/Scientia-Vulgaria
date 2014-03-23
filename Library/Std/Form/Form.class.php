<?php
/*
 * name: Form
 * @description : 
 */
class Form{
    /*
        Attributs
    */
        
        protected $action;
        protected $entity; 
        protected $fields;
        protected $enctype;
        protected $id='';
        protected $method; 
        /*
            Constantes
        */
            const ENCTYPE_APPLICATION='application/x-www-form-urlencoded';
            const ENCTYPE_MULTIPART='multipart/form-data';
            const ENCTYPE_TEXT='text/plain';


    /*
        Méthodes générales
    */
        public function __construct(array $data = array()){
            if(!empty($data)){
                $this->hydrate($data);
            }
        }
        
        public function hydrate($data){
            foreach ($data as $key => $value){
                $method = 'set'.ucfirst($key);
            }
            
            if (is_callable(array($this, $method))){
                $this->$method($value);
            }
        }
       
        /*
            Getters
        */
            public function getAction(){
                return $this->action;
            }
            
            public function getEntity(){
                return $this->entity;
            }
            
            public function getFields(){
                return $this->fields;
            }
            
            public function getMethod(){
                return $this->method;
            }

        /*
            Setters
        */
            public function setAction($action){
                if(is_string($action)){
                    $this->action='action="'.$action.'"';
                    return true;
                }else{
                    throw new RuntimeException('action doit être une string');
                }
            }
            
            public function setEntity(Record $entity){
                if($entity instanceof Record){
                    $this->entity = $entity;
                }else{
                    throw new RuntimeException('entity doit être une intance de record');
                }   
            }
            
            public function setFields(Array $fields){
                $this->fields=$fields;
            }
            
            public function setFormenctype($formenctype){
                if($formenctype==self::ENCTYPE_APPLICATION || $formenctype==self::ENCTYPE_MULTIPART||
                $formenctype==self::ENCTYPE_TEXT){
                    $this->enctype='enctype="'.$formenctype.'"';
                }else{
                    throw new RuntimeException('formenctype invalide');
                }
            }
            
            public function setId($id){
                $this->id='id="'.$id.'"';
            }
            
            public function  setMethod($method){
                if($method=='GET' || $method=='POST'){
                    $this->method='method="'.$method.'"';
                    return true;
                }else{
                    throw new RuntimeException('La methode doit être GET ou POST');
                }
            }
   
   /*
        Autres méthodes
    */  


        public function add(Field $field){
            /*Dans le cas d'un formulaire d'insertion, si un record existe est est invalide on complete en partie
             * le formulaire
             */
            if(isset($this->entity)){
                $reflex= new ReflectionObject($this->entity); 
                $getter = 'get'.ucfirst($field->getName());
                
                if($reflex->hasMethod($getter)){
                    $field->setValue($this->entity->$getter());
                }   
            }
            $this->fields[] = $field; 
        }
        
        public function createView(){
            $view='<form '.$this->action.' '.$this->enctype.' '.$this->id.' '.$this->method.' >';
            
            //Faille CSRF
            $TokenManager=new TokenManager(PDOFactory::getMysqlConnexion());
            $tokenRecord=new TokenRecord( ['time'=>time(), 'value'=>String::uniqStr(15)] );
            $TokenManager->save($tokenRecord);
            $view.='<input type="hidden" name="token_csrf" value="'.$tokenRecord->getValue().'"/>';
            
            // On génère un par un les champs du formulaire.
            if(isset($this->fields))
            foreach ($this->fields as $field){
                $view .= $field->buildWidget().$field->buildBr();
            }
            
            return $view.'</form>';
        }

        public function isValid(){
            $valid = true;

            //On vérifie que le formulaire provienne bien de scientiavulgaria
            isset($_POST['token_csrf']) ? $tokenValue=$_POST['token_csrf'] : null;
            isset($_GET['token_csrf']) ? $tokenValue=$_GET['token_csrf'] : null;
            if(empty($tokenValue))
				return false;

            if($valid){
				$TokenManager=new TokenManager(PDOFactory::getMysqlConnexion());
				$objFact=new ObjectFactory();
				$requette=new Requete();
				$requette->addCol(new StringCol(['name'=>'value', 'value'=>$tokenValue, 'strict'=>true]));
				$records=$TokenManager->getList($requette);

				if(empty($records))
					return false;
				$token=$objFact->buildObject( $records[0] ); 
				if( empty($token) || $token->isOutdated(Token::TTL_FORM) )
					return false;
			}

            foreach ($this->fields as $field){
                if (!$field->isValid()){
                    $valid = false;
                }
            }
            if(!empty($this->entity)){//A utiliser pour tous les formualires d'insertion
                //On remplit le record
                foreach($this->fields as $field){
                    $value=$field->getValue();
                    $setter='set'.ucfirst($field->getName());

                    if(isset($value) && is_callable(array($this->entity, $setter))){
                        $this->entity->$setter($value); 
                    }
                }   
    
                //on check s'il est valid ou non
                if(!$this->entity->isValidForm()){
                    $valid=false;
                    $erreurs=$this->entity->getErreurs();
                    for($a=0; $a<count($erreurs); $a++){
                        for($b=0; $b<count($this->fields); $b++){
                            if($this->fields[$b]->getName()==$erreurs[$a]){
                                $field->setError=true;
                            }
                        }
                        
                    }
                }
            }
            //On sauve le form
            $_SESSION['form']=$this->createView();
            return $valid;
        }
        
            
            //a condition d'il existe un champ captcha
            public function newCaptcha(){ 
                for($a=0; $a<count($this->fields); $a++){
                    if($this->fields[$a] instanceof CaptchaField){
                        $this->fields[$a]->setCaptcha(new Captcha());
                    }
                }
            }
            
        public function purgeFields(){
            unset($this->fields);
        }
        
        
}
