﻿<?php
//Traitement des variables
if(empty($title))
	$title='Scientia Vulgaria';

if($flash){
	$flash='<aside id="flash">'.$flash.'</aside>';
}

empty($secondaryTopNav) ? $secondaryTopNav='' : null;

echo'
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8"/>
		<title>'.$title.'</title>
		<link rel="icon" type="image/x-icon" href="/Images/sv.ico" />
		'.$css.'
		
	</head>
	
	<body>
		<header>
			'.$topMainNav.'
			<img src="/Images/sv.png" height="180" width="356" id="mainLogo"/>	
			<h1 id="mainTitle">Scientia Vulgaria</h1>		
			'.$flash.'
		</header>

		<section id="mainLeft">'.$leftMainNav.'
			<aside id="leftMainDon">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_donations">
					<input type="hidden" name="business" value="laurent.prosperi658@orange.fr">
					<input type="hidden" name="lc" value="FR">
					<input type="hidden" name="item_name" value="Scientia Vulgaria">
					<input type="hidden" name="no_note" value="0">
					<input type="hidden" name="currency_code" value="EUR">
					<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
					<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
					<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
				</form>
				<a href="" ></a>
			</aside>
			<!--<aside id="leftMainPub"></aside>-->
		</section>	
		<section id="content">
			<section id="secondaryTop">
				'.$secondaryTopNav.'
			</section>
			
			<section id="secondary_content">
				'.$content.'
			</section>
		</section>
		
		<footer>	
		</footer>
			
			'.$js.'
	</body>
</html>';
