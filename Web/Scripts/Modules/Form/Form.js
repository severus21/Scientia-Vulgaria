/*
 * name: Form
 * @description : 
 */
 
 function Form(id=''){
	 this.id=id;
	 this.form=null;
	 this.fields=[];
 }
 
 Form.prototype.setId=function(id){
	 this.id=id;
 }
 
 Form.prototype.getForm=function(){
	 this.form=$('#'+this.id);
 }
 
 Form.prototype.addField = function(field){
	 if( !(field typeof Field) ){
		alert("Must be a field object");
	 }
	 
	 this.fields.push(field);
 }
 
 Form.prototype.isValid= function(){
	 var i=0; var flag=true;
	 for(i ; i<this.fields.size() ; i++){
		 (flag) ? flag=this.fields[i].isValid() : this.fields[i].isValid();
	 }
	 return flag;
 }
