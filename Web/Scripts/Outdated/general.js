	/*
		Header
	*/	
		/* 
			Barre Menu 
		*/
			// var barreMenuAdministration = document.getElementById('barre_menu_administration');
			
			// function getBarreMenuContent(element){
				// while(element=element.nextSibling){
					// if(element.className == 'barre_menu_content'){
						// alert("rere");
						// alert(element);
						// return element;
					// }
				// }	
			// }
        
			// function showListeBarreMenu(element){
				// var element=getBarreMenuContent(element);
				// element.style.display='block';
				// return false;
			// / barreMenuAdministration.addEventListener('mouseover', function(){
				// var barreMenuContent=showListeBarreMenu(barreMenuAdministration);
				// alert(barreMenuContent);
				
			// }, false);
		/*
			Barre de recherche
		*/

			var centerWidth = screen.width/2;
			var centerHeight= document.querySelector('header').height/2;	
			var searchBarre = document.getElementById('search_barre');
			var searchBarreWidth = searchBarre.offsetWidth;
			var searchBarreHeight = searchBarre.offsetHeight;

			searchBarre.style.left = centerWidth-searchBarreWidth/2;
			searchBarre.style.top = centerHeight-searchBarreHeight/2;
		
		
		
		
	/*
		Menu gauche 
	*/
		function getContent(element, contentName){
			while(element=element.nextSibling) {
				if (element.className ==contentName){
					return element;
				}	
			}
			return false;			
		}
		
		function scrollMenu(name){
			var modules=document.querySelectorAll('.'+name+'');
			for(var a=0; a<modules.length; a++){
				modules[a].addEventListener('click',function(){
					var module=document.getElementById(this.id);
					var content=getContent(module, 'content_'+name);
					if(content.style.display=='block'){
						content.style.display='none';
					}else{
						content.style.display='block';
					}	
				},false);	
			}
		}

		scrollMenu('module_nav');
		scrollMenu('categorie_nav');
		// var nav_langue = document.getElementById('nav_langue');
		// var nav_science_humaine = document.getElementById('nav_science_humaine');
		// var nav_science = document.getElementById('nav_science');
		// var nav_multimedia = document.getElementById('nav_multimedia');
		// var nav_informatique = document.getElementById('nav_informatique');
		
		// var content_nav_langue = document.getElementById('content_nav_langue');
		// var content_nav_science_humaine = document.getElementById('content_nav_science_humaine');
		// var content_nav_science = document.getElementById('content_nav_science');
		// var content_nav_multimedia = document.getElementById('content_nav_multimedia');
		// var content_nav_informatique = document.getElementById('content_nav_informatique');
		
		// nav_langue.addEventListener('click', function show(){
			// var display=getComputedStyle(content_nav_langue, null).display;
			// if(display=="none"){
				// content_nav_langue.style.display='block';
			// }
			// else{
				// content_nav_langue.style.display='none';
			// }
		// }, false); 
		// nav_science_humaine.addEventListener('click', function show(){
			// var display=getComputedStyle(content_nav_science_humaine, null).display;
			// if(display=="none"){
				// content_nav_science_humaine.style.display='block';
			// }
			// else{
				// content_nav_science_humaine.style.display='none';
			// }
		// }, false); 
		// nav_science.addEventListener('click', function show(){
			// var display=getComputedStyle(content_nav_science, null).display;
			// if(display=="none"){
				// content_nav_science.style.display='block';
			// }
			// else{
				// content_nav_science.style.display='none';
			// }
		// }, false); 
		// nav_multimedia.addEventListener('click', function show(){
			// var display=getComputedStyle(content_nav_multimedia, null).display;
			// if(display=="none"){
				// content_nav_multimedia.style.display='block';
			// }
			// else{
				// content_nav_multimedia.style.display='none';
			// }
		// }, false); 
		// nav_informatique.addEventListener('click', function show(){
			// var display=getComputedStyle(content_nav_informatique, null).display;
			// if(display=="none"){
				// content_nav_informatique.style.display='block';
			// }
			// else{
				// content_nav_informatique.style.display='none';
			// }
		// }, false); 
