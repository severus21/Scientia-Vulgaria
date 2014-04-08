<?php
	$files=$episode->getFiles();
	$filesAside='';
    for($a=0; $a<count($files); $a++){
		$file=$files[$a];
		if(is_object($file)){
			$filesAside.='<aside>
				<h2>'.$config['view']['title-info-files'].'</h2>
				'.$config['view']['size'].' : '.String::size2str($file->getSize()).'<br/>
				'.$config['view']['format'].' : '.$file->getExt().'<br/>
				'.$config['view']['resolution'].' : '.$file->getWidth().'x'.$file->getHeight().'<br/>
				Md5 : <input type="text" value="'.$file->getMd5().'"size="30" readonly/><br/>
				SHA512 : <input type="text" value="'.$file->getSha512().'" size="30" readonly/><br/>
				'.$file->getDownloadLink('episode', $episode->getRecord()->getId()).' '.$file->getShowLink('episode', 'Voir en streaming').'
				</aside>';
		}
	}
	empty($filesAside) ? $filesAside=$config['view']['default-video-empty'] : null;
	
   echo'<article id="episode_show">
            <h1>'.$config['view']['numero'].''.$episode->getN().' : '.$episode->getNom().'</h1>
            <aside>
                <h2>'.$config['view']['title-info-gen'].'</h2>
                '.$config['view']['subtitle'].' : '.$episode->getSubtitle().'<br/>
                '.$config['view']['resume'].' : 
                    <p>
                        '.String::NlToBr($episode->getResume()).'
                    </p>
            </aside> 
            '.$filesAside.'
        </article>';
