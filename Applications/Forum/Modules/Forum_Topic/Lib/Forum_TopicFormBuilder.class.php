<?php
/*
 * name: Forum_TopicFormBuilder
 * @description : 
 */ 
 
class Forum_TopicFormBuilder extends FormBuilder{
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
        public function buildInsert($id){
            $this->form->setAction('insert-'.$id);
            $this->form->setEntity(new TopicRecord());
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('insert');
            $this->form->setMethod('POST');   
			
			$this->form->add(new StringField([
				'id'=>'titre',
				'name'=>'titre',
				'size'=>26,
				'label'=>$this->config['field']['titre'],
				'tooltip'=>$this->config['tooltip']['titre']
			]));
			
			$this->form->add(new SelectField(array(
                'id'=>'langue',
                'label'=>$this->config['field']['langue'],
                'name'=>'langue', 
                'tooltip'=>$this->config['tooltip']['langue'],
                'optgroups'=>(new Langue)->getCategorieOptions()
            )));
			
			FormFactory::buildBBcode($this->form, $this->config);
			
			$this->form->add(new TextField([
				'id'=>'text',
				'name'=>'text',
				'rows'=>25,
				'cols'=>100,
				'label'=>$this->config['field']['text'],
				'tooltip'=>$this->config['tooltip']['text'],
				'validator'=>array(new RequiredValidator, new MaxLengthValidator(30000))
			]));
			
			$this->form->add(new SubmitField([
				'value'=>'Post'
			]));
		}
		
		 public function buildUpdate($id){
            $this->form->setAction('update-'.$id);
            $this->form->setEntity(new TopicRecord());
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('update');
            $this->form->setMethod('POST');   
			
			$manager=new TopicManager(PDOFactory::getMysqlConnexion('forum'));
            $record=$manager->get($id);

			$this->form->add(new StringField([
				'id'=>'titre',
				'name'=>'titre',
				'size'=>26,
				'value'=>$record->getTitre(),
				'label'=>$this->config['field']['titre'],
				'tooltip'=>$this->config['tooltip']['titre']
			]));
			
			$this->form->add(new SelectField(array(
                'id'=>'langue',
                'label'=>$this->config['field']['langue'],
                'name'=>'langue', 
                'tooltip'=>$this->config['tooltip']['langue'],
                'optgroups'=>(new Langue)->getCategorieOptions($record->getLangue())
            )));
			
			$this->form->add(new SubmitField([
				'value'=>'Post'
			]));
		}
}
