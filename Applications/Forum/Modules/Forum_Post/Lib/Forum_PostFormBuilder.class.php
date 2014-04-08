<?php
/*
 * name: Forum_PostFormBuilder
 * @description : 
 */ 
 
class Forum_PostFormBuilder extends FormBuilder{
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
        public function buildInsert($id=null, $redirect=null){
            $this->form->setAction('/forum/post/insert-'.$id);
            $this->form->setEntity(new PostRecord());
            $this->form->setId('insert');
            $this->form->setMethod('POST');   
			
			FormFactory::buildBBcode($this->form, $this->config);
			
			$this->form->add(new TextField([
				'id'=>'textBBCode',
				'name'=>'textBBCode',
				'label'=>$this->config['field']['text'],
				'tooltip'=>$this->config['tooltip']['text'],
				'validator'=>array(new RequiredValidator, new MaxLengthValidator(30000))
			]));
			
			if(!empty($redirect)){
				$this->form->add(new HiddenField([
				'id'=>'redirect',
				'name'=>'redirect',
				'value'=>$redirect,
				'validator'=>array(new RequiredValidator, new MaxLengthValidator(300))
			]));
			}
			
			$this->form->add(new SubmitField([
				'value'=>'Post'
			]));
		}
        public function buildUpdate($id, $redirect=null){
            $this->form->setAction('/forum/post/update-'.$id);
            $this->form->setEntity(new PostRecord());
            $this->form->setId('update');
            $this->form->setMethod('POST');   
			
			$manager=new PostManager(PDOFactory::getMysqlConnexion('forum'));
            $record=$manager->get($id);

			FormFactory::buildBBcode($this->form, $this->config);
			
			$this->form->add(new TextField([
				'id'=>'textBBCode',
				'name'=>'textBBCode',
				'value'=>$record->getTextBBCode(),
				'label'=>$this->config['field']['text'],
				'tooltip'=>$this->config['tooltip']['text'],
				'validator'=>array(new RequiredValidator, new MaxLengthValidator(30000))
			]));
			
			if(!empty($redirect)){
				$this->form->add(new HiddenField([
				'id'=>'redirect',
				'name'=>'redirect',
				'value'=>$redirect,
				'validator'=>array(new RequiredValidator, new MaxLengthValidator(300))
			]));
			}
			
			$this->form->add(new SubmitField([
				'value'=>'Post'
			]));
		}
}
