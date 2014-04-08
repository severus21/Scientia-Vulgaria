/*var progression=
	"var xhr = new XMLHttpRequest();"+
	"var formData=new FormData();"+
	"var progress=document.getElementById('progress');"+

	"formData.append('nom', document.getElementById('nom').value);"+
	"formData.append('fichier', document.getElementById('fichier').files[0]);"+
	"formData.append('formatAudio', document.getElementById('formatAudio').value);"+
	"formData.append('formatVideo', document.getElementById('formatVideo').value);"+
	"formData.append('qualite', document.getElementById('qualite').value);"+
	"formData.append('createur', document.getElementById('createur').value);"+
	"formData.append('miniature', document.getElementById('miniature').files[0];"+
	"formData.append('description', document.getElementById('description').value);"+
    	"xhr.open('POST', 'insert');"+
   	"xhr.upload.onprogress = function(e) {"+
      	 	"progress.value = e.loaded;"+
        	"progress.max = e.total;"+
		"alert(e.total);"+
    	"};"+
    	"xhr.onload = function() {"+
       	 	"alert('Upload terminé !');"+
    	"};"+
   	"xhr.send(formData);"+
	"event.preventDefault();";
*/
var checkInputs= new CheckInputs();
checkInputs.addInput(new Input('nom', /^[a-zA-Z0-9\'\,\?;\.:\/\!_\(\)=àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ -]{4,50}$/));
checkInputs.addInput(new Input('createur', /^[a-zA-Z0-9 àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ_-]{2,30}$/));

var formTraitement=new FormTraitement('form_insert', checkInputs);


	




