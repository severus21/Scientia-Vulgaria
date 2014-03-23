<?php
/*
 * name: Captcha
 * @description :  
 */
    
class Captcha{
    /*
        Attributs
    */  
        protected $code;
        protected $excl=array();
        protected $image;
        protected $img;
        protected $plage=[38,122];
        protected $name;
        protected $nbr=8;
        
        /*
            Constantes
        */
            
    /*
        Méthodes générales
    */
        public function __construct($use=false, array $info=null){
            if($use and isset($_SESSION['captcha'])){
                $this->hydrate($_SESSION['captcha']);
            }else{
                isset($info)?$this->hydrate($info):null;
                $this->generateCode();
                $this->createImage();
                $this->setImg();
            }
        }
        
        public function __destruct(){
            $this->save();
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
            public function getCode(){
                return $this->code;
            }
            
            public function getImg(){
                return $this->img;
            }
            
            public function getImage(){
                return $this->image;
            }
       
        /*
            Setters
        */
            public function setCode($code){
                $this->code=$code;
            }
            
            public function setImg($img=null){
                if(empty($img)){
                    $tmp=new Tmp();
                    $this->img=$tmp->path('Main', 'Captcha').'.png';
                    imagepng($this->image, $this->img);
                }else{
                    $this->img=$img;
                }
            }

            public function setImage($image=null){
                $this->image=$image;
            }
  
			public function setName($name){
				$this->name=$name;
			}
    /*
        Autres méthodes
    */
        
        public function generateCode(){
            $this->code=null;
            $excl=array_merge([60,62,64, 92, 95, 96], $this->excl);
            
            for($a=0; $a<=$this->nbr; $a++){
                $c=mt_rand($this->plage[0], $this->plage[1]);
                !in_array($c, $excl) ? $this->code.=chr($c) : $a--;
            }
        }
        
        public function createImage(){
            $this->image=imagecreatetruecolor(200,50);
            
            $background = imagecolorallocate($this->image, 0, 0, 0);
            $colorStr=imagecolorallocate($this->image, 255, 255, 255);
            $colorL=imagecolorallocate($this->image, 255, 255, 255);
            $pointsPolygone=[];
            
            //Choix de la police
            $fonts=['MTCORSVA.TTF'];
            $font=$fonts[mt_rand(0, (count($fonts)-1))];
            
            
            imagettftext($this->image, 30, 0, 0, 40, $colorStr, '../Web/Fonts/'.$font, $this->code);
            imagesetthickness ($this->image, 1);
            for($a=0; $a<=4; $a++){
                imageline($this->image, mt_rand(0,199), mt_rand(0,49),  mt_rand(0,199), mt_rand(0,49), $colorL);
                $pointsPolygone[]=mt_rand(0,199);
                $pointsPolygone[]=mt_rand(0,49);
            }
            imagesetthickness ($this->image, 1);
            imagepolygon($this->image, $pointsPolygone, 4, $colorL);
            
        }
        
        public function save(){
            $_SESSION['captcha']=array('code'=>$this->code, 'img'=>$this->img, 'name'=>$this->name);
        }
}
