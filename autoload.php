<?php

class ClassMap{
	protected $chemins=array();
	public $lines=0;
	public $files=0;
	public $chars=0;
	
    public function search($directory){
        foreach(scandir($directory) as $key => $element){
            if($element !='.' && $element !='..'){
                if(is_dir($directory.'/'.$element)){
                    $this->search($directory.'/'.$element);
                }
                else{
                    if( preg_match("#(.+)\.class\.php$#", $element, $arg) || preg_match("#class\.(.+)\.php$#", $element, $arg) )
						$class=$arg[1];
					
                    
                    if(!empty($class)){
						$this->chemins[$class]=$directory.'/'.$element;
                        $this->chemins[ucfirst(strtolower($class))]=$directory.'/'.$element;
					}
                    
                    $class="";
                }
            }
        }
    }
    
    public function save($path='../classMap.php'){
		empty($this->chemins) ? $this->search('..') : null;
		ksort($this->chemins);
		
		is_file($path) ? unlink($path) : null;
		$file=fopen($path, 'a+');
		
		//Head
		$head="<?php \n\$classMap=[\n";
		fputs($file, $head);
		
		//Body
		foreach($this->chemins as $class => $fileClass){
			fputs($file, "'".$class."'=>'".$fileClass."',\n");
		}
		
		//Foot
		$foot="] \n ?>";
		fputs($file, $foot);
		fclose($file);
	}
  
	public function countLines($directory){
		foreach(scandir($directory) as $key => $element){
            if($element !='.' && $element !='..'){
                if(is_dir($directory.'/'.$element)){
                    $this->countLines($directory.'/'.$element);
                }
                else{
                    if( preg_match("#(.+)\.(?:php|css|html|xml|js)$#", $element, $arg) && !preg_match("#Extras#", $directory)){
						$this->files++;
						$buffer=file_get_contents($directory.'/'.$element);
						$this->lines+=substr_count($buffer, "\n");
						$this->chars+=strlen($buffer);
					}
                }
            }
        }
		
	}
}
	function autoload($class){
		/*$ClassMap=new ClassMap();
		$ClassMap->search('..');
		$ClassMap->save();
		$ClassMap->countLines('..');
		echo "Files : {$ClassMap->files} <br/>Lines : {$ClassMap->lines} <br/>Chars : {$ClassMap->chars}";exit;*/
		
		include('../classMap.php');
		
		//On vérifie que ce ne soit pas une classe native
		$classPhp=array('ZipArchive', 'Memcached');
		if(in_array($class, $classPhp)){
			return;
		}

		require_once $classMap[$class];
	}
    spl_autoload_register('autoload');
