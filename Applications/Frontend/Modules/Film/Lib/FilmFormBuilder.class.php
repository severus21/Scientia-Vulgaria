<?php
/*
 * name: FilmFormBuilder
 * @description : 
 */ 
 
class FilmFormBuilder extends FormBuilder{
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
            $this->form->setEntity(new FilmRecord());
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
                'id'=>'acteurs',
                'label'=>$this->config['field']['acteurs'],
                'name'=>'acteurs',
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['acteurs'],
                'validators'=>array(new MaxLengthValidator(256))
                )));   
                
            $this->form->add(new SelectField(array(
                'id'=>'categorie',
                'label'=>$this->config['field']['categorie'],
                'name'=>'id_filmCategorie_categorie', 
                'tooltip'=>$this->config['tooltip']['categorie'],
                'optgroups'=>(new FilmCategorie)->getCategorieOptgroups()
            )));  
                
            $this->form->add(new SelectField(array(
                'id'=>'langue',
                'label'=>$this->config['field']['langue'],
                'name'=>'langue', 
                'tooltip'=>$this->config['tooltip']['langue'],
                'optgroups'=>(new Langue)->getCategorieOptions()
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
            
            $this->form->add(new StringField(array(
                'id'=>'realisateur',
                'label'=>$this->config['field']['realisateur'],
                'name'=>'realisateur',
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['realisateur'],
                'validators'=>array(new MaxLengthValidator(256))
                )));
                
            $this->form->add(new StringField(array(
                'id'=>'saga',
                'label'=>$this->config['field']['saga'],
                'name'=>'saga',
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['saga'],
                'validators'=>array(new MaxLengthValidator(256))
                )));
                
			$this->form->add(new SelectField(array(
                'id'=>'subtitle',
                'label'=>$this->config['field']['subtitle'],
                'name'=>'subtitle', 
                'tooltip'=>$this->config['tooltip']['subtitle'],
                'optgroups'=>(new Langue)->getCategorieOptions('Aucun',new Langue(['nom'=>'','value'=>'Aucun']))
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
            
            $this->form->add(new SubmitField(array(
                'value'=>'Upload'
                )));
        }   
        
        public function buildUpdateInfo($id){
            $this->form->setAction('update-info-'.$id);
            $this->form->setEntity(new FilmRecord());
            $this->form->setId('update_info');
            $this->form->setMethod('POST');
            
            $manager=new FilmManager(PDOFactory::getMysqlConnexion());
            $record=$manager->get($id);
            $objectFactory=new ObjectFactory();
            $obj=$objectFactory->buildObject($record);
            
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
                'id'=>'acteurs',
                'label'=>$this->config['field']['acteurs'],
                'name'=>'acteurs',
                'value'=>$obj->getActeurs(),
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['acteurs'],
                'validators'=>array(new MaxLengthValidator(256))
                )));   
                
            $this->form->add(new SelectField(array(
                'id'=>'categorie',
                'name'=>'id_filmCategorie_categorie', 
                'label'=>$this->config['field']['categorie'],
                'tooltip'=>$this->config['tooltip']['categorie'],
                'optgroups'=>(new FilmCategorie)->getCategorieOptgroups( $obj->getCategorie()->getRecord()->getId() )
            )));  
                
            $this->form->add(new SelectField(array(
                'id'=>'langue',
                'name'=>'langue', 
                'label'=>$this->config['field']['langue'],
                'tooltip'=>$this->config['tooltip']['langue'],
                'optgroups'=>(new Langue)->getCategorieOptions($obj->getLangue())
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
            
            $this->form->add(new StringField(array(
                'id'=>'realisateur',
                'label'=>$this->config['field']['realisateur'],
                'name'=>'realisateur',
                'value'=>$obj->getRealisateur(),
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['realisateur'],
                'validators'=>array(new MaxLengthValidator(256))
                )));
                
            $this->form->add(new StringField(array(
                'id'=>'saga',
                'label'=>$this->config['field']['saga'],
                'name'=>'saga',
                'value'=>$obj->getSaga(),
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['saga'],
                'validators'=>array(new MaxLengthValidator(256))
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
        
        public function buildSearch($action='index'){
            $this->form->setAction($action);
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
                'name'=>'id_filmCategorie_categorie', 
                'label'=>$this->config['field']['categorie'],
                'tooltip'=>$this->config['tooltip']['categorie'],
                'optgroups'=>(new FilmCategorie)->getCategorieOptgroups('',new FilmCategorie(['nom'=>'','value'=>'']))
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
                'label'=>$this->config['field']['order'],
                'options'=>array(
                    new OptionField(array('value'=>'', 'label'=>'Date d\'ajout', 'selected'=>true)),
                    new OptionField(array('value'=>'date', 'label'=>'Date de sortie')),
                    new OptionField(array('value'=>'categorie', 'label'=>'Catégorie')),
                    new OptionField(array('value'=>'nom', 'label'=>'Nom'))
                )
            ))); 
            
            $this->form->add(new SubmitField(array(
                'value'=>'Go'
            )));
            
        }
}

