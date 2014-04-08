<?php
    $files=$film->getFiles();
    $image=$film->getMiniature();
    $miniature=$image->getMiniature();
    
    $filesAside='';
    for($a=0; $a<count($files); $a++){
		$file=$files[$a];
		if(is_object($file)){
			$filesAside.='<aside>
				'.$config['view']['size'].' : '.String::size2str($file->getSize()).'<br/>
				'.$config['view']['format'].' : '.$file->getExt().'<br/>
				'.$config['view']['resolution'].' : '.$file->getWidth().'x'.$file->getHeight().'<br/>
				Md5 : <input type="text" value="'.$file->getMd5().'"size="30" readonly/><br/>
				SHA512 : <input type="text" value="'.$file->getSha512().'" size="30" readonly/><br/>
				'.$file->getDownloadLink('film', $film->getRecord()->getId()).' '.$file->getShowLink('film', 'Voir en streaming').'
				</aside>';
		}
	}
	empty($filesAside) ? $filesAside=$config['view']['default-video-empty'] : null;
    
   echo'<article id="film_show">
            <h1>'.$film->getNom().'</h1>
            <aside>
				<img src='.$miniature->generateHtmlPath().' alt=""/>
            </aside>
            <aside>
                <h2>'.$config['view']['title-info-gen'].'</h2>
                '.$config['view']['acteurs'].' : '.$film->getActeurs().'<br/>
                '.$config['view']['realisateur'].' : '.$film->getRealisateur().'<br/>
                '.$config['view']['categorie'].' : '.$film->getCategorie()->getValue().'<br/>
                '.$config['view']['langue'].' : '.$film->getLangue().'<br/>
                '.$config['view']['subtitle'].' : '.$film->getSubtitle().'<br/>
                '.$config['view']['date'].' : '.$film->getDate().'<br/>
                '.$config['view']['saga'].' : '.$film->getSaga().'<br/>
                '.$config['view']['resume'].' : 
                    <p>
                        '.String::NlToBr($film->getResume()).'
                    </p>
            </aside>  
            <aside>
				<h2>'.$config['view']['title-info-file'].'</h2>      
				'.$filesAside.' 
			</aside>
            <aside>
				<h2>'.$config['view']['title-related-film'].'</h2>
				'.$related.'
			</aside>
        </article>';
