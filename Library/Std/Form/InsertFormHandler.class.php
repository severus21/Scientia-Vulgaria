<?php
/*
 * name: InsertFormHandler
 * @description : 
 */ 
 
class InsertFormHandler{
    /*
        Attributs
    */
        protected $form;
        protected $manager;
        protected $setHTTPRequette;
        
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        public function __construct(Form $form ,Manager $manager, HTTPRequette $HTTPRequette){
            $this->setForm($form);
            $this->setManager($manager);
            $this->setHTTPRequette($HTTPRequette);
        }
        /*
            Getters
        */
        
        /*
            Setters
        */
            public function setForm(Form $form){
                $this->form = $form;
            }
            
            public function setManager(Manager $manager){
                $this->manager = $manager;
            }
            
            public function setRequest(HTTPRequest $request){
                $this->request = $request;
            }
    /*
        Autres méthodes
    */  
		
		abstract function process();
        public function process(){
            if($this->request->method() == 'POST' && $this->form->isValid()){//a modfier
                $this->manager->save($this->form->entity());
                return true;
            }else{
                return false;
            }   
        }
}
