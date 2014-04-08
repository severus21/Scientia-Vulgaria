<?php
    $image=$serie->getMiniature();
    $miniature=$image->getMiniature();
    
    
   echo'<article id="serie_show">
            <h1>'.$serie->getNom().'</h1>
            <aside>
				<img src='.$miniature->generateHtmlPath().' alt=""/>
            </aside>
            <aside>
                <h2>'.$config['view']['title-info-gen'].'</h2>
                '.$config['view']['acteurs'].' : '.$serie->getActeurs().'<br/>
                '.$config['view']['realisateur'].' : '.$serie->getRealisateur().'<br/>
                '.$config['view']['categorie'].' : '.$serie->getCategorie()->getValue().'<br/>
                '.$config['view']['langue'].' : '.$serie->getLangue().'<br/>
                '.$config['view']['date'].' : '.$serie->getDate().'<br/>
                '.$config['view']['nbrSaisons'].' : '.$serie->getNbrSaisons().'<br/>
                '.$config['view']['nbrEpisodes'].' : '.$serie->getNbrEpisodes().'<br/>
                '.$config['view']['resume'].' : 
                    <p>
                        '.String::NlToBr($serie->getResume()).'
                    </p>
            </aside>  
            <aside>
				<h2>'.$config['view']['title-related-saisons'].'</h2>
				'.$saisons.'
            </aside>
            <aside>
				<h2>'.$config['view']['title-related-serie'].'</h2>
				'.$related.'
			</aside>
        </article>';
