<?php
/*
 * 
 * name: Config
 * @description permet :
 *          de gérer la configuration.
 * 
 */

class Config{
    /*
        Attributs
    */
        protected $file;
        protected $pattern; //regexp definissant la structure des champs, facultatif non encore implémenter
        protected $xml; 
            
        /*
            Constantes
        */
    
    /*
        Méthodes générales
    */
        public function __construct(array $donnees = array()){
            if (!empty($donnees)){
                $this->hydrate($donnees);
            }
        }
        
        public function hydrate(array $donnees){
            foreach ($donnees as $attribut => $valeur){
                $methode = 'set'.ucfirst($attribut);
                
                if (is_callable(array($this, $methode))){
                    $this->$methode($valeur);
                }
            }
        }
        
        /*
            Getters
        */
           
        /*
            Setters
        */
            public function setFile($file){
                $this->file=$file;
                return true;
            }
            
            public function setPattern($pat){
                $this->pattern=$pat;
            }
    /*
        Autres méthodes
    */
        public function load(){
            if(isset($this->file) && is_file($this->file)){
                $this->xml = new DOMDocument;
                $this->xml->load($this->file);
            }else{
                throw new RuntimeException('Fichier de configuration invalide');
            }
        }
        
        
		/*
         * 
         * name: buildArray
         * @param
         *      $keys : ['key1',...,'keyn'] / 'all' désigne les attributs à renvoyer
         *      $element : DOMElement
         *      $matches : resultat de la regexp si existance
         * @return
         *      mixed array : ['key1'=>'value1',...,'keyn'=>'valuen']; ou [element1 à n]; array() si aucune occurance trouvée
         * 
         */
        public function buildArray($keys=null, $element, $matches=null){
            $array=array();
            if(!empty($keys) && is_array($keys)){
				$i=1;
				for($a=0; $a<count($keys); $a++){
					if($keys[$a]=='vars' && isset($matches[$i])){
						$vars=explode(',',$element->getAttribute($keys[$a]));
						for($b=0; $b<count($vars); $b++){
							$array[$keys[$a]][]= ['var'=>$vars[$b],'value'=>$matches[$i]]; 						
							$i++;
						}
					}else{
						$array[$keys[$a]]= $element->getAttribute($keys[$a]);
					}        
				}
			}elseif('all'==$keys){
				if(!empty($element->attributes)){
					$n=$element->attributes->length;
					for($a=0; $a<$n; $a++){
						$item=$element->attributes->item($a);
						$array[$item->name]=$item->value;
					}
				}
			}else{
				$array[]=$element;
			}
            return $array;
        }
            
        /*
         * 
         * name: getById
         * @param
         * @return
         * 
         */
        public function getById($keys, $id){
            $this->load();
            return $this->buildArray($keys, $this->xml->getElementById($id));
        }
        
        
        /*
         * 
         * name: getByTagName
         * @param
         *      $keys : ['key1',...,'keyn'] / 'all' désigne les attributs à renvoyer
         *      $tagName : nom de l'élément à récupérer
         *      $reg : ['att'=>string :attribut de l'element xml,
         * 				'opt'=>string :option(s) PCRE, 
         *				'strict'=>bool :si la regexp doit englober strictement la chaîne, 
         * 				'value'=>string :la valeur devant être testé]
         * @return
         *      mixed array : ['key1'=>'value1',...,'keyn'=>'valuen']; ou [element1 à n]; array() si aucune occurance trouvée
         * 
         */
        public function getByTagName($keys=null, $tagName, $reg=null){
            $this->load();
            $elements = $this->xml->getElementsByTagName($tagName);
            $array=array();
            foreach($elements as $element){
                if(isset($reg)){
                    if($reg['strict'])
                        $pattern='#^'.$element->getAttribute($reg['att']).'$#';
                    else
                        $pattern='#'.$element->getAttribute($reg['att']).'#';
                     
                    isset($reg['opt']) ?$pattern.=$reg['opt'] : null;
                    
                    if(preg_match($pattern, $reg['value'], $matches))
                        return $this->buildArray($keys, $element, $matches);					
                }else{
					$array=$array+$this->buildArray($keys, $element);
				}
            }         
            return $array;
        }
        
}
