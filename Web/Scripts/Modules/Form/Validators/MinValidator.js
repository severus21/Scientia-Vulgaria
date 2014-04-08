function MinValidator(min){
	this.min=min;
}

MinValidator.prototype.isValid(value){
	return IsNumeric(value) && (value=>this.min);
}

$.extend(MinValidator.prototype, new Validator() );
