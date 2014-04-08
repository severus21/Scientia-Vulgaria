<?php
	if(empty($tmpfileRecords))
		exit;
	
	for($a=0; $a<count($tmpfileRecords); $a++){
		echo ($a==0) ? "Fichier : " : "Fichier".$a." : ";
		echo 'http://scientiavulgaria.org/'.substr($tmpfileRecords[$a]->getPath(), 7).'<br/>';
	}


?>
