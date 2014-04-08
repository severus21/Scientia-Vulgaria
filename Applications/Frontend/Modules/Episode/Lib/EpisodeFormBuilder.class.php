<?php
/*
 * name: EpisodeFormBuilder
 * @description : 
 */ 
 
class EpisodeFormBuilder extends FormBuilder{
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
            $this->form->setEntity(new EpisodeRecord());
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('insert');
            $this->form->setMethod('POST');   
            
            $this->form->add(new StringField(array(
                'id'=>'nom',
                'required'=>true,
                'label'=>$this->config['field']['nom'],
                'name'=>'nom',
                'size'=>50,
                'tooltip'=>$this->config['tooltip']['nom'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));   
                
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
            
            $this->form->add(new SelectField(array(
                'id'=>'subtitle',
                'label'=>$this->config['field']['subtitle'],
                'name'=>'subtitle', 
                'tooltip'=>$this->config['tooltip']['subtitle'],
                'optgroups'=>(new Langue)->getCategorieOptions('Aucun',new Langue(['nom'=>'','value'=>'Aucun']))
            )));
            
            $this->form->add(new FileField(array(
                'id'=>'file',
                'label'=>$this->config['field']['file'],
                'name'=>'file',
                'size'=>40,
                'tooltip'=>$this->config['tooltip']['file']
                )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Upload'
                )));
        }   
        
        public function buildUpdateInfo($id){
            $this->form->setAction('update-info-'.$id);
            $this->form->setEntity(new EpisodeRecord());
            $this->form->setId('update_info');
            $this->form->setMethod('POST');
            
            $manager=new EpisodeManager(PDOFactory::getMysqlConnexion());
            $record=$manager->get($id);
            $objectFactory=new ObjectFactory();
            $obj=$objectFactory->buildObject($record);
            
             $this->form->add(new StringField(array(
                'id'=>'nom',
                'required'=>true,
                'label'=>$this->config['field']['nom'],
                'name'=>'nom',
                'value'=>$obj->getNom(),
                'size'=>50,
                'tooltip'=>$this->config['tooltip']['nom'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));
            
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
            
            $subtitle=$obj->getSubtitle();
            if(empty($subtitle) || $subtitle=='Aucun')
				$subtitles=(new Langue)->getCategorieOptions( 'Aucun',new Langue(['nom'=>'','value'=>'Aucun']) );
			else
				$subtitles=(new Langue)->getCategorieOptions( $subtitle );
				
            $this->form->add(new SelectField(array(
                'id'=>'subtitle',
                'name'=>'subtitle', 
                'label'=>$this->config['field']['subtitle'],
                'tooltip'=>$this->config['tooltip']['subtitle'],
                'optgroups'=>  $subtitles
            )));
            
            
            $this->form->add(new SubmitField(array(
                'value'=>'Update'
                )));
        }
         
		public function buildUpdateFile($id, $videos){
            $this->form->setAction('update-file-'.$id);
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('update_file');
            $this->form->setMethod('POST');
            
            $this->form->add(new HiddenField(array(
				'id'=>'deleteListFile',
				'name'=>"deleteListFile",
				'brlabel'=>false,
				'br'=>false
			)));
			
            for($a=0; $a<count($videos); $a++){
				$this->form->add(new StringField(array(
					'id'=>'size',
					'label'=>$this->config['field']['size'],
					'value'=>$videos[$a]->getSize(),
					'size'=>6,
					'brlabel'=>false,
					'readonly'=>true
				)));
				$this->form->add(new StringField(array(
					'id'=>'type',
					'label'=>$this->config['field']['type'],
					'brlabel'=>false,
					'readonly'=>true,
					'value'=>$videos[$a]->getType(),
					'size'=>20
				)));
				$this->form->add(new StringField(array(
					'id'=>'md5',
					'label'=>$this->config['field']['md5'],
					'brlabel'=>false,
					'readonly'=>true,
					'value'=>$videos[$a]->getMd5(),
					'size'=>20
				)));
				$this->form->add(new StringField(array(
					'id'=>'sha512',
					'label'=>$this->config['field']['sha512'],
					'brlabel'=>false,
					'readonly'=>true,
					'value'=>$videos[$a]->getSha512(),
					'size'=>20
				)));
				$this->form->add(new FileField(array(
					'id'=>'file'.$a,
					'label'=>$this->config['field']['file'],
					'name'=>'file'.$a,
					'brlabel'=>false,
					'size'=>40,
					'tooltip'=>$this->config['tooltip']['file']
				)));
				$this->form->add(new HiddenField(array(
					'id'=>'id-file'.$a,
					'name'=>'id-file'.$a,
					'value'=> (string)$videos[$a]->getRecord()->getId(),
					'brlabel'=>false,
					'br'=>false
				)));
				$this->form->add(new HiddenField(array(
					'br'=>true
				)));
			}
                
            $this->form->add(new SubmitField(array(
                'value'=>'Upload'
            )));
        }
            
}

