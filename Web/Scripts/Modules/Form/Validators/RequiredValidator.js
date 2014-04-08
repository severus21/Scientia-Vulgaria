function RequiredValidator(){
}

RequiredValidator.prototype.isValid(value){
	return value!='';
}

$.extend(RequiredValidator.prototype, new Validator() );
