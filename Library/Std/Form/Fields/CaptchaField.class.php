<?php
/*
 * name:CaptchaField
 * @description : 
 */ 
 
 class CaptchaField extends Field{
    /*
        Attributs
    */
        protected $captcha;
        protected $maxLength='maxlength="64"';
        protected $placeholder='';
        
        
        
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
            public function setCaptcha($captcha){
                if($captcha instanceof Captcha){
                    $this->captcha=$captcha;
                }else{
                    throw new RuntimeException('captcha doit être une insatance de Captcha');
                }
            }
          
            public function setMaxLength($maxLength){
                $maxLength = (int) $maxLength;
                if($maxLength > 0){
                    $this->maxLength='maxlength="'.$maxLength.'"';
                }else{
                    throw new RuntimeException('La longueur maximale doit être un nombre supérieur à 0');
                }
            }
            
            public function setPlaceholder($placeholder){
                if(is_string($placeholder)){
                    $this->placeholder = 'placeholder="'.$placeholder.'"';
                }else{
                    throw new RuntimeRuntimeException('Placeholder doit être une string');
                }
            }
    /*
        Autres méthodes
    */  
        public function buildWidget(){
            if(isset($this->captcha)){
                $widget=$this->buildLabel();
                $widget .='<img src="../..'.substr($this->captcha->getImg(),6).'" alt="captcha"/><br/>
                        <input type="text" name="'.$this->name.'" '.$this->id.' '.$this->size.' '.$this->maxLength.'
                         '.$this->placeholder.'  required '.$this->readonly.'/>';
                            
                return  $widget.$this->buildTooltip();
            }else{
                throw new RuntimeException('l attribut captcha doit exister');
            }
        }
            
        public function isValid(){
            foreach($this->validators as $validator){
                if($validator instanceof CaptchaValidator){
                    if(!$validator->isValid($this->value, $this->captcha)){
                        $this->error=true;
                        return false;
                    }
                }elseif(!$validator->isValid($this->value)){
                    $this->error=true;
                    return false;
                }
            }
            return true;
        }
        
}
