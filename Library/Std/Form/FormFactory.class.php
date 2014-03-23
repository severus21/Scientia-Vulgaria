<?php
/*
 * name: FormFactory
 * @description :  
 */
 
class FormFactory{
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
		public static function buildBBcode($form, $config){
			$form->add(new ButtonField([
				'id'=>'gras',
				'class'=>'bbcode-button',
				'name'=>'gras',
				'value'=>$config['field']['gras'],
				'br'=>false
			]));
			
			$form->add(new ButtonField([
				'id'=>'italic',
				'class'=>'bbcode-button',
				'name'=>'italic',
				'value'=>$config['field']['italic'],
				'br'=>false
			]));
			$form->add(new ButtonField([
				'id'=>'souligne',
				'class'=>'bbcode-button',
				'name'=>'souligne',
				'value'=>$config['field']['souligne'],
				'br'=>false
			]));
			$form->add(new ButtonField([
				'id'=>'lien',
				'class'=>'bbcode-button',
				'name'=>'lien',
				'value'=>$config['field']['lien']
			]));
		}



}
