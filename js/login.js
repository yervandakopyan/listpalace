function validateSubmit() {
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var email=$F('email');
	
	if(reg.test(email)==false) {
		$("email").up("div.fields").down('[name=message]').update("Enter valid email.");
		return false;
	} else {
		return true;
	}
}


var fields = new FieldManager([
	new Field("email",Validator.notEmpty,"Please enter a valid email."),
	new Field("password",Validator.notEmpty,"Please enter your password"),
	
]);

function validateFields() {
	return fields.validateUntil();
}
