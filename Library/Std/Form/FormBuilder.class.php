<?php
/*
 * name: FormBuilder
 * @description : 
 */ 
 
abstract class FormBuilder{
    /*
        Attributs
    */
        protected $form;
        protected $config;
        /*
            Constantes
        */
    /*
        Méthodes générales
    */
        public function __construct($form=null, $config=null){
            if(isset($form) && $form instanceof Form){
                $this->setForm($form);
            }else{
                $this->setForm(new Form());
            }
            isset($config) ? $this->config=$config : $this->buildConfig();
        }
        /*
            Getters
        */
            public function getForm(){
                return $this->form;
            }
        
        /*
            Setters
        */
            public function setForm(Form $form){
                $this->form = $form;
            }
            
            public function setValues($values){
                $i=0;
                foreach($values as $key =>$value){
                    foreach($this->form->getFields() as $field){
                        if($field->getName()==$key){
                            $field->setValue($value);
                        }
                    }
                }
            }
            
    /*
        Autres méthodes
    */    
		public function buildConfig(){
			$class=get_class($this);
			$classAr=explode('_', $class);
			
			//On recupère l'application
			if(count($classAr)==1){
				$app='Frontend';
			}else{
				$app=$classAr[0];
			}
			
			$module=substr($class, 0, strlen($class)-10);
			
			//On charge la config générale du module

			$appConfig=new AppConfig($app, $module);
			$this->config=$appConfig->load();
		}
}
