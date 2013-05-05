/*many functions are same based on user type*/
document.observe('dom:loaded', function() {
	//reset select box
	$('user_type').selectedIndex='';
	
    $('user_type').observe('change',function(e){
		check_user();
    });
	
	$('insured').observe('change',function(e){
		show_license_field();
    });

	$('contact_phone').observe('blur', function(e) {
		validatePhone();
	});
	
});

function check_user () {
	var type = $F('user_type');
	
	//value 1 = homeowner
	if (type=='1') {
		$('contractor_register').hide();
		$('homeowner_registration').show();
	//value 2 = contractor
	} else if(type=='2'){
		$('homeowner_registration').hide();
		$('contractor_register').show();
	} else {
		$('homeowner_registration').hide();
		$('contractor_register').hide();
	}
}

function show_license_field() {
	if($F('insured') == 'yes') {
		$('license').show();
	} else if ($F('insured') == 'no') {
		$('license').hide();
	} else {
		$('license').hide();
	}
}

//check if fields are filled in for contractor
var fields = new FieldManager([
	new Field("email",Validator.notEmpty,"Enter a valid email."),
	new Field("password",Validator.notEmpty,"Enter a password"),
	new Field("password2",Validator.notEmpty,"Confirm your password."),
	new Field("buss_name",Validator.notEmpty,"Enter your business name."),
	new Field("first_name",Validator.notEmpty,"Enter your first name."),
	new Field("last_name",Validator.notEmpty,"Enter your last name."),
	new Field("insured",Validator.notEmpty,"Are you insured?"),
	new Field("license_num",Validator.validInsured,"Enter your license number."),
	new Field("bonded",Validator.notEmpty,"Are you bonded?"),
	new Field("address",Validator.notEmpty,"Enter your address."),
	new Field("city",Validator.notEmpty,"Enter the city."),
	new Field("state",Validator.notEmpty,"Choose your state."),
	new Field("zip",Validator.notEmpty,"Enter the zipcode."),
	new Field("contact_phone",Validator.notEmpty,"Enter your phone number."),
	new Field("contract_type",Validator.notEmpty,"Choose your type of work."),
	new Field("specialty",Validator.notEmpty,"Choose your specialty."),
	
]);
//check if fields are filled in for contractor
function validateFields() {
	return fields.validateUntil();
}


//check if fields are filled in for homeowner
var h_fields = new FieldManager([
	new Field("email_home",Validator.notEmpty,"Enter a valid email."),
	new Field("password_home",Validator.notEmpty,"Enter a password"),
	new Field("password2_home",Validator.notEmpty,"Confirm your password."),
	new Field("first_name_home",Validator.notEmpty,"Enter your first name."),
	new Field("last_name_home",Validator.notEmpty,"Enter your last name."),
	new Field("residence_type",Validator.notEmpty,"Choose a residence type?"),
	new Field("city_home",Validator.notEmpty,"Enter the city."),
	new Field("state_home",Validator.notEmpty,"Choose your state."),
	new Field("zip_home",Validator.notEmpty,"Enter the zipcode."),
	new Field("contact_phone_home",Validator.notEmpty,"Enter your phone number."),
	
]);
//check if fields are filled in for homeowner
function validateFieldsHome() {
	return h_fields.validateUntil();
}



function validateSubmit() {
	var password=$F('password').toLowerCase();
	var password2=$F('password2').toLowerCase();
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var email=$F('email');
	
	if(reg.test(email)==false) {
		$("email").up("div.fields").down('[name=message]').update("Enter valid email.");
		return false;
	} else if(password.length < 6) {
		$("password").up("div.fields").down('[name=message]').update("Minimum 6 characters.");
	} else if(password != password2) {
		$("password2").up("div.fields").down('[name=message]').update("Passwords do not match.");
		return false;
	} else {
		$('error_box').hide();
		return true;
	}
}

function validateSubmitHome() {
	var password=$F('password_home').toLowerCase();
	var password2=$F('password2_home').toLowerCase();
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
	var email=$F('email_home');
	
	if(reg.test(email)==false) {
		$("email_home").up("div.fields").down('[name=message]').update("Enter valid email.");
		return false;
	} else if(password.length < 6) {
		$("password_home").up("div.fields").down('[name=message]').update("Minimum 6 characters.");
		return false;
	} else if(password != password2) {
		$("password2_home").up("div.fields").down('[name=message]').update("Passwords do not match.");
		return false;
	} else {
		$('error_box').hide();
		return true;
	}
}


function validatePhone() {
	var phone=$F('contact_phone').replace(/[^0-9]/g,'');
	if (phone.length != 10) {
		$("contact_phone").up("div.fields").down('[name=message]').update("Invalid phone number.");
	} else {
		var area = phone.substring(0,3);
		var prefix = phone.substring(3,6);
		var line = phone.substring(6);
		$('contact_phone').writeAttribute("value", '(' + area + ') ' + prefix + '-' + line);
	}
	
}
