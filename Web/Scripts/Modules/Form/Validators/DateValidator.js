function DateValidator(){
}

DateValidator.prototype.isValid(value){
	var regexp=new RegExp(/^\d{4}-\d{2}-\d$/,'i');
	return regexp.test(value);
}

$.extend(DateValidator.prototype, new Validator() );
