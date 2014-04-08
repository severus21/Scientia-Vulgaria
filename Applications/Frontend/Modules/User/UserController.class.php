<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class UserController extends BackController{
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
        
        /*
         * 
         * name: inconnu
         * @description: traitement requete XHR
         * @return
         * 
         *//*
        public function executeLoginExists(){
            $manager=$this->managers->getManagerOf('User');
            $userRec=new UserRecord();
            
            if(!$this->httpRequette->postExists('login') || !$userRec->setLogin($this->httpRequette->postExists('login')) || $manager->exists($this->httpRequette->postExists('login'))){
                $this->page->addVar('reponse','false');
                return;
            }
            $this->page->addVar('reponse','true');
            return;
        }
        
        public function executeEmailExists(){
            $manager=$this->managers->getManagerOf('User');
            $userRec=new UserRecord();
            
            if(!$this->httpRequette->postExists('email') || !$userRec->setLogin($this->httpRequette->postExists('email')) || $manager->exists($this->httpRequette->postExists('email'))){
                $this->page->addVar('reponse','false');
                return;
            }
            $this->page->addVar('reponse','true');
            return;
        }*/
        
        public function executeDelete($id=null){
			$manager=$this->managers->getManagerOf('User');
			//Si c'est un moderateur qui supprime un compte
			if(isset($id))
				throw new Exception('Pas encore écrit');
			//Sinon
			if(empty($id)){
				$password=$this->httpRequette->postData('password');
				
				$formBuilder = new UserFormBuilder(null, $this->config);
				$formBuilder->buildUpdateEmail();
				$formBuilder->setValues(array('password'=>$password));
				
				if(!$formBuilder->getForm()->isValid()){
					
					$this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
					$this->httpReponse->redirect('update-index');
				}
				
				if(Cryptographie::str2SHA512($password)!==$this->app->getUser()->getPassword()){
					$this->app->getUser()->setFlash($this->config['flash']['invalid-password']);
					$this->httpReponse->redirect('update-index');
				}
							
				if($manager->delete($this->app->getUser()->getId())){
					$this->app->getUser()->setFlash($this->config['flash']['successful-delete']);
					$this->httpReponse->redirect('/user/deconnexion');
				}else{
					$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
					$this->httpReponse->redirect('update-index');
				}
			}
		}
        
        public function executeInsertIndex(){
            $this->page->addVar('title', 'Inscription');
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
                $formBuilder = new UserFormBuilder(null, $this->config);
                $formBuilder->buildInsert();
                $this->page->addVar('form', $formBuilder->getForm()->createView());
            }
        }
        
        public function executeInsert(){
            $log=new Log();
            
            $login=$this->httpRequette->postData('login');
            $password=$this->httpRequette->postData('password');
            $password2=$this->httpRequette->postData('password2');
            $nom=$this->httpRequette->postData('nom');
            $prenom=$this->httpRequette->postData('prenom');
            $anniversaire=$this->httpRequette->postData('anniversaire');
            $email=$this->httpRequette->postData('email');
            
            $formBuilder = new UserFormBuilder(null, $this->config);
            $formBuilder->buildInsert(true);
            $formBuilder->setValues(array('login'=> $login,
                                            'password'=>$password,
                                            'password2'=>$password2,
                                            'nom'=>$nom,
                                            'prenom'=>$prenom,
                                            'anniversaire'=>$anniversaire,
                                            'email'=>$email,
                                            'captcha'=>$this->httpRequette->postData('captcha')));
            
            if(!$formBuilder->getForm()->isValid()){
                $formBuilder->getForm()->newCaptcha();
                $this->httpRequette->addSessionVar('form', $formBuilder->getForm()->createView());
                
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('insert-index');
            }
                    
            if($password!==$password2){
                $this->app->getUser()->setFlash($this->config['flash']['password-not-match']);
                $this->httpReponse->redirect('insert-index');
            }
            
            $manager=$this->managers->getManagerOf('User');  
            $record=$formBuilder->getForm()->getEntity();  
            $record->setAccreditation(User::ACCREDITATION_STANDARDE);
            $record->setStatut(User::STATUT_MEMBRE);
            $record->setPassword(Cryptographie::str2SHA512($password));
            $record->setDateCreation( date('Y-m-d H:i:s') );
            
            $record->setId($manager->save($record));
            $i=$record->getId();
            if(is_numeric($i)){
                $objFac=new ObjectFactory();
                $this->app->setUser($objFac->buildObject($record));
                $this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);
                $this->httpRequette->sessionUnset('form');
                $log->log('User', 'inscription', 'inscription=true,'.$login);
                $this->httpReponse->redirect('/');
            }elseif(is_string($ret)){
                $this->app->getUser()->setFlash($ret);
                $this->httpReponse->redirect('insert-index');
            }else{
                $this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
                $log->log('user', 'inscription', 'inscription=false, enregistrement failed');
                $this->httpReponse->redirect('insert-index');              
            } 
        }
	
		public function executeConnexion(){
            $this->page->addVar('title', 'Connexion');
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
                $formBuilder = new UserFormBuilder(null, $this->config);
                $formBuilder->buildConnexion();
                $this->page->addVar('form', $formBuilder->getForm()->createView());
            }
        }
	
		public function executeConnect(){
            $log=new Log();
            
            $login=$this->httpRequette->postData('login');
            $password=$this->httpRequette->postData('password');
            $formBuilder = new UserFormBuilder(null, $this->config);
            $formBuilder->buildConnexion();
            $formBuilder->setValues(array('login'=> $login,
                                            'password'=>$password));

            if(!$formBuilder->getForm()->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('connexion');
            }

            $manager=$this->managers->getManagerOf('User');
            $userRec=$manager->getLogin($login);
            $objFac=new ObjectFactory();
                
            if(is_object($userRec)){
                if(Cryptographie::str2SHA512($password)==$userRec->getPassword()){
					$user=$objFac->buildObject($userRec);	
                    session_regenerate_id();
                    $this->app->setUser($user);
                    $this->app->getUser()->setFlash($this->config['flash']['successful-connexion']);
                    $log->log('Access','Connexion', $userRec->getLogin().'=true', Log::GRAN_MONTH);
                    $this->httpRequette->sessionUnset('form');
                    $this->httpReponse->redirect('/');
                }else{
                    $this->app->getUser()->setFlash($this->config['flash']['invalid-password']);
                    $log->log('Access','Connexion', $userRec->getLogin().'=false, wrong password', Log::GRAN_MONTH);
                    $this->httpReponse->redirect('connexion');
                }
            }else{
                $this->app->getUser()->setFlash($this->config['flash']['invalid-login']);
                $this->httpReponse->redirect('connexion');
            }
        }
        
        public function executeDeconnexion(){
			$this->app->setUser(new User());
			$this->app->getUser()->setFlash($this->config['flash']['successful-deconnexion']);
			$this->httpReponse->redirect('/');
		}
        
        public function executeUpdateIndex(){
			$this->page->addVar('title', 'Update info');
            if($this->httpRequette->sessionExists('form')){
                $this->page->addVar('form', $this->httpRequette->sessionData('form'));
                $this->httpRequette->sessionUnset('form');
            }else{
                $formBuilder = new UserFormBuilder(null, $this->config);
                $form='';
                
                //Modification de l'email
                $formBuilder->buildUpdateEmail();
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                //Modification du mot de passe
                $formBuilder->buildUpdatePassword();
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                //Suppression du compte
                $formBuilder->buildDelete();
                $form.=$formBuilder->getForm()->createView();
                $formBuilder->getForm()->purgeFields();
                
                
                $this->page->addVar('form', $form);
            }
		}
		
		public function executeUpdateEmail(){
			$manager=$this->managers->getManagerOf('User');
			
			$password=$this->httpRequette->postData('password');
            $email=$this->httpRequette->postData('email');
            
            $formBuilder = new UserFormBuilder(null, $this->config);
            $formBuilder->buildUpdateEmail();
            $formBuilder->setValues(array('password'=>$password,
                                            'email'=>$email
                                            ));
            
            if(!$formBuilder->getForm()->isValid()){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('update-index');
            }
			
			if(Cryptographie::str2SHA512($password)!==$this->app->getUser()->getPassword()){
				$this->app->getUser()->setFlash($this->config['flash']['invalid-password']);
				$this->httpReponse->redirect('update-index');
			}
			
			$this->app->getUser()->getRecord()->setEmail($email);
			$this->app->getUser()->setEmail($email);
			if($manager->save($this->app->getUser()->getRecord())){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);
				$this->httpRequette->sessionUnset('form');
				$this->httpReponse->redirect('gestion');
			}else{
				$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				$this->httpReponse->redirect('update-index');
			}
		}
		
		public function executeUpdatePassword(){
			$manager=$this->managers->getManagerOf('User');
			
			$password=$this->httpRequette->postData('password');
            $newPassword=$this->httpRequette->postData('newPassword');
			$password2=$this->httpRequette->postData('password2');
            
            $formBuilder = new UserFormBuilder(null, $this->config);
            $formBuilder->buildUpdatePassword();
            $formBuilder->setValues(array('password'=>$password,
                                            'newPassword'=>$newPassword,
                                            'password2'=>$password2
                                            ));
            $a=$this->app->getUser()->getRecord()->setPassword($newPassword);
            $b=$formBuilder->getForm()->isValid();
            if(!$a || !$b){
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('update-index');
            }
			
			if(Cryptographie::str2SHA512($password)!==$this->app->getUser()->getPassword()){
				$this->app->getUser()->setFlash($this->config['flash']['invalid-password']);
				$this->httpReponse->redirect('update-index');
			}
			if($newPassword!==$password2){
				$this->app->getUser()->setFlash($this->config['flash']['password-not-match']);
                $this->httpReponse->redirect('insert-index');
			}	
			
			
			$this->app->getUser()->getRecord()->setPassword(Cryptographie::str2SHA512($newPassword));
			$this->app->getUser()->setPassword( $this->app->getUser()->getRecord()->getPassword() );
			
			$this->httpRequette->sessionUnset('form');
			if($manager->save($this->app->getUser()->getRecord())){
				$this->app->getUser()->setFlash($this->config['flash']['sucessful-record']);
				$this->httpReponse->redirect('gestion');
			}else{
				$this->app->getUser()->setFlash($this->config['flash']['mysql-record-failed']);
				$this->httpReponse->redirect('update-index');
			}
		}
		
		public function executeGestion(){
			$this->page->addVar('title', 'Profile');
			
			$userNavBuilder=new UserNavBuilder(null, $this->config);
			$this->page->addVar('secondaryTopNav', $userNavBuilder->buildGestionTop());
			
		}
		
		
		public function executePasswordIndex(){
            $formBuilder = new UserFormBuilder(null, $this->config);
            $formBuilder->buildPasswordForgotten();

            
            $this->page->addVar('title', 'Mot de passe oublié ?');
            $this->page->addVar('form', $formBuilder->getForm()->createView());
            
        }

        public function executePasswordForgotten(){
            $manager=$this->managers->getManagerOf('User');
            $log=new Log();
            
            $formBuilder = new UserFormBuilder(null, $this->config);
            $formBuilder->buildPasswordForgotten(true);
            $formBuilder->setValues(array('email'=>$this->httpRequette->postData('email'),
                                            'captcha'=>$this->httpRequette->postData('captcha')));
            
            if(!$formBuilder->getForm()->isValid()){
                $formBuilder->getForm()->newCaptcha();
                $this->httpRequette->addSessionVar('form', $formBuilder->getForm()->createView());
                
                $this->app->getUser()->setFlash($this->config['flash']['invalid-form']);
                $this->httpReponse->redirect('passwordindex');
            } 
            
            $requete=new Requete();
            $requete->addCol(new StringCol(['name'=>'email', 'strict'=>true, 'value'=>$this->httpRequette->postData('email')]));
            $tmpRecords=$manager->getList($requete);
            
            if(count($tmpRecords)==0){
				$formBuilder->getForm()->newCaptcha();
                $this->httpRequette->addSessionVar('form', $formBuilder->getForm()->createView());
                $this->app->getUser()->setFlash($this->config['flash']['unknow-mail']);
                $this->httpReponse->redirect('passwordindex');
			}
			
			$record=&$tmpRecords[0];
			$newPassword=Cryptographie::randomPassword();
			$record->setPassword(Cryptographie::str2SHA512($newPassword));
			
            //Redaction du mail  
            $mail = new PHPMailer;

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp1.scientiavulgaria.org';  // Specify main and backup server
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = 'jswan';                            // SMTP username
			$mail->Password = 'secret';                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

			$mail->From = 'noreply@scientiavulgaria.org';
			$mail->FromName = 'Mailer';
			$mail->addAddress($record->getEmail());  // Add a recipient
			//$mail->addAddress('ellen@example.com');               // Name is optional
			//$mail->addReplyTo('info@example.com', 'Information');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');

			$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = $this->config['view']['mail-subject'];
			$mail->Body    = $this->config['view']['mail-begin-bodyhtml'].$newPassword.$this->config['view']['mail-end-bodyhtml'];
			$mail->AltBody = $this->config['view']['mail-begin-body'].$newPassword.$this->config['view']['mail-end-body'];

			if(!$mail->send()) {
				$log->log('PHPMailer','password_forgotten', $record->getLogin().'=false, '.$mail->ErrorInfo, Log::GRAN_MONTH);
				$this->app->getUser()->setFlash($this->config['flash']['send-mail-failed']);
				$this->httpReponse->redirect('passwordIndex');
			}
			$log->log('Access','password_forgotten', $userRec->getLogin().'=true', Log::GRAN_MONTH);
			$this->app->getUser()->setFlash($this->config['flash']['successful-mail-send']);
			$manager->save($record);
			$this->httpReponse->redirect('/');            
        }
}
