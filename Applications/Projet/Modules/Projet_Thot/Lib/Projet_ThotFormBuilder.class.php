<?php
/*
 * name: Projet_ThotFormBuilder
 * @description : 
 */ 
 
class Projet_ThotFormBuilder extends FormBuilder{
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
			public function buildInsertExemple(){
				$this->form->setAction('insert-exemple');
				$this->form->setId('insert-exemple');
				$this->form->setMethod('POST');
				$this->form->setEntity(new ThotExempleRecord());
				$this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
				
				$this->form->add(new SelectField(array(
					'id'=>'id_thotDatabase_database',
					'label'=>$this->config['field']['id_thotDatabase_database'],
					'name'=>'id_thotDatabase_database',
					'options'=>(new ThotDatabase)->getDatabaseOptions(),
					'tooltip'=>$this->config['tooltip']['id_thotDatabase_database'],
					'validators'=>array(new RequiredValidator())
					)));
						
				$this->form->add(new StringField(array(
					'id'=>'c',
					'label'=>$this->config['field']['c'],
					'name'=>'c',
					'tooltip'=>$this->config['tooltip']['c'],
					'validators'=>array(new MaxLengthValidator(256))
					)));
		 
				$this->form->add(new ImageField(array(
					'id'=>'image',
					'label'=>$this->config['field']['image'],
					'name'=>'image',
					'required'=>true,
					'size'=>40,
					'tooltip'=>$this->config['tooltip']['image']
					)));
								
				$this->form->add(new SubmitField(array(
					'value'=>'Upload'
					)));
			}  
			 
			public function buildInsertMultiExemple(){
				$this->form->setAction('insert-multi-exemple');
				$this->form->setId('insert-multi-exemple');
				$this->form->setMethod('POST');
				$this->form->setEntity(new ThotExempleRecord());
				$this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
				
				$this->form->add(new SelectField(array(
					'id'=>'id_thotDatabase_database',
					'label'=>$this->config['field']['id_thotDatabase_database'],
					'name'=>'id_thotDatabase_database',
					'options'=>(new ThotDatabase)->getDatabaseOptions(),
					'tooltip'=>$this->config['tooltip']['id_thotDatabase_database'],
					'validators'=>array(new RequiredValidator())
					)));
						
		 
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
			 
			public function buildUpdateExemple(&$exemple){
				$this->form->setAction('update-exemple-'.$exemple->getId());
				$this->form->setId('update-exemple');
				$this->form->setMethod('POST');
				$this->form->setEntity(new ThotExempleRecord());
				$this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
						
				$this->form->add(new StringField(array(
					'id'=>'c',
					'label'=>$this->config['field']['c'],
					'name'=>'c',
					'value'=>$exemple->getC(),
					'size'=>6,
					'tooltip'=>$this->config['tooltip']['c'],
					'validators'=>array(new MaxLengthValidator(256))
					)));
								
				$this->form->add(new SubmitField(array(
					'value'=>'Update'
					)));
			}   
	
			public function buildInsertDatabase(){
				$this->form->setAction('insert-database');
				$this->form->setId('insert-database');
				$this->form->setMethod('POST');
				$this->form->setEntity(new ThotDatabaseRecord());
				
				$this->form->add(new StringField(array(
					'id'=>'nom',
					'label'=>$this->config['field']['nom'],
					'name'=>'nom',
					'size'=>6,
					'tooltip'=>$this->config['tooltip']['nom'],
					'validators'=>array(new RequiredValidator(),new MaxLengthValidator(256))
					)));
						
				$this->form->add(new StringField(array(
					'id'=>'type',
					'label'=>$this->config['field']['type'],
					'name'=>'type',
					'size'=>6,
					'tooltip'=>$this->config['tooltip']['type'],
					'validators'=>array(new RequiredValidator(),new MaxLengthValidator(256))
					)));
								
				$this->form->add(new SubmitField(array(
					'value'=>'Add'
					)));
			} 

			public function buildUpdateDatabase($database){
				$this->form->setAction('update-database-'.$database->getId());
				$this->form->setId('update-database');
				$this->form->setMethod('POST');
				$this->form->setEntity(new ThotDatabaseRecord());
				
				$this->form->add(new StringField(array(
					'id'=>'nom',
					'label'=>$this->config['field']['nom'],
					'name'=>'nom',
					'value'=>$database->getNom(),
					'size'=>6,
					'tooltip'=>$this->config['tooltip']['nom'],
					'validators'=>array(new RequiredValidator(),new MaxLengthValidator(256))
					)));
						
				$this->form->add(new StringField(array(
					'id'=>'type',
					'label'=>$this->config['field']['type'],
					'name'=>'type',
					'value'=>$database->getType(),
					'size'=>6,
					'tooltip'=>$this->config['tooltip']['type'],
					'validators'=>array(new RequiredValidator(),new MaxLengthValidator(256))
					)));
								
				$this->form->add(new SubmitField(array(
					'value'=>'Update'
					)));
			}

			 public function buildSearch($action='list'){
            $this->form->setAction($action);
            $this->form->setId('search');
            $this->form->setMethod('POST');   
            
            //Champs de recherche
            /*$this->form->add(new StringField(array(
                'id'=>'recherche',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'recherche',
                'size'=>11,
                'placeholder'=>$this->config['field']['recherche'],
                'tooltip'=>$this->config['tooltip']['recherche'],
                'validators'=>array(new MaxLengthValidator(256))
            )));*/
            
            $this->form->add(new SelectField(array(
                'id'=>'database',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'id_thotDatabase_database', 
                'label'=>$this->config['field']['id_thotDatabase_database'],
                'tooltip'=>$this->config['tooltip']['id_thotDatabase_database'],
                'optgroups'=>(new ThotDatabase)->getDatabaseOptions('',new ThotDatabase())
            )));  
            
            $this->form->add(new SubmitField(array(
                'value'=>'Go'
            )));
            
        }
}
