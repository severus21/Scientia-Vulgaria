<?php
/*
 * name: Backend_CacheFormBuilder
 * @description : 
 */ 
 
class Backend_CacheFormBuilder extends FormBuilder{
    /*
        Attributs
    */
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        /*
            Getters
        */
        
        /*
            Setters
        */

    /*
        Autres méthodes
    */  
			public function buildIndex(){
            $this->form->setAction('auth1');
            $this->form->setId('connexion');
            $this->form->setMethod('POST');
            
            $this->form->add(
                new PasswordField(array(
                    'id'=>'password',
                    'label'=>$this->config['field']['password'],
                    'name'=>'password',
                    'size'=>26,
                    'tooltip'=>$this->config['tooltip']['password'],
                    'validators'=>array(new RequiredValidator())
                    )));
     
            $this->form->add(
                new SubmitField(array(
                    'value'=>'Connexion'
                    )));
        }   
	
}
