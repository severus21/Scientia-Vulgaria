<?php
/*
 * name: TmpfileFormBuilder
 * @description : 
 */ 
 
class TmpfileFormBuilder extends FormBuilder{
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
            $this->form->setEntity(new TmpfileRecord());
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('insert');
            $this->form->setMethod('POST');   
            
            $this->form->add(new FileField(array(
                'id'=>'file',
                'label'=>$this->config['field']['file'],
                'name'=>'file',
                'required'=>true,
                'size'=>40,
                'tooltip'=>$this->config['tooltip']['file']
                )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Upload'
                )));
        }
}

