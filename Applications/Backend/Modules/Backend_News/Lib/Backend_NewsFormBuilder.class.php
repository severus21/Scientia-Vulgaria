<?php
/*
 * name: NewsFormBuilder
 * @description : 
 */ 
 
class Backend_NewsFormBuilder extends FormBuilder{
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
        public function buildInsert(){
            $this->form->setAction('insert');
            $this->form->setEntity(new NewsRecord());
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('insert');
            $this->form->setMethod('POST'); 
                
            $this->form->add(new SelectField(array(
                'id'=>'accreditation',
                'label'=>$this->config['field']['accreditation'],
                'name'=>'accreditation', 
                'optgroups'=>(new User)->getAccreditationOptions()
            )));  
            
            $this->form->add(new SelectField(array(
                'id'=>'statut',
                'label'=>$this->config['field']['statut'],
                'name'=>'statut', 
                'optgroups'=>(new User)->getStatutOptions()
            )));
              
            $this->form->add(new SelectField(array(
                'id'=>'app',
                'label'=>$this->config['field']['app'],
                'name'=>'app', 
                'optgroups'=>(new BackendApplication)->getAppsOptions()
            )));  
            
            $this->form->add(new TextField(array(
                'id'=>'textBBCode',
                'label'=>$this->config['field']['text'],
                'name'=>'textBBCode',
                'rows'=>10,
                'cols'=>50,
                'tooltip'=>$this->config['tooltip']['text'],
                'validators'=>array(new MaxLengthValidator(2000))
                )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Add'
                )));
        }   
        
        public function buildUpdate($id){
            $this->form->setAction('update-'.$id);
            $this->form->setEntity(new NewsRecord());
            $this->form->setId('update');
            $this->form->setMethod('POST');
            
            $manager=new NewsManager(PDOFactory::getMysqlConnexion());
            $record=$manager->get($id);
            $objectFactory=new ObjectFactory();
            $obj=$objectFactory->buildObject($record);
            
             $this->form->add(new SelectField(array(
                'id'=>'accreditation',
                'label'=>$this->config['field']['accreditation'],
                'name'=>'accreditation', 
                'optgroups'=>(new User)->getAccreditationOptions($obj->getAccreditation())
            )));  
            
            $this->form->add(new SelectField(array(
                'id'=>'statut',
                'label'=>$this->config['field']['statut'],
                'name'=>'statut', 
                'optgroups'=>(new User)->getStatutOptions($obj->getStatut())
            )));
              
            $this->form->add(new SelectField(array(
                'id'=>'app',
                'label'=>$this->config['field']['app'],
                'name'=>'app', 
                'optgroups'=>(new BackendApplication)->getAppsOptions($obj->getApp())
            )));  
            
            $this->form->add(new TextField(array(
                'id'=>'textBBCode',
                'label'=>$this->config['field']['text'],
                'name'=>'textBBCode',
                'rows'=>10,
                'cols'=>50,
                'tooltip'=>$this->config['tooltip']['text'],
                'value'=>$obj->getTextBBCode(),
                'validators'=>array(new MaxLengthValidator(2000))
                )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Add'
                )));
        }
        
        public function buildSearch(){
		}
}
