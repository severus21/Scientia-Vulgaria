<?php

class Benchmark{
		/*
        Attributs
    */
        protected $cpuTimes;
        protected $memoryUsage;
        
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
	public function displayResult($nbrTests){		
		echo '<h4>Average execution time : </h4>';
		for($a=0; $a<$nbrTests; $a++){
			echo'methode '.$a.' = '.$this->calculAverage($this->cpuTimes[$a]).'s <br/>';
		}
			
		echo '<h4>Average memory usage : </h4>';
		for($a=0; $a<$nbrTests; $a++){
			echo'methode '.$a.' = '.$this->calculAverage($this->memoryUsage[$a]).'s <br/>';
		}
		exit;
	}
	
	public function calculAverage(&$array){
		$i=count($array);
		$m=0;
		for($a=0; $a<$i; $a++){
			$m+=$array[$a];
		} 
		return $m/$i;
	}
	
	public function comparMethodes($methodes, $nbrTests){
		$this->cpuTimes=array();
		$this->memoryUsage=array();
		for($a=0; $a<$nbrTests; $a++){
			for($b=0; $b<count($methodes); $b++){
				$t=microtime(true);
				$tm=memory_get_usage();
				
				$methodes[$b]['obj']->$methodes[$b]['methode']( $methodes[$b]['args'] );
				
				$this->cpuTimes[]=microtime(true)-$t;
				$this->memoryUsage[]=memory_get_usage()-$tm;
			}
		}
		
	}
}
