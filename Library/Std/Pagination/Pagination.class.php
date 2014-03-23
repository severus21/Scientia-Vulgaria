<?php
/*
 * name: Pagination
 * @description : 
 */
 
 class Pagination{
    /*
        Attributs
    */  
        protected $id; //id css pour la pagnation
        protected $default; //msg a affiché si aucun objet trouvé
        protected $nbrPages;
        protected $nbrObjects; //nbr d'objet correspondant à la requête en tout
        protected $nbrObjectsPerPage=10;
        protected $nbrSelectablePages=11;
        protected $objects; //objet à afficher uniquement
        protected $classObject; //nom de la classe des objet ex : Image
        protected $page=1; //current page 1-......................
        protected $requete;
        protected $manager; //manager et construit a partir de classObject
        protected $objectFactory; 
        protected $change=true; //indique si on peut chnager de tpe de pagination mosaique/table
        protected $order='';
        protected $app='frontend';
        protected $asc='desc';
        protected $header=true; //False on n'affiche pas le header
        
        /*
            Constantes
        */
        
    
    /*
        Méthodes générales
    */
        public function __construct($data=null){
            $httpRequete=new HttpRequette();
            $httpData=strtolower($httpRequete->method()).'Data';
            $httpExists=strtolower($httpRequete->method()).'Exists';
            if(is_callable(array($httpRequete, $httpExists)) && $httpRequete->$httpExists('nbrObjectsPerPage') && $httpRequete->$httpExists('page')){
                $this->setNbrObjectsPerPage($httpRequete->$httpData('nbrObjectsPerPage'));
                $this->setPage($httpRequete->$httpData('page'));
                
				$httpRequete->$httpExists('order') ? $this->setOrder($httpRequete->$httpData('order')) : null;
				$httpRequete->$httpExists('asc') ? $this->setAsc($httpRequete->$httpData('asc')) : null;
                $this->setClassObject($httpRequete->sessionData('classObject'));
                $this->setApp($httpRequete->sessionData('app'));
                $this->setRequete(unserialize($httpRequete->sessionData('requete')));
                
                $httpRequete->sessionExists('change') ? $this->setChange($httpRequete->sessionData('change')) :null;
            }elseif(is_array($data)){
                $this->hydrate($data);  
            }else{
                throw new RuntimeException('Instance non construite');
            }
            $this->buildManager();
            $this->objectFactory= new ObjectFactory( $this->app );
        }  
       
        protected function hydrate(array $data){
            foreach ($data as $attribut => $value){
                $methode = 'set'.ucfirst($attribut);
                
                if (is_callable(array($this, $methode))){
                    $this->$methode($value);
                }
            }
        } 
        
        public function __destruct(){
            $httpRequete=new HttpRequette();
            $httpRequete->addSessionVar('requete', serialize($this->requete));
            $httpRequete->addSessionVar('classObject', $this->classObject);
            $httpRequete->addSessionVar('app', $this->app);
            $httpRequete->addSessionVar('change', $this->change);
        }

        /*
            Getters
        */
            public function getDefault(){
                return $this->default;
            }
           
            public function getNbrObjects(){
                return $this->nbrObjects;
            }
            
            public function getNbrObjectsPerPage(){
                return $this->nbrObjectsPerPage;
            }
            
            public function getNbrPages(){
                return $this->nbrPagesage;
            }
            
            public function getNbrSelectablePages(){
                return $this->nbrSelectablePages;
            }
            
            public function getObjects(){
                return $this->objects;
            }
            
            public function getPage(){
                return $this->page;
            }
            
            public function getRequete(){
                return $this->requete;
            }
            
            public function getRemember(){
                return $this->remember;
            }
            
            public function getApp(){
				return $this->app;
			}
			
            public function getHeader(){
				return $this->header;
			}
        /*
            Setters
        */
            public function setChange($c){
                if(is_bool($c)){
                    $this->change=$c;
                }else{
                    throw new RuntimeException('change must be a booleen');
                }
            }
     
            public function setId($id){
                $this->id=$id;
            }
            
            public function setDefault($def){
                if(is_string($def)){
                    $this->default=$def;
                }else{
                    throw new RuntimeException('default must be a string');
                }
            }            
            
            public function setNbrObjectsPerPage($nbr){
                if(is_numeric($nbr) && $nbr>0){
                    $this->nbrObjectsPerPage=(int)$nbr;
                }else{
                    throw new RuntimeException('nbrObjectsPerPage must be an integer');
                }
            }
            
            public function setNbrPages($nbr){
                if(is_numeric($nbr)){
                    $this->nbrPages=(int)$nbr;
                }else{
                    throw new RuntimeException('nbrPages must be an integer');
                }
            }
            
            public function setNbrObjects($nbr){
                if(is_numeric($nbr)){
                    $this->nbrObjects=(int)$nbr;
                }else{
                    throw new RuntimeException('nbrRecords must be an integer');
                }
            }
        
            public function setNbrSelectablePages($nbr){
                if(is_numeric($nbr)){
                    $this->nbrSelectablePages=(int)$nbr;
                }else{
                    throw new RuntimeException('nbrSelectablePages must be an integer');
                }
            }
        
            public function setPage($page){
                if(is_numeric($page)){
                    $this->page=(int)$page;
                }else{
                    throw new RuntimeException('page mut be an integer');
                }
            }
            
            public function setClassObject($class){
                if(is_string($class)){
                    $this->classObject=$class;
                }else{
                    throw new RuntimeException('classObject must be a string');
                }
            }
            
            public function setObjects($objects){
                if(is_array($objects) && $objects[0] instanceof Objects){
                    $this->objects=$objects;
                    $this->nbrObjects=count($objects);
                }else{
                    throw new RuntimeException('objets mut be an array of Objects');
                }
            }
            
            public function setManager($m){
                if($m instanceof Manager){
                    $this->manager=$m;
                }else{
                    throw new RuntimeException('manager must be an instance of the class Manager');
                }
            }
            
            public function setRequete($r){
                if($r instanceof Requete){
                    $this->requete=$r;
                }else{
                    throw new RuntimeException('requete must be an instance of the class Requete');
                }
            }
            
            public function setRemember($r){
                $this->remember=$r;
            }
			
			public function setOrder($o){
				if(is_string($o) && preg_match('#^[a-zA-Z0-9_\.]+$#', $o)){
					$this->order=$o;
				}
			}
			
			public function setAsc($a){
				if($a=='asc' || $a=='desc' || $a=='ASC' || $a=='DESC')
					$this->asc=$a;
			}
			
			public function setApp($app='frontend'){
				$this->app=$app;
			}
			
			public function setHeader($b){
				is_bool($b) ? $this->header=$b : null;
			}
    /*
        Autres méthodes
    */
        protected function buildManager(){
                if(empty($this->manager)){
                    if(isset($this->classObject)){
                        $managers=new Managers(PDOFactory::getMysqlConnexion($this->app));
                        $this->manager=$managers->getManagerOf($this->classObject);
                    }else{
                        throw new RuntimeException('classObject is empty');
                    }
                }
            }
            
        protected function calculNbrPages(){
            $this->nbrPages=ceil($this->nbrObjects/$this->nbrObjectsPerPage); 
        }
     
        protected function calculMinMaxPage(){
            $n=ceil(($this->nbrSelectablePages-1)/2);
            $diff=0;
            
            $min=$this->page-$n;
            if($min<1){
                $diff=abs($min)+1;
                $min=1;
            }
            
            $max=$this->page+$n+$diff;
            $max>$this->nbrPages ? $max=$this->nbrPages : null;
            
            return array('min'=>$min, 'max'=>$max);
        }
        
        public function buildEchangeur(){
            if(!$this->change)
                return '';
                
            if($this instanceof TablePagination)
                $class='mosaique';
            elseif($this instanceof MosaiquePagination) 
                $class='table';
            else
                return '';
                
            return '<a href="?page='.$this->page.'&nbrObjectsPerPage='.$this->nbrObjectsPerPage.'&change='.$class.'"><li class="echangeur2'.$class.'">Vue '.$class.'</li></a>';
        }
        
        protected function buildFromSQL(){
            $this->requete->setOffset(0); //on remet l offset a zero
            $this->nbrObjects=$this->manager->count($this->requete);
            
            if($this->nbrObjects==0)
                return false;
                 
            $offset=($this->page-1)*$this->nbrObjectsPerPage;
            $this->requete->setOffset($offset);
            !empty($this->order) ? $this->requete->setOrder([$this->order]) : null;
            !empty($this->asc) ? $this->requete->setAsc($this->asc) : null;
            $this->requete->setLimite($this->nbrObjectsPerPage);
            
            $this->objects=$this->objectFactory->buildObjectFromRequete($this->classObject, $this->requete);
            return true;
        }
        
        protected function buildHeader(){
            $this->calculNbrPages();
            $mM=$this->calculMinMaxPage();
            
            //On construit le header ie les liens vers les différentes pages
            $nav=new Nav();
            $nav->setClass('paginationHeaderNav');
            $nav->addNavElement(new NavElement(['label'=>$this->nbrObjects.' résultats']));
            for($a=$mM['min']; $a<=$mM['max']; $a++){
                $id='';
                if($a==$this->page){
                    $id='current_page';
                } 
                //On contruit le champ change pour la requete GET 
                if($this instanceof MosaiquePagination)
                    $changeClass='&change=mosaique';
                elseif($this instanceof TablePagination)
                    $changeClass='&change=table';
                else
                    $changeClass=='';  
                $nav->addNavElement(new NavElement(['id'=>$id,
													'label'=>$a,
													'link'=>'?page='.$a.'
															&nbrObjectsPerPage='.$this->nbrObjectsPerPage.$changeClass]));
            }
            $nav->addNavElement(new NavElement(['label'=>$this->buildEchangeur()]));
            return '<section class="paginationHeader">'.$nav->build().'</section>';
        }
        
        public function build(){
             $flag=$this->buildFromSQL();
                
            if(!$flag)//Dans le cas d'une recherche sans resultats
                return $this->default;
                
            $content=$this->buildContent();
            
            
            $header= ($this->header) ? $this->buildHeader() : '';
            return '<section class="pagination">'.$header.'<section class="paginationContent">'.$content.'</section></section>';
        }


}
