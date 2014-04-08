<?php
/*
 * name: SaisonFormBuilder
 * @description : 
 */ 
 
class SaisonFormBuilder extends FormBuilder{
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
			$httpRequete=new HttpRequette();
            $this->form->setAction('insert-'.$httpRequete->getData('id'));
            $this->form->setEntity(new SaisonRecord());
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('insert');
            $this->form->setMethod('POST');   
            
            $this->form->add(new NumberField(array(
                'id'=>'n',
                'required'=>true,
                'label'=>$this->config['field']['numero'],
                'name'=>'n',
                'size'=>50,
                'tooltip'=>$this->config['tooltip']['numero'],
                'validators'=>array(new MinValidator(0), new MaxValidator(256), new RequiredValidator())
                )));   
            
            $this->form->add(new TextField(array(
                'id'=>'resume',
                'label'=>$this->config['field']['resume'],
                'name'=>'resume',
                'rows'=>10,
                'cols'=>50,
                'tooltip'=>$this->config['tooltip']['resume'],
                'validators'=>array(new MaxLengthValidator(2000))
                )));
            
            $this->form->add(new DateField(array(
                'id'=>'date',
                'label'=>$this->config['field']['date'],
                'name'=>'date',
                'size'=>50,
                'tooltip'=>$this->config['tooltip']['date'],
                'validators'=>array(new MaxLengthValidator(2000), new DateValidator())
                )));    
                
			$this->form->add(new NumberField(array(
                'id'=>'nbrEpisodes',
                'label'=>$this->config['field']['nbrEpisodes'],
                'name'=>'nbrEpisodes',
                'size'=>10,
                'tooltip'=>$this->config['tooltip']['nbrEpisodes'],
                'validators'=>array(new MaxValidator(256), new MinValidator(0))
                )));
                
            $this->form->add(new ImageField(array(
                'id'=>'miniature',
                'label'=>$this->config['field']['miniature'],
                'name'=>'miniature',
                'required'=>true,
                'size'=>40,
                'tooltip'=>$this->config['tooltip']['miniature']
                )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Upload'
                )));
        }   
        
        public function buildUpdateInfo($id){
            $this->form->setAction('update-info-'.$id);
            $this->form->setEntity(new SaisonRecord());
            $this->form->setId('update_info');
            $this->form->setMethod('POST');
            
            $manager=new SaisonManager(PDOFactory::getMysqlConnexion());
            $record=$manager->get($id);
            $objectFactory=new ObjectFactory();
            $obj=$objectFactory->buildObject($record);
            
            $this->form->add(new NumberField(array(
                'id'=>'n',
                'label'=>$this->config['field']['numero'],
                'name'=>'n',
                'value'=>(string)$obj->getN(),
                'size'=>50,
                'tooltip'=>$this->config['tooltip']['numero'],
                'validators'=>array(new MinValidator(0), new MaxValidator(256), new RequiredValidator())
                )));    
            
            $this->form->add(new TextField(array(
                'id'=>'resume',
                'label'=>$this->config['field']['resume'],
                'name'=>'resume',
                'value'=>$obj->getResume(),
                'rows'=>10,
                'cols'=>50,
                'tooltip'=>$this->config['tooltip']['resume'],
                'validators'=>array(new MaxLengthValidator(2000))
                )));
            
            $this->form->add(new DateField(array(
                'id'=>'date',
                'label'=>$this->config['field']['date'],
                'name'=>'date',
                'value'=>$obj->getDate(),
                'size'=>50,
                'tooltip'=>$this->config['tooltip']['date'],
                'validators'=>array(new MaxLengthValidator(2000), new DateValidator())
                )));    
				
			$this->form->add(new NumberField(array(
                'id'=>'nbrEpisodes',
                'label'=>$this->config['field']['nbrEpisodes'],
                'name'=>'nbrEpisodes',
                'value'=>(string)$obj->getNbrEpisodes(),
                'size'=>10,
                'tooltip'=>$this->config['tooltip']['nbrEpisodes'],
                'validators'=>array(new MaxValidator(256), new MinValidator(0))
                )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Update'
                )));
        }
        
        public function buildUpdateMiniature($id){
            $this->form->setAction('update-miniature-'.$id);
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('update_miniature');
            $this->form->setMethod('POST');
            
           
            $this->form->add(new ImageField(array(
                'id'=>'miniature',
                'label'=>$this->config['field']['miniature'],
                'name'=>'miniature',
                'required'=>true,
                'size'=>40,
                'tooltip'=>$this->config['tooltip']['miniature']
            )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Upload'
            )));
        }        
}

