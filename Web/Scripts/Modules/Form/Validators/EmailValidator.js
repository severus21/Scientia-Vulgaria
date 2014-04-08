function EmailValidator(){
}

EmailValidator.prototype.isValid(value){
	var regexp=new RegExp(/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/,'i');
	return regexp.test(value);
}

$.extend(EmailValidator.prototype, new Validator() );
