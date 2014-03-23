<?php
/*
 * name: Categorie
 * @description :  
 */
 
class Categorie extends Objects{
    /*
        Attributs	
    */
        public $nom;
        public $value;

        /*
            Constantes
        */
    /*
        Méthodes générales
    */
		public function hydrate(array $donnees){
			parent::hydrate($donnees);
			(array_key_exists('nom_'.$_SESSION['lang'], $donnees)) ? $this->setNom($donnees['nom_'.$_SESSION['lang']]) : null;
			(array_key_exists('value_'.$_SESSION['lang'], $donnees)) ? $this->setValue($donnees['value_'.$_SESSION['lang']]) :null;
		}
        /*
            Getters
        */
            public function getNom(){
                return $this->nom;
            }           
            
            public function getValue(){
                return $this->value;
            }
        /*
            Setters
        */
            public function setNom($n){
                $this->nom=$n;
            } 
            
            public function setValue($v){
                $this->value=$v;
            } 
    /*
        Autres méthodes
    */ 
		public function getTableOptions($default=''){
			$manager=new CategorieManager();
			$tables=$manager->getCategorieTables();
			$grps=array();
			
			foreach($tables as $database => $tmp){
				$optGrp=new OptgroupField(['label'=>$database]);
				for($i=0; $i<count($tmp); $i++){
					$table=$tmp[$i];
					$selected = ($default==$table) ? true : false;
					$optGrp->addOption( new OptionField(array('value'=>$database.'.'.$table, 'label'=>$table, 'selected'=>$selected)) );  	
				}
				$grps[]=$optGrp;
			}
			return $grps;
		}
		
		public function getCategorieOptgroups($id=null,$default=null){
			if(empty($id) && ( empty($default) || $default->getNom()=="" || $default->getValue()=="") ){
				$cache=new Cache();
				
				isset($default) ? $def='default' : $def='';
				$name=get_class($this).'_Optgr_'.$def.'_'.$_SESSION['lang'];
				return $cache->getAPCCache($name, (function($arg){
					$categorie=new $arg['class']();
					return $categorie->getCategorieOptgroupsDyn( null, $arg['default'] );
				}), ['class'=>get_class($this), 'default'=>$default]);
			}
			return $this->getCategorieOptgroupsDyn($id, $default);
		}
		
		public function getCategorieOptions($value=null,$default=null){
			if(empty($value) && ( empty($default) || $default->getNom()=="" || $default->getValue()=="") ){
				$cache=new Cache();
				
				isset($default) ? $def='default' : $def='';
				$name=get_class($this).'_Opt_'.$def.'_'.$_SESSION['lang'];
				return $cache->getAPCCache($name, (function($arg){
					$categorie=new $arg['class']();
					return $categorie->getCategorieOptionsDyn( null, $arg['default'] );
				}), ['class'=>get_class($this), 'default'=>$default]);
			}
			return $this->getCategorieOptionsDyn($value, $default);
		}
		
		public function getCategorieOptgroupsDyn($id=null,$default=null){//default de type Categorie
			$class=get_class($this);
			$classManager=ucfirst($class).'Manager';
			$catManager=new $classManager(PDOFactory::getMysqlConnexion());
			$catRequete=new Requete(array('order'=>['nom_'.$_SESSION['lang'],'value_'.$_SESSION['lang']]));
			
			$records=$catManager->getList($catRequete);
			$objectFactory=new ObjectFactory();
			$cat=$objectFactory->buildMultiObject($records);
			if(isset($default)){
				$cat=array_merge([$default],$cat);
			}
			$catOp=array();
			$catSelect=new SelectField();
			foreach($cat as $rec){
				if(!array_key_exists($rec->getNom(), $catOp))
					$catOp[$rec->getNom()]=new OptgroupField(array('label'=>$rec->getNom()));
				//on verifie que ce ne soit pas un objet vide
				if(is_object($rec->getRecord())){
					(isset($id) && $rec->getRecord()->getId()==$id) ? $selected=true : $selected=false;
					$value=(string)$rec->getRecord()->getId();
				}else{
					$value='';
					$selected=false;
				} 

				$option=new OptionField(array('value'=>$value, 'label'=>$rec->getValue(), 'selected'=>$selected));              
				$catOp[$rec->getNom()]->setOptions($option);
			}
			$catOptgroups=array();
			foreach($catOp as $key => $element){
				$catOptgroups[]=$element;
			}
			return $catOptgroups;
		}

		public function getCategorieOptionsDyn($value=null,$default=null){//default de type Categorie
			$class=get_class($this);
			$classManager=ucfirst($class).'Manager';
			$catManager=new $classManager(PDOFactory::getMysqlConnexion());
			$catRequete=new Requete(array('order'=>['nom_'.$_SESSION['lang'],'value_'.$_SESSION['lang']]));
			
			$records=$catManager->getList($catRequete);
			$objectFactory=new ObjectFactory();
			$cat=$objectFactory->buildMultiObject($records);
			if(isset($default)){
				$cat=array_merge([$default],$cat);
			}
			
			$options=array();
			foreach($cat as $rec){
				(isset($value) && $rec->getValue()==$value) ? $selected=true : $selected=false; 
				$options[]=new OptionField(array('value'=>$rec->getValue(), 'label'=>$rec->getNom(), 'selected'=>$selected));              
			}
			return $options;
		}
 
		public function getUpdateLink($linkOnly=false, $class='', $value='Modifier'){
			if($linkOnly)
				return '/backend/categorie/update-'.$this->getRecord()->getId().'-'.lcfirst($this->getCurrentClass());
			else 
				return '<a class="'.$class.'" href="/backend/categorie/update-index-'.$this->getRecord()->getId().'-'.lcfirst($this->getCurrentClass()).'">'.$value.'</a>';
		}
		
		public function getDeleteLink($linkOnly=false, $class='', $value='Suppr'){
			if($linkOnly)
				return '/backend/categorie/update-'.$this->getRecord()->getId().'-'.lcfirst($this->getCurrentClass());
			else 
				return '<a class="'.$class.'" href="/backend/categorie/delete-'.$this->getRecord()->getId().'-'.lcfirst($this->getCurrentClass()).'">'.$value.'</a>';
		}
 
 }
