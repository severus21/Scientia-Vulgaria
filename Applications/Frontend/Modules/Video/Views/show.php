<?php
    $streaming=$video->getStreaming();


	if( $video->getStatut()=='done' && is_file($streaming->getPath())){
		echo '<video controls preload="metadata" src="'.$streaming->getStreamHtmlPath().'" 
		height="600" width="800">
		Veuillez mettre à jour votre navigateur ou utiliser : Firefox, Opera ou Chrome </video>';
	}else{
		echo $config['view']['waiting-convertion'];
	}
