<?php
/*
 * name: Forum_ForumFormBuilder
 * @description : 
 */ 
 
class Forum_ForumFormBuilder extends FormBuilder{
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
        public function buildSearch($id){
            $this->form->setAction('index-'.$id);
            $this->form->setId('search');
            $this->form->setMethod('POST');   
            
            //Champs de recherche
            $this->form->add(new StringField(array(
                'id'=>'recherche',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'recherche',
                'size'=>11,
                'placeholder'=>$this->config['field']['recherche'],
                'tooltip'=>$this->config['tooltip']['recherche'],
                'validators'=>array(new MaxLengthValidator(256))
            )));
            
            $this->form->add(new SelectField(array(
                'id'=>'langue',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'langue', 
                'label'=>$this->config['field']['langue'],
                'tooltip'=>$this->config['tooltip']['langue'],
                'optgroups'=>(new Langue)->getCategorieOptions('',new Langue(['nom'=>'','value'=>'']))
            ))); 
            
            $this->form->add(new HiddenField(array(
                'id'=>'ID',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'id', 
                'value'=>$id
            ))); 
            
            $this->form->add(new SubmitField(array(
				'br'=>false,
                'brLabel'=>false,
                'value'=>'Go'
            )));
            
        }
}

