var photos=JSON.parse(photos);
var photosDiaporama=new Array();
var stopDiaporama=false;
var url=window.location.href;
var regexp=new RegExp(/.+album-photo\/([\d]+)-.+$/);
var idAlbumPhoto=url.replace(regexp, '$1');

document.querySelector('#upload_photo').addEventListener('click',function(e){
	e.preventDefault();
	document.getElementById('form_upload').action='../photo/insert-'+idAlbumPhoto;
	divUpload.style.display='block';
},false);

function generatePhotosDiaporama(photos){
	var a=0;
	for (var index in photos){
		photosDiaporama[a]=photos[index];
		a++;
	}
	return photosDiaporama;
}
function lastPicture(photos, focus){
	var lastIndex=-1;
	for (var index in photos){
		if(index===focus){
			var changeEvent = document.createEvent("Event");
                	changeEvent.initEvent ("click", true, false);
             		document.getElementById(photos[lastIndex].miniature).dispatchEvent(changeEvent);
			break;
		}	
		lastIndex=index;
	}

}

function nextPicture(photos, focus){
	var lastIndex=-1;
	for (var index in photos){
		if(lastIndex===focus){
			var changeEvent = document.createEvent ("Event");
               		changeEvent.initEvent ("click", true, false);
            		document.getElementById(photos[index].miniature).dispatchEvent(changeEvent);
			break;
		}	
		lastIndex=index;
	}

}

var ii=0;
function diaporama(photosDiaporama){
	var photo=photosDiaporama.shift();
	window.setTimeout(function(){
		if(stopDiaporama){
			return;
		}
		var changeEvent = document.createEvent ("Event");
               	changeEvent.initEvent ("click", true, false);
             	document.getElementById(photo.miniature).dispatchEvent(changeEvent);
		if(photosDiaporama.length!=0){
			diaporama(photosDiaporama);
		}
  	}, 3000);
}

for (var index in photos){
	var photo=photos[index];
	var focus=null;
	var miniature=document.getElementById(photo.miniature);
	var agrandissement=document.querySelector('#agrandissement');
	miniature.addEventListener('click', function(){
		if(document.getElementById('picture')==null){
			var image=document.createElement('img');
			agrandissement.appendChild(image);
			image.id='picture';

		}else{
			var image=document.getElementById('picture');
		}
		var ratioX=photos[this.id].x/(screen.width-400);
		var ratioY=photos[this.id].y/(screen.height-400);
		var ratioXY=photos[this.id].x/photos[this.id].y;
		if(ratioX<=1 && ratioY<=1){
			image.width=photos[this.id].x;
			image.height=photos[this.id].y;
		}else{
			if(ratioX/ratioY<=1){
				image.height=photos[this.id].y/ratioY;
				image.width=image.height*ratioXY;	
			}else{
				image.width=photos[this.id].x/ratioX;
				image.height=image.width*(1/ratioXY);
			}
		}
		agrandissement.style.display='block';
		image.src='/Fichiers/Photos/'+photos[this.id].fichier;
		agrandissement.style.left = screen.width/2-agrandissement.offsetWidth/2;
		agrandissement.style.top = screen.height/2-image.height/2;
		focus=this.id;
	},false);
	
}

document.addEventListener('keydown', function(e) {
	if(e.keyCode===37 && document.getElementById('picture')!=null){
		lastPicture(photos, focus);
	}else if(e.keyCode===39 && document.getElementById('picture')!=null){
		nextPicture(photos, focus);     	
	}
    }, false);

document.querySelector('#precedent').addEventListener('click', function(e){
	lastPicture(photos, focus);
},false);

document.querySelector('#suivant').addEventListener('click', function(e){
	nextPicture(photos, focus);
},false);

document.querySelector('#diaporama').addEventListener('click', function(e){
		stopDiaporama=false;
		var photosDiaporama=generatePhotosDiaporama(photos);
		diaporama(photosDiaporama);
},false);

document.querySelector('#fermer_agrandissement').addEventListener('click', function(){
	stopDiaporama=true;
	agrandissement.style.display='none';
},false);

document.querySelector('#supprimer_photo').addEventListener('click', function(){
	if(window.confirm('Voulez-vous vraiment supprimer cette photo')){
		var xhr=new XMLHttpRequest();
		var form=new FormData();
		form.append('id', focus);
		xhr.open('GET', '../photo/delete-'+photos[focus].id, true);
		xhr.onreadystatechange=function(){
			if(xhr.readyState==4 && xhr.status==200){
				window.location.reload();
			}
		};
		xhr.send(form);	
	}
	
},false);

document.querySelector('#infoAlbum').addEventListener('click', function(e){
	e.preventDefault();
	document.querySelector('#divInfoAlbum').style.display='block';
},false);

document.querySelector('#fermer_info_album').addEventListener('click', function(){
	document.querySelector('#divInfoAlbum').style.display='none';
},false);

/*
document.getElementById('ajouterPhoto').addEventListener('click', function(e){
	e.preventDefault();
	var xhr=new XMLHttpRequest();
	var form= new FormData();
	var progress=document.getElementById('progressUploadPhoto');
	var forms=document.querySelectorAll('#listeAjout input');
	for(var a=0; a<forms.length; a++){
		alert(forms[a].type);
		if(form.type=='file'){
			form.append('file', forms[a].files[0]);
		}
	}


	xhr.open('POST', '../photo/insert-'+idAlbumPhoto);

	xhr.upload.onprogress=function(e){
		alert(e.loaded+'/'+e.total);
		progress.value=e.loaded;
		progress.max=e.total;	
	};

	xhr.onload=function(){
		alert('Upload done!');
	};
	xhr.send(form);
 var fileInput = document.querySelector('#1'),
    progress = document.querySelector('#progressUploadPhoto');alert('a');

    var xhr = new XMLHttpRequest();

    xhr.open('POST', 'http://exemple.com');
alert('b');
    xhr.upload.onprogress = function(e) {
        progress.value = e.loaded;
        progress.max = e.total;
    };
    
    xhr.onload = function() {
        alert('Upload terminé !');
    };
alert('c');
    var form = new FormData();alert('d');
	alert(fileInput.file);
    form.append('file', fileInput.files[0]);

    xhr.send(form);alert('e');

},false);
*/





