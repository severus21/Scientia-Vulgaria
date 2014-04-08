<?php
/*
 * name: ImageProcess
 * @description :  
 */


class ImageProcess extends FileProcess{
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
            public function getXY(){
                switch($this->file->getExt()){
                    case 'png':
                        $source = imagecreatefrompng($this->file->getPath());
                    break;
                    case 'jpeg':
                        $source = imagecreatefromjpeg($this->file->getPath());
                    break;
                    case 'gif':
                        $source = imagecreatefromgif($this->file->getPath());
                    break;
                }
                return array('x'=>imagesx($source) , 'y'=>imagesy($source));
            }
        /*
            Setters
        */
    /*
        Autres méthodes
    */
        /*
         * name: calculDimention
         * @param: $xO(int)[x originel], $y0(int)[y originel], $x(int), $y(int)
         * @description: cacule les dimentions en fonction des dimentions originelles et de celle souhaité pour conserver le même coef x/y  
         */
        public function calculDimention($x, $y){
            if($x==0 || $y==0){
                throw new RuntimeException('x et y doivent être different de zéro');
            }
            $xy=$this->getXY();
            $x0=$xy['x'];
            $y0=$xy['y'];
                
            $Xx=$x0/$x;
            $Yy=$y0/$y;
            
            if($Yy>1 && $Xx<=$Yy){
                $b=$x0/$y0;
                $x=ceil($b*$y);
            }elseif($Xx>1 && $Yy<=$Xx){
                $b=$y0/$x0;
                $y=ceil($b*$x);
            }
            return array('x'=>$x, 'y'=>$y);
        }
        
        /*
         * name: createMiniature
         * @param: $x(int), $y(int), $format(str), $auto(bool) 
         * @description: crée une miniature , $auto=true <=>redimentionnement intelligent
         */
        public function createMiniature($dir, $x, $y, $auto=true){
            $this->file->getExt()=='jpg'? $ext='jpeg': $ext=$this->file->getExt();
            $imagecreate='imagecreatefrom'.$ext;
            if(!is_callable($imagecreate))
                throw new RuntimeException('Type non supporter pour la redimention');
            
            if($auto===true){
                $dim=$this->calculDimention($x, $y);
                $x=$dim['x'];
                $y=$dim['y'];
            }
            $destination = imagecreatetruecolor($x, $y);
            $source=$imagecreate($this->file->getPath());

            $XY=$this->getXY();
            $X = $XY['x'];
            $Y = $XY['y'];

            
            if(!imagecopyresampled($destination, $source, 0, 0, 0, 0, $x, $y, $X, $Y)){
                $this->erreurs[]=self::ECHEC_REDIMENTION_MINIATURE;
                return false;
            }

            $function='image'.strtolower($this->file->getExt());
            $path=Image::DIR.'/'.String::uniqStr().'.'.$ext;
            if(!$function($destination, $path)){
                $this->erreurs[]=self::ECHEC_CREATION_MINIATURE;
                return false;
            }
            
            return array('path'=>$path, 'x'=>$x, 'y'=>$y);
        }

}
