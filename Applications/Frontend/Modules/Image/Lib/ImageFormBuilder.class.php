<?php
/*
 * name: ImageFormBuilder
 * @description : 
 */ 
 
class ImageFormBuilder extends FormBuilder{
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
            $this->form->setEntity(new ImageRecord());
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('insert');
            $this->form->setMethod('POST');   
                
            $this->form->add(new StringField(array(
                'id'=>'description',
                'label'=>'Description :',
                'name'=>'description',
                'size'=>26,
                'placeholder'=>'256 carctères max',
                'tooltip'=>'Chaine de 256 caractères max',
                'validators'=>array(new MaxLengthValidator(256))
                )));    
                
            $this->form->add(new ImageField(array(
                'id'=>'file',
                'label'=>'Fichier :',
                'name'=>'file',
                'required'=>true,
                'size'=>26,
                'tooltip'=>'Seul les fichiers images sont autorisés'
                )));
             
            $this->form->add(new CheckboxField(array(
                'id'=>'redimention',
                'label'=>'Redimentionner',
                'value'=>'true',
                'name'=>'redimention',
                )));
                 
                
            $this->form->add(new NumberField(array(
                'id'=>'x',
                'label'=>'Largeur(optionnel) :',
                'min'=>1,
                'max'=>20000,
                'name'=>'x',
                'value'=>'1',
                'size'=>26,
                'tooltip'=>'Doit être compris entre 0 et 20000',
                'validators'=>array(new MaxValidator(20000), new MinValidator(1))
                )));
                
            $this->form->add(new NumberField(array(
                'id'=>'y',
                'label'=>'Hauteur(optionnel) :',
                'min'=>1,
                'max'=>20000,
                'name'=>'y',
                'value'=>'1',
                'size'=>26,
                'tooltip'=>'Doit être compris entre 0 et 20000',
                'validators'=>array(new MaxValidator(20000), new MinValidator(1))
                )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Upload'
                )));
        }   
        
        public function buildUpdate($id){
            $this->buildInsert();
            $this->form->setAction('update-'.$id);
            
        }

        public function buildSearch(){
            $this->form->setAction('index');
            $this->form->setId('search');
            $this->form->setMethod('POST');   
            
            $this->form->add(new StringField(array(
                'id'=>'keywords',
                'br'=>false,
                'brLabel'=>false,
                'label'=>'Rechercher :',
                'name'=>'description',
                'size'=>26,
                'placeholder'=>'256 carctères max',
                'tooltip'=>'Chaine de 256 caractères max',
                'validators'=>array(new MaxLengthValidator(256))
            ))); 
            
            $this->form->add(new SubmitField(array(
                'value'=>'Go'
            )));
            
        }
}

