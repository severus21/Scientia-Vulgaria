<?php
/*
 * name: EbookFormBuilder
 * @description : 
 */ 
 
class EbookFormBuilder extends FormBuilder{
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
            $this->form->setEntity(new EbookRecord());
            $this->form->setFormenctype(Form::ENCTYPE_MULTIPART);
            $this->form->setId('insert');
            $this->form->setMethod('POST');   
            $this->form->add(new StringField(array(
                'id'=>'nom',
                'label'=>$this->config['field']['titre'],
                'name'=>'nom',
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['titre'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));   
            
            $this->form->add(new NumberField(array(
                'id'=>'isbn',
                'label'=>$this->config['field']['isbn'],
                'name'=>'isbn',
                'size'=>50,
                'placeholder'=>'International Standard Book Number',
                'tooltip'=>$this->config['tooltip']['isbn'],
                'validators'=>array()
                )));   
                
            $this->form->add(new StringField(array(
                'id'=>'auteur',
                'label'=>$this->config['field']['auteur'],
                'name'=>'auteur',
                'size'=>50,
                'placeholder'=>$this->config['tooltip']['auteur'],
                'tooltip'=>$this->config['tooltip']['auteur'],
                'validators'=>array(new MaxLengthValidator(256))
                )));   
                
            $this->form->add(new SelectField(array(
                'id'=>'genre',
                'label'=>$this->config['field']['genre'],
                'name'=>'id_ebookCategorie_genre', 
                'tooltip'=>$this->config['tooltip']['genre'],
                'optgroups'=>(new EbookCategorie)->getCategorieOptgroups()
            )));  
            
            $this->form->add(new SelectField(array(
                'id'=>'langue',
                'label'=>$this->config['field']['langue'],
                'name'=>'langue', 
                'tooltip'=>$this->config['tooltip']['langue'],
                'optgroups'=>(new Langue)->getCategorieOptions()
            )));
            
            $this->form->add(new SelectField(array(
                'id'=>'etiquette',
                'name'=>'id_etiquette_etiquette', 
                'label'=>$this->config['field']['etiquette'],
                'tooltip'=>$this->config['tooltip']['etiquette'],
                'optgroups'=>(new Etiquette)->getCategorieOptgroups()
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
                'validators'=>array(new DateValidator())
                )));    
            
            $this->form->add(new StringField(array(
                'id'=>'editeur',
                'label'=>$this->config['field']['editeur'],
                'name'=>'editeur',
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['editeur'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));
                
            $this->form->add(new StringField(array(
                'id'=>'serie',
                'label'=>$this->config['field']['serie'],
                'name'=>'serie',
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['serie'],
                'validators'=>array(new MaxLengthValidator(256))
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
            $this->form->setEntity(new EbookRecord());
            $this->form->setId('update_info');
            $this->form->setMethod('POST');
            
            $manager=new EbookManager(PDOFactory::getMysqlConnexion());
            $record=$manager->get($id);
            $objectFactory=new ObjectFactory();
            $obj=$objectFactory->buildObject($record);
            
            $this->form->add(new StringField(array(
                'id'=>'nom',
                'label'=>$this->config['field']['titre'],
                'name'=>'nom',
                'value'=>$obj->getNom(),
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['titre'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));   
            
            $this->form->add(new NumberField(array(
                'id'=>'isbn',
                'label'=>$this->config['field']['isbn'],
                'name'=>'isbn',
                'value'=>$obj->getIsbn(),
                'size'=>50,
                'placeholder'=>'International Standard Book Number',
                'tooltip'=>$this->config['tooltip']['isbn'],
                'validators'=>array()
                ))); 
            
            $this->form->add(new StringField(array(
                'id'=>'auteur',
                'label'=>$this->config['field']['auteur'],
                'name'=>'auteur',
                'value'=>$obj->getAuteur(),
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['auteur'],
                'validators'=>array(new MaxLengthValidator(256))
                )));   
                
            $this->form->add(new SelectField(array(
                'id'=>'genre',
                'name'=>'id_ebookCategorie_genre', 
                'label'=>$this->config['field']['genre'],
                'tooltip'=>$this->config['tooltip']['genre'],
                'optgroups'=>(new EbookCategorie)->getCategorieOptgroups( $obj->getGenre()->getRecord()->getId() )
            ))); 
             
            $this->form->add(new SelectField(array(
                'id'=>'langue',
                'name'=>'langue', 
                'label'=>$this->config['field']['langue'],
                'tooltip'=>$this->config['tooltip']['langue'],
                'optgroups'=>(new Langue)->getCategorieOptions( $obj->getLangue() )
            )));  
            
            $this->form->add(new SelectField(array(
                'id'=>'etiquette',
                'name'=>'id_etiquette_etiquette', 
                'label'=>$this->config['field']['etiquette'],
                'tooltip'=>$this->config['tooltip']['etiquette'],
                'optgroups'=>(new Etiquette)->getCategorieOptgroups( $obj->getEtiquette()->getRecord()->getId() )
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
                'validators'=>array(new DateValidator())
                )));    
            
            $this->form->add(new StringField(array(
                'id'=>'editeur',
                'label'=>$this->config['field']['editeur'],
                'name'=>'editeur',
                'value'=>$obj->getEditeur(),
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['editeur'],
                'validators'=>array(new MaxLengthValidator(256), new RequiredValidator())
                )));
                
            $this->form->add(new StringField(array(
                'id'=>'serie',
                'label'=>$this->config['field']['serie'],
                'name'=>'serie',
                'value'=>$obj->getSerie(),
                'size'=>50,
                'placeholder'=>'256 carctères max',
                'tooltip'=>$this->config['tooltip']['serie'],
                'validators'=>array(new MaxLengthValidator(256))
                )));
            
            $this->form->add(new SubmitField(array(
                'value'=>'Update'
                )));
        }
        
        public function buildUpdateFile($id, $documents){
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
            for($a=0; $a<count($documents); $a++){
				$this->form->add(new StringField(array(
					'id'=>'size',
					'label'=>$this->config['field']['size'],
					'value'=>$documents[$a]->getSize(),
					'size'=>6,
					'brlabel'=>false,
					'readonly'=>true
				)));
				$this->form->add(new StringField(array(
					'id'=>'type',
					'label'=>$this->config['field']['type'],
					'brlabel'=>false,
					'readonly'=>true,
					'value'=>$documents[$a]->getType(),
					'size'=>20
				)));
				$this->form->add(new StringField(array(
					'id'=>'md5',
					'label'=>$this->config['field']['md5'],
					'brlabel'=>false,
					'readonly'=>true,
					'value'=>$documents[$a]->getMd5(),
					'size'=>20
				)));
				$this->form->add(new StringField(array(
					'id'=>'sha512',
					'label'=>$this->config['field']['sha512'],
					'brlabel'=>false,
					'readonly'=>true,
					'value'=>$documents[$a]->getSha512(),
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
					'value'=> (string)$documents[$a]->getRecord()->getId(),
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
                'id'=>'genre',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'id_ebookCategorie_genre', 
                'label'=>$this->config['field']['genre'],
                'tooltip'=>$this->config['tooltip']['genre'],
                'optgroups'=>(new EbookCategorie)->getCategorieOptgroups('',new EbookCategorie(['nom'=>'','value'=>'']))
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
             
            $this->form->add(new SelectField(array(
                'id'=>'etiquette',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'id_etiquette_etiquette', 
                'label'=>$this->config['field']['etiquette'],
                'tooltip'=>$this->config['tooltip']['etiquette'],
                'optgroups'=>(new Etiquette)->getCategorieOptgroups('',new Etiquette(['nom'=>'','value'=>'']))
            ))); 
            
            //Order
            $this->form->add(new SelectField(array(
                'id'=>'order',
                'br'=>false,
                'brLabel'=>false,
                'name'=>'order', 
                'label'=>$this->config['field']['order'],
                'options'=>array(
                    new OptionField(array('value'=>'', 'label'=>$this->config['field']['option-order-default'], 'selected'=>true)),
                    new OptionField(array('value'=>'date', 'label'=>$this->config['field']['option-order-date'])),
                    new OptionField(array('value'=>'langue', 'label'=>$this->config['field']['option-order-langue'])),
                    new OptionField(array('value'=>'nom', 'label'=>$this->config['field']['option-order-titre']))
                )
            ))); 
            
            
            $this->form->add(new SubmitField(array(
				'br'=>false,
                'brLabel'=>false,
                'value'=>'Go'
            )));
            
        }
}

