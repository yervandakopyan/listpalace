var Validator = new (function() {
	this.notEmpty = function(val) {
		return !val.empty();
	}
	this.validState = function(val) {
		return !val.empty() && val != "XX";
	}
	this.validInsured=function(val) {
		if($F('insured')=='yes'){
			return !val.empty();
		} else {
			return val.empty();
		}
	}
	
})();

//confirm passwords match
var Confirm = new (function() {
	this.confirmPasswords=function(val) {
		
		if($F('password1').length < 6) {
			$('error_box').show();
			$('error_box').update('Password must be a minimum of 6 letters or numbers');
			return false;
		}else if($F('password2') != $F('password')) {
			$('error_box').update("");
			$('error_box').show();
			$('error_box').update('Password must be a minimum of 6 letters or numbers');
			return false;
		} else {
			return true();
		}
	}
	
}) ();

var Field = function(field,method,message) {
	this.field = field;
	this.method = method;
	this.message = message;
}

Field.prototype.validate = function() {
	if((this.method)($F(this.field))) {
			return true;
	} else {
		return this.message;
	}
};

var FieldManager = function() {
	if(arguments.length == 1) {
		this.fields = arguments[0];
	} else {
	this.fields = [];
	}
}

FieldManager.prototype.add = function(field) {
	this.fields.push(field);
}

FieldManager.prototype.validate = function(stopOnError) {
	var success = true;
	if(arguments.length <= 0) {
		stopOnError = false;
	}
	for( idx in this.fields ) {
		if(this.fields.hasOwnProperty(idx)) {
			if(this.fields[idx].validate() !== true) {
				success = false;
				$(this.fields[idx].field).up("div.fields").down('[name=message]').update(this.fields[idx].message);
				//alert(this.fields[idx].message);
					if(stopOnError) {	
						break;
					}
			}
		}
	}
	
	return success;
}

FieldManager.prototype.validateAll = function() {
	return this.validate(false);
}

FieldManager.prototype.validateUntil = function() {
	return this.validate(true);
} 