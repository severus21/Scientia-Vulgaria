<?php
//Traitement des variables
if(empty($title))
	$title='Scientia Vulgaria';
	
$_SERVER['REQUEST_URI']!='/' ? $dir='../../../': $dir='';

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
		<link rel="icon" type="image/x-icon" href="'.$dir.'Images/sv.ico" />
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
			<aside id="leftMainPub"></aside>
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
