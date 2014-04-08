	<?php
/*
 * name: VideoFormBuilder
 * @description : 
 */ 
 
class SoftwareFormBuilder extends FormBuilder{
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
            $this->form->setEntity(new SoftwareRecord());
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('insert');
            $this->form->setMethod('POST');   
            
            $this->form->add(new StringField(array(
                'id'=>'nom',
                'label'=>$this->config['field']['nom'],
                'name'=>'nom',
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['nom'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));   
            
            $this->form->add(new StringField(array(
                'id'=>'developpeur',
                'label'=>$this->config['field']['developpeur'],
                'name'=>'developpeur',
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['developpeur'],
                'validators'=>array(new MaxLengthValidator(256))
                )));   
                
            $this->form->add(new SelectField(array(
                'id'=>'categorie',
                'name'=>'id_softwareCategorie_categorie', 
                'label'=>$this->config['field']['categorie'],
                'tooltip'=>$this->config['tooltip']['categorie'],
                'optgroups'=>(new SoftwareCategorie)->getCategorieOptgroups()
            )));  
                
            $this->form->add(new SelectField(array(
                'id'=>'os',
                'name'=>'id_operatingSystem_os', 
                'label'=>$this->config['field']['os'],
                'tooltip'=>$this->config['tooltip']['os'],
                'optgroups'=>(new OperatingSystem)->getCategorieOptgroups()
            )));  
            
            $this->form->add(new TextField(array(
                'id'=>'description',
                'label'=>$this->config['field']['description'],
                'name'=>'description',
                'rows'=>10,
                'cols'=>50,
                'tooltip'=>$this->config['tooltip']['description'],
                'validators'=>array(new MaxLengthValidator(2000), new RequiredValidator())
                )));
            
            $this->form->add(new DateField(array(
                'id'=>'date',
                'label'=>$this->config['field']['date'],
                'name'=>'date',
                'size'=>50,
                'tooltip'=>$this->config['tooltip']['date'],
                'validators'=>array(new MaxLengthValidator(2000), new DateValidator())
                )));   
                 
            $this->form->add(new SelectField(array(
                'id'=>'langue',
                'name'=>'langue', 
                'label'=>$this->config['field']['langue'],
                'tooltip'=>$this->config['tooltip']['langue'],
                'optgroups'=>(new Langue)->getCategorieOptions()
            )));
            
            $this->form->add(new StringField(array(
                'id'=>'license',
                'label'=>$this->config['field']['license'],
                'name'=>'license',
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['license'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));
                
            $this->form->add(new StringField(array(
                'id'=>'version',
                'label'=>$this->config['field']['version'],
                'name'=>'version',
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['version'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));
                
            $this->form->add(new ImageField(array(
                'id'=>'miniature',
                'label'=>$this->config['field']['miniature'],
                'name'=>'miniature',
                'required'=>true,
                'size'=>40,
                'tooltip'=>$this->config['tooltip']['miniature']
                )));
                
			$this->form->add(new FileField(array(
                'id'=>'file',
                'label'=>$this->config['field']['file'],
                'name'=>'file',
                'size'=>40,
                'tooltip'=>$this->config['tooltip']['file']
                )));
                
            $this->form->add(new FileField(array(
                'id'=>'tutoriel',
                'label'=>$this->config['field']['tutoriel'],
                'name'=>'tutoriel',
                'required'=>false,
                'size'=>40,
                'tooltip'=>$this->config['tooltip']['tutoriel']
                )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Upload'
                )));
        }   
        
        public function buildUpdateInfo($obj){
            $this->form->setAction('update-info-'.$obj->getRecord()->getId());
            $this->form->setEntity(new SoftwareRecord());
            $this->form->setId('update_info');
            $this->form->setMethod('POST');
            
            $this->form->add(new StringField(array(
                'id'=>'nom',
                'label'=>$this->config['field']['nom'],
                'name'=>'nom',
                'value'=>$obj->getNom(),
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['nom'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));   
            
            $this->form->add(new StringField(array(
                'id'=>'developpeur',
                'label'=>$this->config['field']['developpeur'],
                'name'=>'developpeur',
                'value'=>$obj->getDeveloppeur(),
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['developpeur'],
                'validators'=>array(new MaxLengthValidator(256))
                )));   
                
            $this->form->add(new SelectField(array(
                'id'=>'categorie',
                'name'=>'id_softwareCategorie_categorie', 
                'label'=>$this->config['field']['categorie'],
                'tooltip'=>$this->config['tooltip']['categorie'],
                'optgroups'=>(new SoftwareCategorie)->getCategorieOptgroups( $obj->getCategorie()->getRecord()->getId() )
            )));  
                
            $this->form->add(new SelectField(array(
                'id'=>'os',
                'name'=>'id_operatingSystem_os', 
                'label'=>$this->config['field']['os'],
                'tooltip'=>$this->config['tooltip']['os'],
                'optgroups'=>(new OperatingSystem)->getCategorieOptgroups( $obj->getOs()->getRecord()->getId() )
            )));  
            
            $this->form->add(new TextField(array(
                'id'=>'description',
                'label'=>$this->config['field']['description'],
                'name'=>'description',
                'value'=>$obj->getDescription(),
                'rows'=>10,
                'cols'=>50,
                'tooltip'=>$this->config['tooltip']['description'],
                'validators'=>array(new MaxLengthValidator(2000), new RequiredValidator())
                )));

            $this->form->add(new DateField(array(
                'id'=>'date',
                'label'=>$this->config['field']['date'],
                'name'=>'date',
                'value'=>$obj->getDate(),
                'size'=>50,
                'tooltip'=>$this->config['tooltip']['date'],
                'validators'=>array( new DateValidator())
                )));    
            
            $this->form->add(new SelectField(array(
                'id'=>'langue',
                'name'=>'langue', 
                'label'=>$this->config['field']['langue'],
                'tooltip'=>$this->config['tooltip']['langue'],
                'optgroups'=>(new Langue)->getCategorieOptions($obj->getLangue())
            )));
            
            $this->form->add(new StringField(array(
                'id'=>'license',
                'label'=>$this->config['field']['license'],
                'name'=>'license',
                'value'=>$obj->getLicense(),
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['license'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));
                
            $this->form->add(new StringField(array(
                'id'=>'version',
                'label'=>$this->config['field']['version'],
                'name'=>'version',
                'value'=>$obj->getVersion(),
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['version'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Update'
                )));
        }
        
        public function buildUpdateFile($id, $archive){
            $this->form->setAction('update-file-'.$id);
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('update_file');
            $this->form->setMethod('POST');
            
           if(!empty($archive)){
			   $this->form->add(new StringField(array(
					'id'=>'size',
					'label'=>$this->config['field']['size'],
					'value'=>$archive->getSize(),
					'size'=>6,
					'brlabel'=>false,
					'readonly'=>true
				)));
				$this->form->add(new StringField(array(
					'id'=>'type',
					'label'=>$this->config['field']['type'],
					'brlabel'=>false,
					'readonly'=>true,
					'value'=>$archive->getType(),
					'size'=>20
				)));
				$this->form->add(new StringField(array(
					'id'=>'md5',
					'label'=>$this->config['field']['md5'],
					'brlabel'=>false,
					'readonly'=>true,
					'value'=>$archive->getMd5(),
					'size'=>20
				)));
				$this->form->add(new StringField(array(
					'id'=>'sha512',
					'label'=>$this->config['field']['sha512'],
					'brlabel'=>false,
					'readonly'=>true,
					'value'=>$archive->getSha512(),
					'size'=>20
				)));
			}
			$this->form->add(new FileField(array(
				'id'=>'file',
				'label'=>$this->config['field']['file'],
				'name'=>'file',
				'brlabel'=>false,
				'size'=>40,
				'tooltip'=>$this->config['tooltip']['file']
			)));
                
            $this->form->add(new SubmitField(array(
                'value'=>'Upload'
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
        
        public function buildUpdateTutoriel($id){
            $this->form->setAction('update-tutoriel-'.$id);
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('update_tutoriel');
            $this->form->setMethod('POST');
            
            
            $this->form->add(new FileField(array(
                'id'=>'tutoriel',
                'label'=>$this->config['field']['tutoriel'],
                'name'=>'tutoriel',
                'required'=>false,
                'size'=>40,
                'tooltip'=>$this->config['tooltip']['tutoriel']
            )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Upload'
            )));
        }

        public function buildSearch(){
            $this->form->setAction('index');
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
                'id'=>'categorie',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'id_softwareCategorie_categorie', 
                'label'=>$this->config['field']['categorie'],
                'tooltip'=>$this->config['tooltip']['categorie'],
                'optgroups'=>(new SoftwareCategorie)->getCategorieOptgroups('',new SoftwareCategorie(['nom'=>'','value'=>'']))
            )));  
                
            $this->form->add(new SelectField(array(
                'id'=>'os',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'id_operatingSystem_os', 
                'label'=>$this->config['field']['os'],
                'tooltip'=>$this->config['tooltip']['os'],
                'optgroups'=>(new OperatingSystem)->getCategorieOptgroups('',new OperatingSystem(['nom'=>'','value'=>'']))
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
            
            //Order
            $this->form->add(new SelectField(array(
                'id'=>'order',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'order', 
                'label'=>'Ordonner par :',
                'options'=>array(
                    new OptionField(array('value'=>'', 'label'=>'Date d\'ajout', 'selected'=>true)),
                    new OptionField(array('value'=>'date', 'label'=>'Date de sortie')),
                    new OptionField(array('value'=>'langue', 'label'=>'Langue')),
                    new OptionField(array('value'=>'nom', 'label'=>'Nom'))
                )
            ))); 
            
            
            $this->form->add(new SubmitField(array(
                'value'=>'Go'
            )));
            
        }
}

