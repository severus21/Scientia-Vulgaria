<?php
/*
 * name: SerieFormBuilder
 * @description : 
 */ 
 
class SerieFormBuilder extends FormBuilder{
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
            $this->form->setEntity(new SerieRecord());
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
                'name'=>'id_serieCategorie_categorie', 
                'tooltip'=>$this->config['tooltip']['categorie'],
                'optgroups'=>(new SerieCategorie)->getCategorieOptgroups()
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
			
			$this->form->add(new NumberField(array(
                'id'=>'nbrSaisons',
                'label'=>$this->config['field']['nbrSaisons'],
                'name'=>'nbrSaisons',
                'size'=>10,
                'tooltip'=>$this->config['tooltip']['nbrSaisons'],
                'validators'=>array(new MaxValidator(256), new MinValidator(0))
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
            $this->form->setEntity(new SerieRecord());
            $this->form->setId('update_info');
            $this->form->setMethod('POST');
            
            $manager=new SerieManager(PDOFactory::getMysqlConnexion());
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
                'name'=>'id_serieCategorie_categorie', 
                'label'=>$this->config['field']['categorie'],
                'tooltip'=>$this->config['tooltip']['categorie'],
                'optgroups'=>(new SerieCategorie)->getCategorieOptgroups( $obj->getCategorie()->getRecord()->getId() )
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
				
			$this->form->add(new NumberField(array(
                'id'=>'nbrSaisons',
                'label'=>$this->config['field']['nbrSaisons'],
                'name'=>'nbrSaisons',
                'value'=>$obj->getNbrSaisons(),
                'size'=>10,
                'tooltip'=>$this->config['tooltip']['nbrSaisons'],
                'validators'=>array(new MaxValidator(256), new MinValidator(0))
                )));
				
			$this->form->add(new NumberField(array(
                'id'=>'nbrEpisodes',
                'label'=>$this->config['field']['nbrEpisodes'],
                'name'=>'nbrEpisodes',
                'value'=>$obj->getNbrEpisodes(),
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
                'name'=>'id_serieCategorie_categorie', 
                'label'=>$this->config['field']['categorie'],
                'tooltip'=>$this->config['tooltip']['categorie'],
                'optgroups'=>(new SerieCategorie)->getCategorieOptgroups('',new SerieCategorie(['nom'=>'','value'=>'']))
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

