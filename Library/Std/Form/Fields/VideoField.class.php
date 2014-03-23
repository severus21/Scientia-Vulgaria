<?php
/*
 * name: VideoField
 * @description : 
 */ 
 
 class VideoField extends Field{
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
        public function buildWidget(){
            $widget = $this->buildLabel();
            $widget .= '<input type="file" '.$this->id.' name="'.$this->name.'" accept="video/*" '.$this->size.' '.$this->required.' '.$this->readonly.'/>';       
            return  $widget.$this->buildTooltip();
        }
}
