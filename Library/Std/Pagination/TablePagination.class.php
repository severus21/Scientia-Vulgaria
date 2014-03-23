<?php
/*
    Auteur : Oceane21
    Version : 1.0.0
    Projet : Scientia Vulgaria Project
*/

class TablePagination extends Pagination{
    /*
        Attributs
    */
        protected $cells=array(); 
        protected $title='';
        
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
            public function setCells($cells){
                if(is_array($cells)){
                    $this->cells=$cells;
                }else{
                    throw new RuntimeExcetion('cells must be an array');
                }
            }
            
            public function setTitle($title){
                if(is_string($title)){
                    $this->title=$title;
                }else{
                    throw new RuntimeExcetion('title must be a string');
                }
            }
    /*
        Autres méthodes
    */
        public function addCell($cell){
            $this->cells[]=$cell;
        }
        
        public function buildHead(){
            $head='<thead><tr></tr>';
            
             //Faille CSRF, on génère le token
            $TokenManager=new TokenManager(PDOFactory::getMysqlConnexion());
            $tokenRecord=new TokenRecord( ['time'=>time(), 'value'=>String::uniqStr(15)] );
            $TokenManager->save($tokenRecord);

            
            foreach($this->cells as $cell){
				$cellOrder=$cell->getOrder();
				isset($cellOrder) ? $order='&order='.$cellOrder : $order='';
				
				if($this->order===$cellOrder && $this->asc=='asc'){
					$asc='&asc=desc';
				}else
					$asc='&asc=asc';
				if(!empty($cellOrder)){
					$head.='<th><a href="?page='.$this->page.'
											&nbrObjectsPerPage='.$this->nbrObjectsPerPage.'
											&change=table
											'.$order.'
											'.$asc.'
											&token_csrf='.$tokenRecord->getValue().'">
									'.$cell->getName().'</a></th>';
				}else{
					$head.='<th>'.$cell->getName().'</th>';
				}
            }
            return $head.'</tr></thead>';
        }
        
        public function buildFoot(){
            $foot='<tfoot><tr></tr>';
            foreach($this->cells as $cell){
                $foot.='<th>'.$cell->getName().'</th>';
            }
            return $foot.'</tr></tfoot';
        }
        
        public function buildBody(){
            $body='<tbody>';
            for($a=0; $a<count($this->objects); $a++){
                $body.='<tr>';
                foreach($this->cells as $cell){
                    $body.=$cell->show($this->objects[$a]);
                }
                $body.='</tr>';
            }
            return $body;
        }
        
        public function buildContent(){
            return '<table id="'.$this->id.'"><caption>'.$this->title.'</caption>'.$this->buildHead().''.$this->buildBody().''.$this->buildFoot().'</table>';
        }
}
