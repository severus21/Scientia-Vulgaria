<?php
    $image=$saison->getMiniature();
    $miniature=$image->getMiniature();
   
   echo'<article id="saison_show">
            <h1>'.$config['view']['numero'].''.$saison->getN().'</h1>
            <aside>
				<img src='.$miniature->generateHtmlPath().' alt=""/>
            </aside>
            <aside>
                <h2>'.$config['view']['title-info-gen'].'</h2>
                '.$config['view']['date'].' : '.$saison->getDate().'<br/>
                '.$config['view']['nbrEpisodes'].' : '.$saison->getNbrEpisodes().'<br/>
                '.$config['view']['resume'].' : 
                    <p>
                        '.String::NlToBr($saison->getResume()).'
                    </p>
            </aside>
            <aside>
				<h2>'.$config['view']['title-info-episodes'].'</h2>
				'.$episodes.'
            </aside> 
        </article>';
