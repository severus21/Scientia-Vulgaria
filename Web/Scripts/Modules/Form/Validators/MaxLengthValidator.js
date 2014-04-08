function MaxLengthValidator(max){
	(max>0) ? this.max=max : this.max=0;
}

MaxLengthValidator.prototype.isValid(value){
	return value.length()<=this.max;
}

$.extend(MaxLengthValidator.prototype, new Validator() );
