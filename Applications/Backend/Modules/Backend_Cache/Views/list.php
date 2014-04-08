<?php
	$lastApp="";
	$lastMod="";
	
	//On affiche  la liste trié
	$n=count($cacheElements);
	for($a=0; $a<$n; $a++){
		$element=&$cacheElements[$a];
		
		//App
		if($lastApp!=$element->getApp()){
			echo empty($lastApp) ? "" : '</section>'; //On ferme si n"c"ssaire
			echo'<section id="'.$element->getApp().'" class="app">
					<h3>'.$element->getApp().'  <a href="unset?app='.$element->getApp().'">	'.$config['view']['delete-mod'].'</a></h3>';
		}
		
		//Mod
		if($lastMod!=$element->getMod()){
			echo empty($lastMod) ? "" : '</section>'; //On ferme si n"c"ssaire
			echo'<section id="'.$element->getMod().'" class="mod">
					<h4>'.$element->getMod().' <a href="unset?app='.$element->getApp().'&mod='.$element->getMod().'">'.$config['view']['delete-mod'].'</a></h4>';
		}
		
		echo '<aside class="file-cache">
				<strong>'.$config['view']['name'].'</strong> '.$element->getName().'<br/>
				<strong>'.$config['view']['size'].'</strong> '.String::size2str($element->getSize())	.'
				<a href="unset?app='.$element->getApp().'&mod='.$element->getMod().'&name='.$element->getName().'">'.$config['view']['delete-file'].'</a>
			</aside>';
		
		$lastApp=$element->getApp();
		$lastMod=$element->getMod();
	}
