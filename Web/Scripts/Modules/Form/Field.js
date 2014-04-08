/*
 * name: Field
 * @description : 
 */

function Field(id, required=false){
        this.id='';
        this.field=$('#'+this.id);
        this.tooltip=null;
        this.required=false;
        this.validators=[];
        this.value='';
}

Field.prototype.addValidator(validator){
	 if( !(validator typeof Validator) ){
		alert("Must be a validator object");
	 }
	 
	 this.validators.push(validator);
}

Field.prototype.getTooltip(){
	var flag=false; var element=this.field;
	while(!flag && element=element.next()) {
		if (element.className =='tooltip'){
			this.tooltip=element;
		}
	}
}

Field.prototype.getValue(){
	this.value=this.field.value;
}


Field.prototype.displayTooltip(){
	if(this.id=='' || this.tooltip==null)
		return null;
	
	this.tooltip.style.display='inline';
}

Field.prototype.isValid(){
	var n=this.validators.size(); var i=0; var flag=true;
	this.getValue();
	while( i<n && flag){
		flag=this.validatros[i].isValid(this.value);
		i++;
	} 
	(flag) ? null : this.displayTooltip();
}
