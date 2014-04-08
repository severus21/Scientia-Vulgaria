<?php
/*
 * name: InscriptionFormBuilder
 * @description : 
 */ 
 
class UserFormBuilder extends FormBuilder{
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
		public function buildDelete(){
			$this->form->setAction('delete');
            $this->form->setId('delete');
            $this->form->setMethod('POST');
                
			 $this->form->add(new PasswordField(array(
                'id'=>'password',
                'label'=>$this->config['field']['password'],
                'maxlength'=>30,
                'name'=>'password',
                'size'=>26,
                'reiquired'=>true,
                'validators'=>array(new RequiredValidator(),new MaxLengthValidator(30))
                )));
			$this->form->add(new SubmitField(array(
                'value'=>'Supprime le compte.'
                )));
		}
    
        public function buildInsert($cap=false){
            $this->form->setAction('insert');
            $this->form->setEntity(new UserRecord());
            $this->form->setId('insert');
            $this->form->setMethod('POST');
            
            $this->form->add(new StringField(array(
                'id'=>'login',
                'label'=>$this->config['field']['login'],
                'maxlength'=>20,
                'name'=>'login',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['login'],
                'validators'=>array(new RequiredValidator(), new MaxLengthValidator(20))
                )));
            
            $this->form->add(new PasswordField(array(
                'id'=>'password',
                'label'=>$this->config['field']['password'],
                'maxlength'=>30,
                'name'=>'password',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['password'],
                'validators'=>array(new RequiredValidator(),new MaxLengthValidator(30))
                )));
            
            $this->form->add(new PasswordField(array(
                'id'=>'password2',
                'label'=>$this->config['field']['password2'],
                'name'=>'password2',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['password2'],
                'validators'=>array(new RequiredValidator())
                )));
                    
            $this->form->add(new StringField(array(
                'id'=>'nom',
                'label'=>$this->config['field']['nom'],
                'maxlength'=>20,
                'name'=>'nom',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['nom'],
                'validators'=>array(new RequiredValidator(), new MaxLengthValidator(20))
                )));
                    
                    
            $this->form->add(new StringField(array(
                'id'=>'prenom',
                'label'=>$this->config['field']['prenom'],
                'maxlength'=>20,
                'name'=>'prenom',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['prenom'],
                'validators'=>array(new RequiredValidator(), new MaxLengthValidator(20))
                )));       
            
           $this->form->add(new DateField(array(
                'id'=>'anniversaire',
                'label'=>$this->config['field']['anniversaire'],
                'name'=>'anniversaire',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['anniversaire'],
                'validators'=>array(new RequiredValidator(), new DateValidator())
                )));        
             
            $this->form->add(new EmailField(array(
                'id'=>'email',
                'label'=>$this->config['field']['email'],
                'maxlength'=>256,
                'name'=>'email',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['email'],
                'validators'=>array(new RequiredValidator(), new EmailValidator(), new MaxLengthValidator(256))
                )));       
                    
            $this->form->add(new CaptchaField(array(
                'id'=>'captcha',
                'captcha'=>new Captcha($cap),
                'label'=>$this->config['field']['captcha'],
                'name'=>'captcha',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['captcha'],
                'validators'=>array(new RequiredValidator(), new CaptchaValidator())
                )));
                
            $this->form->add(new SubmitField(array(
                'value'=>'Valider'
                )));
        }   

		public function buildConnexion(){
            $this->form->setAction('connect');
            $this->form->setId('connexion');
            $this->form->setMethod('POST');
            
            $this->form->add(
                new StringField(array(
                    'id'=>'login',
                    'label'=>$this->config['field']['login'],
                    'name'=>'login',
                    'required'=>true,
                    'size'=>26,
                    'tooltip'=>$this->config['tooltip']['login'],
                    'validators'=>array(new RequiredValidator())
                    )));
            
            $this->form->add(
                new PasswordField(array(
                    'id'=>'password',
                    'label'=>$this->config['field']['password'],
                    'name'=>'password',
                    'size'=>26,
                    'tooltip'=>$this->config['tooltip']['password'],
                    'validators'=>array(new RequiredValidator())
                    )));
     
            $this->form->add(
                new SubmitField(array(
                    'value'=>'Connexion'
                    )));
        }   
	
		public function buildPasswordForgotten($cap=false){
            $this->form->setAction('passwordForgotten');
            $this->form->setId('passwordForgotten');
            $this->form->setMethod('POST');
            
            $this->form->add(new EmailField(array(
                'id'=>'email',
                'label'=>$this->config['field']['email'],
                'maxlength'=>256,
                'name'=>'email',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['email'],
                'validators'=>array(new RequiredValidator(), new EmailValidator(), new MaxLengthValidator(256))
                )));       
                    
            $this->form->add(new CaptchaField(array(
                'id'=>'captcha',
                'captcha'=>new Captcha($cap),
                'label'=>$this->config['field']['captcha'],
                'name'=>'captcha',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['captcha'],
                'validators'=>array(new RequiredValidator(), new CaptchaValidator())
                )));
                
                    
            $this->form->add(
                new SubmitField(array(
                    'value'=>'Réinitialiser'
                    )));
        }

		public function buildUpdateEmail(){
			$this->form->setAction('update-email');
            $this->form->setId('updateEmail');
            $this->form->setMethod('POST');
            
			$this->form->add(new EmailField(array(
                'id'=>'email',
                'label'=>$this->config['field']['new-email'],
                'maxlength'=>256,
                'name'=>'email',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['email'],
                'validators'=>array(new RequiredValidator(), new EmailValidator(), new MaxLengthValidator(256))
                )));  
                
			 $this->form->add(new PasswordField(array(
                'id'=>'password',
                'label'=>$this->config['field']['password'],
                'maxlength'=>30,
                'name'=>'password',
                'size'=>26,
                'reiquired'=>true,
                'validators'=>array(new RequiredValidator(),new MaxLengthValidator(30))
                )));
			$this->form->add(new SubmitField(array(
                'value'=>'Valider'
                )));
		}

		public function buildUpdatePassword(){
			$this->form->setAction('update-password');
            $this->form->setId('updatePassword');
            $this->form->setMethod('POST');
            
            $this->form->add(new PasswordField(array(
                'id'=>'newPassword',
                'label'=>$this->config['field']['new-password'],
                'maxlength'=>30,
                'name'=>'newPassword',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['password'],
                'validators'=>array(new RequiredValidator(),new MaxLengthValidator(30))
                )));
            $this->form->add(new PasswordField(array(
                'id'=>'password2',
                'label'=>$this->config['field']['password2'],
                'maxlength'=>30,
                'name'=>'password2',
                'required'=>true,
                'size'=>26,
                'tooltip'=>$this->config['tooltip']['password2'],
                'validators'=>array(new RequiredValidator(),new MaxLengthValidator(30))
                )));
            
            $this->form->add(new PasswordField(array(
                'id'=>'password',
                'label'=>$this->config['field']['password'],
                'name'=>'password',
                'required'=>true,
                'size'=>26,
                'validators'=>array(new RequiredValidator())
                )));
			$this->form->add(new SubmitField(array(
                'value'=>'Valider'
                )));
		}
}
