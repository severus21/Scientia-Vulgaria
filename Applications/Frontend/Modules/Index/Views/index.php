<?php
/*
	Auteur : Oceane21
	Version : 1.0.0
	Projet : Scientia Vulgaria Project
*/
	$buffer='';
	if(!empty($news)){
		$buffer='<aside id="news">';
		for($i=0; $i<count($news); $i++){
			$buffer.=$news[$i]->toString();
		}
		$buffer.='</aside>';
	}
	echo $buffer;

	include('changelog.html');
	include('presentation.html');
	
	
	
?>
