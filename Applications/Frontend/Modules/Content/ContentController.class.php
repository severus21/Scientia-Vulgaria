<?php
/*
 * name: ContentController
 * @description : 
 */

class ContentController extends MiddleController{ 
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
         * name: executeDelete
         * @param
         * @return
         * @description
         * 
         */
        public function executeDelete($id){
            $manager=$this->managers->getManagerOf('Content');
            $manager->delete($id);
        }
        
        public function executeInsert($data){
            $record=new ContentRecord();
            $record->setContent(
				isset($data['content']) ? $data['content'] : null,
				isset($data['min']) ? $data['min'] : null,
				isset($data['max']) ? $data['max'] : null,
				isset($data['regExp']) ? $data['regExp'] : null);
				
            isset($data['id']) ? $record->setId($data['id']) : null;
            
            if(!$record->isValid())
                return false;
            $manager=$this->managers->getManagerOf('Content');  
            
            $record->setId($manager->save($record));
            if(!is_numeric($record->getId())){
                $this->app->getUser()->setFlash('Echec lors de l\'enregistrement');
                return false;
            }

            $objectFactory=new ObjectFactory();
            return $objectFactory->buildObject($record);
        }
        
        public function executeUpdate($data){
            $this->executeInsert($data);
        }
        
        
}
