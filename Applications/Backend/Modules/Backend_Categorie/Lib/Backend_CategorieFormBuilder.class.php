<?php
/*
 * name: Backend_CategorieFormBuilder
 * @description : 
 */ 
 
class Backend_CategorieFormBuilder extends FormBuilder{
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
            $this->form->setEntity(new CategorieRecord());
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('insert');
            $this->form->setMethod('POST'); 
                
            $this->form->add(new SelectField(array(
                'id'=>'table',
                'label'=>$this->config['field']['table'],
                'name'=>'table', 
                'optgroups'=>(new Categorie)->getTableOptions(),
                'validators'=>array(new RequiredValidator())
            ))); 
            
            $this->form->add(new StringField(array(
                'id'=>'nom',
                'label'=>$this->config['field']['nom'],
                'name'=>'nom_fr',
                'tooltip'=>$this->config['tooltip']['nom'],
                'validators'=>array(new RequiredValidator(), new MaxLengthValidator(256))
               )));
                
            $this->form->add(new StringField(array(
                'id'=>'value',
                'label'=>$this->config['field']['value'],
                'name'=>'value_fr',
                'tooltip'=>$this->config['tooltip']['value'],
                'validators'=>array(new RequiredValidator(), new MaxLengthValidator(256))
			)));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Add'
			)));
        }   
        
        public function buildUpdate($record, $table){
            $this->form->setAction('update-'.$record->getId().'-'.$table);
            $this->form->setEntity(new CategorieRecord());
            $this->form->setId('update');
            $this->form->setMethod('POST');
            
            $langueManager=new LangueManager(PDOFactory::getMysqlConnexion());
            $records=$langueManager->getList();
            
			for($i=0; $i<count($records); $i++){
				$getter='getNom_'.$records[$i]->getValue_fr();
				$this->form->add(new StringField(array(
					'class'=>'nom',
					'label'=>$this->config['field']['nom'].'(<em>'.$records[$i]->getNom_fr().'</em>)',
					'name'=>'nom_'.$records[$i]->getValue_fr(),
					'value'=>$record->$getter(),
					'tooltip'=>$this->config['tooltip']['nom'],
					'validators'=>array(new MaxLengthValidator(256))
				  )));
					
				$getter='getValue_'.$records[$i]->getValue_fr();
				$this->form->add(new StringField(array(
					'class'=>'value',
					'label'=>$this->config['field']['value'].'(<em>'.$records[$i]->getNom_fr().'</em>)',
					'name'=>'value_'.$records[$i]->getValue_fr(),
					'tooltip'=>$this->config['tooltip']['value'],
					'value'=>$record->$getter(),
					'validators'=>array(new MaxLengthValidator(256))
				)));
			}
            
            $this->form->add(new SubmitField(array(
                'value'=>'Add'
                )));
        }
        
        public function buildSearch(){
			$this->form->setAction('index');
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('index');
            $this->form->setMethod('POST'); 
                
            $this->form->add(new SelectField(array(
                'id'=>'categorie',
                'label'=>$this->config['field']['table'],
                'name'=>'categorie', 
                'optgroups'=>(new Categorie)->getTableOptions(),
                'validators'=>array(new RequiredValidator())
            ))); 
            
            $this->form->add(new SelectField(array(
                'id'=>'lang',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'lang', 
                'label'=>$this->config['field']['lang'],
                'tooltip'=>$this->config['tooltip']['lang'],
                'optgroups'=>(new Langue)->getCategorieOptions('',new Langue(['nom'=>'','value'=>'']))
            ))); 
             
            $this->form->add(new SubmitField(array(
                'value'=>'Go'
			)));
		}
}
