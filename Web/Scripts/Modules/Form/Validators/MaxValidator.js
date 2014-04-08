function MaxValidator(max){
	this.max=max;
}

MaxValidator.prototype.isValid(value){
	return IsNumeric(value) && (value<=max);
}

$.extend(MaxValidator.prototype, new Validator());


