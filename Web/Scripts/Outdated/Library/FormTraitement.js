function Input(name, regexp){
	this.name=name;
	this.regexp=regexp;
	
	this.input=document.getElementById(this.name);
	this.getToolTip=function(element){
		while(element=element.nextSibling) {
			if (element.className =='tooltip'){
				return element;
			}
		}
		return false;
	};

	this.toolTip=this.getToolTip(this.input);
	
	this.check=function(){
		if(this.regexp.test(this.input.value)){
			this.toolTip.style.display='none';
			return true;
		}
			this.toolTip.style.display='inline';
			return false;
	};
}

function CheckInputs(){
	this.inputs={};
	
	this.addInput=function(Input){
		this.inputs[Input.name]=Input;
	};
	
	this.check=function(){
		for(var id in this.inputs){
			if(!this.inputs[id].check()){
				return false;
			}
			return true;
		}
	};
}
	
function FormTraitement(formName, checkInputs, functiona){
	this.form=document.getElementById(formName);
	this.CheckInputs=checkInputs;
	this.inputs = document.querySelectorAll('#'+formName+' input');
	this.inputsLength=this.inputs.length;
	for (var a = 0 ;  a<this.inputsLength ; a++){
		
           if (this.inputs[a].type == 'text' || this.inputs[a].type == 'password' || this.inputs[a].type == 'email'){
				this.inputs[a].addEventListener('change',function(){
					checkInputs.inputs[this.id].check(this.id); // « this » représente l'input actuellement modifié
				},false);
			}
    }
		
	this.form.addEventListener('submit', function(event){
		var result= true;
        	for (var key in checkInputs){
			if(result==true){
				result = checkInputs.check();
			}
			break;
     		}
		if(!result){
			event.preventDefault();
		}
		if(functiona && result==true){alert('test');
			event.preventDefault();
			var functionSubmit=eval(functiona);
			functionSubmit();
			
		}
		
	}, false);
}
