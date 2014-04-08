<?php



	$ratioFreeSpace=floor( $statsDisk['size']*100/$statsDisk['freeSpace'] + 0.5);
	echo'<section id="statsDisk">
		'.$config['view']['nbr'].'  '.$statsDisk['nbr'].'<br/>
		'.$config['view']['size'].'  '.String::size2str($statsDisk['size']).' '.$ratioFreeSpace.'%<br/>
		'.$config['view']['free-space'].' '.String::size2str($statsDisk['freeSpace']).'
	</section>';

	echo'<section id="statsMem">'; 
	foreach($statsMem as $key=>$server){
		$ratio_used_max_bytes=floor( $server['bytes']*100/$server['limit_maxbytes'] + 0.5);
		 
		echo '<aside id="'.$key.'">';
		echo '<h3>'.$key.'</h3>';
		echo $config['view']['curr_items'].' '.$server['curr_items'].'<br/>';
		echo $config['view']['bytes-used'].' '.String::size2str($server['bytes']).'    '.$ratio_used_max_bytes .'%<br/>';
		echo $config['view']['max-bytes'].' '.String::size2str($server['limit_maxbytes']).'<br/>';
		echo $config['view']['version'].' '.$server['version'].'<br/>';
		echo'</aside>';
	 }			
	echo'</section>';
	
	
