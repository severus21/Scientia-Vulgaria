Scientia-Vulgaria
=================

================

1.General
	Applications/
	Cache/
	Cron/
	Library/
	License/	
	Log/	
	Web/
	autoload.php
	classMap.php
	
2.Autoloader
	autoload.php : chargement auto des classes 
	classMap.php : tableau name=>path 	

3.Web
	A.Structure
		Web/
			Dossier racine de l'application pour le serveur Apache.
		CSS/	
			#Centralise l'ensemble des feuilles de style, leur inclusion est spécifiée dans les fichiers de config des modules et des applications
			->$module-default.css
			->$app-default.css 
			->$module-$nomDuTheme.css (optionel)
			->$app-$nomDuTheme.css (optionel)
		Files/
			#Centralise l'ensemble des fichiers utilisés par les modules
			->$Module/
		
		Fonts/
		
		Images/
			#Design des applications
			->sv.ico		
			*
		Scripts/
			#JS et autres scripts
	
		Tmp/ 
			#Fichiers temporaires générés par les applications

		$app.php  #pages où sont redirigées(par Apache ) les requêtes en fct de l'application(à modifier dans les fichiers de config d'Apache)
		robot.txt

4.Library
	A.Intro
		Regroupe les sources des différents projets externes et le corps du framework
	
	B.Structure
		->Extras/
		->Std/			
		->Models
	
	C.Extras
		Contient l'ensemble des librairies extérieures utilisées, les principales sont : 
			->PHPMailer (gestion des mails)
			->PHPTracker (tracker bittorrent)
			...
	
	D.Std
		a.Description
			Le framwork 
		b.Structure
			->Core
			->Form 
			->Nav
			->Objects
			->Pagination
			->SQL
		c.Core
			Module gérant la communication entre l'application et le client.
		d.Form
			Gestion des formulaires
		e.Nav
			Gestion des menus
		f.Objects
			Gère les Objet de type Objects(et oui, un nom très recherché ;) ), ie les objets enregistrés en base de donnée par le Framwork ou par les applications filles.
		g.Pagination	
			Affichage proprement des objets de type Objects
		h.SQL
			Gestion des requêtes SQL : construction automatique des requêtes en fonction de la structure des objets
		
	E.Models
		contient les classes héritantes de Objects et leurs classes associées
		->Managers/
		->Objects/ 
		->Process/		
		->Records/


5.Applications
	A.Description
		->Config/
		->Modules/ 
		->Templates/ #Contient les squelettes des pages renvoyées par l'app
		->$NomApp+Application.class.php #App proprement dite, gestion des accés au modules principalement
		->$NomApp+NavBuilder.class.php #Menu par défauts de l'app
	B.Config
		->config.xml #Configuration en tout genre, surtout multilingue
		->routes.xml  # Associe les urls à un module et à une action du module		
		
	C.Modules
		cf.modulePHP-structure
