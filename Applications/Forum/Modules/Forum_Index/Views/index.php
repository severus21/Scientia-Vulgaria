<?php
	$content='';
	
	//On stocke toutes les categories déja rencontrées sachant que les objets sont classés par categorie
	$usedCategorie=array();
	foreach($forums as $forum){
		if(!in_array( $forum->getCategorie()->getNom(), $usedCategorie)){
			$usedCategorie!==array() ? $content.='</section></section>' : null;
			$usedCategorie[]=$forum->getCategorie()->getNom();
			$content.='<section class="forum_categorie">
							<h3>'.$forum->getCategorie()->getNom().'</h3>
							<section class="forum_categorie_content">';
		}
		
		$content.='<section class="forum">
					<h4><a href="/forum/forum/index-'.$forum->getRecord()->getId().'">'.$forum->getName().'</a></h4>
					<p>'.$forum->getDescription().'</p>
					<aside class="forum_info_sup">
						Topics : '.$forum->getTopics().' | Posts : '.$forum->getNbrPosts().'   
					</aside>
				</section>';
		
	}
	echo $content;
