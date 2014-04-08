<?php
/*
 * name: SupportFormBuilder
 * @description : 
 */ 
 
class SupportFormBuilder extends FormBuilder{
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
            $this->form->setAction('insert-rapport');
            $this->form->setEntity(new SupportRecord());
            $this->form->setId('report');
            $this->form->setMethod('POST');
            
            
            $this->form->add(new StringField(array(
                'id'=>'url',
                'label'=>'Url de la page :',
                'name'=>'url',
                'size'=>26,
                'maxlength'=>256,
                'tooltip'=>'256 caractères au plus',
                'validators'=>array(new MaxLengthValidator(256))
                )));
            
            //On construit les options
			$options=array();
			foreach(Support::getCategoriesAllowed() as $key=>$element){
				$options[]=new OptionField(['value'=>(string)$key, 'label'=>$element]);
			}
            $this->form->add(new SelectField(array(
                'id'=>'categorie',
                'label'=>'Catégorie :',
                'name'=>'categorie',
                'size'=>1,
                'tooltip'=>'Seul les caractères : [a-z] [A-Z] [0-9] _ @ sont autorisés,  4 à 20 caractères',
                'options'=>$options,
                'validators'=>array(new MaxLengthValidator(20))
                )));
            
            $this->form->add(new TextField(array(
                'id'=>'description',
                'label'=>'Description :',
                'maxlength'=>2000,
                'name'=>'description',
                'rows'=>10,
                'cols'=>50,
                'tooltip'=>'Longueur max : 2000 caractères',
                'required'=>true,
                'validators'=>array(new RequiredValidator(),new MaxLengthValidator(2000))
                )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Valider'
                )));
        }   
}
