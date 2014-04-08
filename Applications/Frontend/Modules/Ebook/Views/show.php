<?php
    $files=$ebook->getFiles();
    $image=$ebook->getMiniature();
    $miniature=$image->getMiniature();
    
    $filesAside='';
    for($a=0; $a<count($files); $a++){
		$file=$files[$a];
		if(is_object($file)){
			$filesAside.='<aside>
				'.$config['view']['size'].' : '.String::size2str($file->getSize()).'<br/>
				'.$config['view']['format'].' : '.$file->getExt().'<br/>
				Md5 : <input type="text" value="'.$file->getMd5().'"size="30" readonly/><br/>
				SHA512 : <input type="text" value="'.$file->getSha512().'" size="30" readonly/><br/>
				'.$file->getDownloadLink('ebook', $ebook->getRecord()->getId()).'
				'.$file->getShowLink('ebook').'
				</aside>';
		}
	}
	empty($filesAside) ? $filesAside=$config['view']['default-document-empty'] : null;
    
   echo'<article id="ebook_show">
            <h1>'.$ebook->getNom().'</h1>
            <aside>
				<img src='.$miniature->generateHtmlPath().' alt=""/>
            </aside>
            <aside>
                <h2>'.$config['view']['title-info-gen'].'</h2>
                 '.$config['view']['isbn'].' : '.$ebook->getIsbn().'<br/>
                 '.$config['view']['auteur'].' : '.$ebook->getAuteur().'<br/>
                 '.$config['view']['editeur'].' : '.$ebook->getEditeur().'<br/>
                 '.$config['view']['genre'].' : '.$ebook->getGenre()->getValue().'<br/>
                 '.$config['view']['langue'].' : '.$ebook->getLangue().'<br/>
                 '.$config['view']['etiquette'].' : '.$ebook->getEtiquette()->getValue().'<br/>
                 '.$config['view']['date'].' : '.$ebook->getDate().'<br/>
                 '.$config['view']['serie'].' : '.$ebook->getSerie().'<br/>
                 '.$config['view']['resume'].' : 
                    <p>
                        '.String::NlToBr($ebook->getResume()).'
                    </p>
            </aside> 
            <aside>
				<h2>'.$config['view']['title-info-file'].'</h2>       
				'.$filesAside.' 
			</aside>
			 <aside>
				<h2>'.$config['view']['title-related-ebook'].'</h2>
				'.$related.'
			</aside>
        </article>';
