<?php
    $archive=$software->getFile();
    $document=$software->getTutoriel();
    $image=$software->getMiniature();
    $miniature=$image->getMiniature();
    
    //Tuto
    if(empty($document))
        $t=$config['view']['default-tuto-empty'];
    else{
        $t='
            '.$config['view']['size'].' : '.String::size2str($document->getSize()).'<br/>
            '.$config['view']['format'].' : '.$document->getExt().'<br/>
            Md5 : <input type="text" value="'.$document->getMd5().'"size="30" readonly/><br/>
            SHA512 : <input type="text" value="'.$document->getSha512().'"size="30" readonly/><br/>
            '.$document->getDownloadLink('software', $software->getRecord()->getId()).'
        ';
    }
    //File
    if(empty($archive))
		$a=$config['view']['default-archive-empty'];
    else{
		$a=$config['view']['size'].' : '.String::size2str($archive->getSize()).'<br/>
			'.$config['view']['format'].' : '.$archive->getExt().'<br/>
			Md5 : <input type="text" value="'.$archive->getMd5().'"size="30" readonly/><br/>
			SHA512 : <input type="text" value="'.$archive->getSha512().'" size="30" readonly/><br/>
			'.$archive->getDownloadLink('software', $software->getRecord()->getId());
	}
    
   echo'<article id="software_show">
            <h1>'.$software->getNom().'</h1>
            <aside>
                <h2>'.$config['view']['title-info-gen'].'</h2>
                '.$config['view']['developpeur'].' : '.$software->getDeveloppeur().'<br/>
                '.$config['view']['langue'].' : '.$software->getLangue().'<br/>
                '.$config['view']['categorie'].' : '.$software->getCategorie()->getValue().'<br/>
                '.$config['view']['os'].' : '.$software->getOs()->getValue().'<br/>
                '.$config['view']['date'].' : '.$software->getDate().'<br/>
                '.$config['view']['license'].' : '.$software->getLicense().'<br/>
                '.$config['view']['version'].' : '.$software->getVersion().'<br/>
                '.$config['view']['description'].' : 
                    <p>
                        '.String::NlToBr($software->getDescription()).'
                    </p>
            </aside>        
            <aside>
                <h2>'.$config['view']['title-info-file'].'</h2>
                '.$a.'
            </aside>
            <aside>
                <h2>'.$config['view']['title-info-tuto'].'</h2>
                '.$t.'
            </aside>
            <aside>
				<h2>'.$config['view']['title-related-software'].'</h2>
				'.$related.'
			</aside>
        </article>';
