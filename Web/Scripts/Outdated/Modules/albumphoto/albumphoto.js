var divUpload=document.querySelector('#divUpload');
var listeAjout=document.querySelector('#listeAjout');
var formUpload=document.querySelector('#form_upload');
var compteur=0;
document.querySelector('#fermer_upload').addEventListener('click', function(){
	divUpload.style.display='none';
},false);

document.querySelector('#ajouter').addEventListener('click', function(){
	if(compteur<100){
		var input=document.createElement('input');
		input.type='file';
		input.name='photo'+compteur;
		input.accept='image';

		compteur++;
		listeAjout.appendChild(input);	
		listeAjout.appendChild(document.createElement('br'));
		document.querySelector('#listeAjout #nbr').innerHTML=compteur+'/100';
	}
},false);

document.querySelector('#annuler').addEventListener('click', function(e){
	e.preventDefault();
	compteur=0;
	document.querySelector('#listeAjout #nbr').innerHTML='0/100';
	var inputs=document.querySelectorAll('#listeAjout input');
	for(var index in inputs){
		if(/photo[\d]+/.test(inputs[index].name)){
			document.querySelector('#listeAjout').removeChild(inputs[index]);
		}	
	}
	var brs=document.querySelectorAll('#listeAjout br');
	for(var index in brs){
		document.querySelector('#listeAjout').removeChild(brs[index]);
	}
},false);
