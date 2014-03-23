<?php


class Sort{
    
    /*
     * Algorithmes généraux
     */
        static private function echange($a, $b, &$array){
            $tmp=$array[$a];
            $array[$a]=$array[$b];
            $array[$b]=$tmp;
        }
        
        //Tri à bulle, complexité moyenne n², tri en place
        static public function bubbleSort(&$array, $f){ //$f(a b)  true si a plus grand que b ex: function($a, $b){return $a>$b;}
            $flag=true;
            $b=count($array);
            while($flag && $b>1){
                for($a=0; $a<=($b-2); $a++){
                    if($f($array[$a], $array[$a+1])){
                        self::echange($a, $a+1, $array);
                        $flag=true;
                    }  
                }   
                $b--;
            }
        }
        
        //Tri par selection, complexité moyenne n², tri en place
        static function selectMax(&$array, $f, $a=0, $b=-1){
            ($b==-1) ? $b=(count($array)-1) : null;
            $max=$array[0];
            $ind_max=0;
            for($i=$a; $i<=$b; $i++){
                if($f($array[$i], $max)){
                    $max=$array[$i];
                    $ind_max=$i;
                }
            }
            return $ind_max;
        }
        
        static function selectionSort(&$array, $f){
            for($i=(count($array)-1); $i>0; $i--){
                $ind=self::selectMax($array, $f, 0, $i);
                self::echange($ind, $i, $array);
            }
        }
        
        
        //Tri par insertion, complexité moyenne n², tri en place
        static function insertionSort(&$array, $f){
			if(count($array)==2 && $f($array[0], $array[1])){
				$tmp=$array[0];
				$array[0]=$array[1];
				$array[1]=$tmp;
				return true;
			}
				
            for($a=0; $a<(count($array)-2) ; $a++){
				echo $a;
                $b=$a;
                $tmp=$array[$a+1];
                while($b>=0 && $f($array[$b], $tmp)){
                    $array[$b+1]=$array[$b];
                    $b--;
                }
                $array[$b+1]=$tmp;
            }
        }
        
        
        //Tri par fusion, complexité moyenne O(nlog(n)), tri hors place et null a revoir un temps de calcul pres de 100 sup au quick sort sur 100 000 éléments
        static function fusionSort($array, $f){
            if (sizeof($array ) <=1)
                return $array;
            
            $array2 = array_splice($array, (sizeof($array)/2));
            return self::fusion($f, self::fusionSort($array, $f), self::fusionSort($array2, $f));
        }
            

        static function fusion($f, $array1, $array2){
            $output=array();
            while(!empty( $array1 ) || !empty($array2)){ 
                if(empty($array1) || empty($array2))
                    $output[] = (empty($array2)) ? array_shift($array1) : array_shift($array2);
                else 
                    $output[] = ($f($array1[0], $array2[0])) ? array_shift($array2) : array_shift($array1);
            }
            return $output;
        }
        
        //Tri par segmentation, complexité moyenne O(nlog(n)) avec pivot variable, tri hors place
        static function quickSort($array, $f){
            if(count($array)<=1)
                return $array;
                
            $id_pivot=mt_rand(0,(count($array)-1));
            $pivot=$array[$id_pivot];
            $ar1=array(); $ar2=array();
            for($a=0; $a<count($array); $a++){
                if($a!=$id_pivot)
                    $f($array[$a], $pivot) ? $ar2[]=$array[$a] : $ar1[]=$array[$a];
            }    
            return array_merge(self::quickSort($ar1,$f), [$pivot], self::quickSort($ar2,$f));
        }
    
    /*
     * Algorithmes hybride
     */
        static function hybrid_sort($array, $f){
            if(count($array)<=250)//de manière expérimentale
                self::insertionSort($array,$f);
            else
                $array=self::quickSort($array,$f);
            return $array;
        }
        
        static function test($nbr){
            $ar=[];
            for($a=0; $a<$nbr; $a++){
                $ar[]=mt_rand(0,100000);
            }
            $br=$ar;
            
            $t=microtime(true);
            Sort::quickSort($ar, function($a, $b){return $a>$b;} );
            $T=microtime(true)-$t;

            $e=microtime(true);
            Sort::insertionSort($br, function($a, $b){return $a>$b;} );
            $E=microtime(true)-$e;
        
            echo '100000 :  tri 1='.($T).'et tri 2='.($E).'donc diff  <strong>'.($T/$E).'</strong>';exit;
        }
}
