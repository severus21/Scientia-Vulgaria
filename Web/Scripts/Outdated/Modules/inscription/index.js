var checkInputs= new CheckInputs();
checkInputs.addInput(new Input('login',/^[a-zA-Z0-9_@]{4,20}$/i));
checkInputs.addInput(new Input('passworda',/^[a-zA-Z0-9_@]{4,30}$/i));
document.getElementById('passworda').onkeyup=function (){
	checkInputs.addInput(new Input('passwordb',new RegExp('^'+document.getElementById('passworda').value+'$','i')));	
};
checkInputs.addInput(new Input('nom',/^[a-zA-Z\' -]{3,20}$/i));
checkInputs.addInput(new Input('prenom',/^[a-zA-Z\' -]{3,20}$/i));
checkInputs.addInput(new Input('email',/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/i));
		
var formTraitement=new FormTraitement('inscription', checkInputs);

var xhr=new XMLHttpRequest();
var form=new FormData();
document.getElementById('login').addEventListener('blur',function(){
	form.append('login', document.getElementById('login').value);
	xhr.open('POST', 'login-exists',true);
	xhr.onreadystatechange=function(){
	if (xhr.readyState == 4 && xhr.status == 200) {
		var reponseXhr=xhr.responseText;
		reponseXhr=reponseXhr.replace(/\s/g,'');
		reponseXhr=reponseXhr.replace(/.+reponseXhr==/,'');
		reponseXhr=reponseXhr.replace(/==.+$/,'');					
		if(reponseXhr==='true'){
			checkInputs.getInput('login').toolTipb.style.display='inline';
		}else{
			checkInputs.getInput('login').toolTipb.style.display='none';
					}	
		}
	};
	xhr.send(form);
},false);	
