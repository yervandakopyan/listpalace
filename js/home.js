function add_post() {

	$('post_message').toggle();
}







/*

//school info search area 
function search_school () {
   var search_field=$F('search_field');
   if(!search_field) {
        return false;
    }
    
   new Ajax.Request('/ajax/search_for_school.php?search_field='+search_field, {
    onSuccess: function(response) {
       var result=response.responseText.evalJSON(true);
       
	   if(result.error) {
           $("alert").update(result.error);
       } else {
	       var str = "",
	           address = "",
	           id_school = "",
	           id_student = "",
	           school_name = "",
		       idx,
			   len = result.length;
			   input = "",
			   hidden_id = ""
                
            for( idx=0; idx < len; idx++ ) {
            
                //str += result[idx % len].school + "<br />";  //remarked because we are creating a seperate div for each value for styling
                //address +=result[idx % len].city + "," + " " + result[idx % len].state + " " + result[idx % len].zip + "<br />"; //remarked because we are creating a seperate div for each value for styling
                str += "<div class='school_name'>" + result[idx % len].school + "</div>";
            	address +="<div class='school_address'>" + result[idx % len].city + "," + " " + result[idx % len].state + " " + result[idx % len].zip + "</div>";
                id_school =result[idx % len].id_school;
                id_student =result[idx % len].id_student;
                school_name =result[idx % len].school;
                input += "<div class='sch_button'>" + "<input type='button' name='myschool_"+id_school+"' id='myschool_"+id_school+"' value='This is the school' onclick='set_cookie("+id_school+", "+id_student+", \""+school_name+"\")' />" + "</div>";
                //input += "<input type='button' name='myschool_"+id_school+"' id='myschool_"+id_school+"' value='This is my school' onclick='set_cookie("+id_school+")' />";
				
            }
            
            
            $('school_name_column').update(str);
            $('school_address_column').update(address);  
            $('id_student').writeAttribute('value',id_student); //if searched by student code only one school should be coming up in result
            $('school_button').update(input);
           
        Lightview.show({
		    href: "#quick_preview",
		    rel: "inline",
		    options: {
		        autosize: true,
		    }
	    });

       }        
        
    },
    
    onFailure: function(response) {
            if(response.status==403) {
                location.replace('/checkout.php?timedout=1');
            }
        },
        method: 'get',
        asynchronous: true
    });
   
    return false;  
  
    
}

//set cookie for result returned from school search 
function set_cookie (id_school, id_student, school_name) {
    //var school = id_school;
    //var student_id = $F('id_student');

    var date = new Date();
	date.setTime(date.getTime()+(1*24*60*60*1000));
	var expires = "; expires="+date.toUTCString();
    
    document.cookie = 'id_school'+"="+id_school+expires+"; path=/";

    if(! id_student) {
        date.setTime(date.getTime()+(-1*24*60*60*1000));
        var expires = "; expires="+date.toUTCString();
        document.cookie = 'id_student'+"="+""+expires+"; path=/";
    } else {
        document.cookie = 'id_student'+"="+id_student+expires+"; path=/"; 
    }
    
    $("school_name").update(school_name);
    $('school_chosen').update("Your school is set to <span style='color: #3399cc;'>" + school_name + ".</span> (If you would like to choose a new school please search below.)");
    
    Lightview.hide();
}

//checking for affiliation type is chosen
var AffiliateForm={};

    AffiliateForm.Affiliate = "affiliate";
    
    AffiliateForm.init = function() {
        AffiliateForm.AffiliateField = document.getElementById(AffiliateForm.Affiliate);
    };


AffiliateForm.ValidateAffiliate = function() {
    var check_affiliate = AffiliateForm.validateForm();
    
    
    if(AffiliateForm.isValidAffiliate() || check_affiliate==true) {
        Validator.removeWarningAfter(AffiliateForm.Affiliate);
        return true;
    } else {
        Validator.addWarningAfter(AffiliateForm.Affiliate);
        return false;
    }
};

AffiliateForm.isValidAffiliate = function() {
    return  StringUtils.isNotBlank(AffiliateForm.AffiliateField.value); 
}

AffiliateForm.validateForm = function() {
    var affiliation = $F('affiliate');
    var affiliate_result = true;

    if(affiliation == "") {
        Validator.addWarningAfter(AffiliateForm.Affiliate);
        affiliate_result = false;
    } else {
        affiliate_result = true;
    }
    
    return affiliate_result
    
}


var SchoolForm={};
    
    SchoolForm.SearchArea = "search_field";
    
     SchoolForm.init = function() {
        SchoolForm.SearchField = document.getElementById(SchoolForm.SearchArea);
     };
    
SchoolForm.validateSchool = function() {
	var check = SchoolForm.validateForm();
	
	if(SchoolForm.isValidSchool() || check==true){
		Validator.removeWarningAfter(SchoolForm.SearchArea);
        return true;
	}else{
		Validator.addWarningAfter(SchoolForm.SearchArea);
        return false;
	}
		
};    
   
SchoolForm.isValidSchool = function() {
    return  StringUtils.isNotBlank(SchoolForm.SearchField.value); 
};    
    
SchoolForm.validateForm = function() {
    var x = readCookie('id_school');
    var school_result=true;
    
    if (x == '-1') {
        school_result=false;
        Validator.addWarningAfter(SchoolForm.SearchArea);
    }else if (x == null) {
        school_result = false;
        Validator.addWarningAfter(SchoolForm.SearchArea);
    } else {
        school_result = true;
    }

    return school_result

}


function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}


//searches for charity organizations only similar function to search_school
function search_charity () {
    new Ajax.Request('/ajax/search_for_charity.php', {
        onSuccess: function(response) {
            var result=response.responseText.evalJSON(true);
        
            if(result.error) {
               $("alert").update(result.error);
           } else {
	            var str = "",
	            address = "",
	            id_school = "",
	            id_student = "",
	            school_name = "",
		        idx,
			    len = result.length;
			    input = "",
			    hidden_id = ""
                
            for( idx=0; idx < len; idx++ ) {
            
                //str += result[idx % len].school + "<br />";
                //address +=result[idx % len].city + "," + " " + result[idx % len].state + " " + result[idx % len].zip + "<br />";
            	str += "<div class='charity_name'>" + result[idx % len].school + "</div>";
            	address +="<div class='charity_address'>" + result[idx % len].city + "," + " " + result[idx % len].state + " " + result[idx % len].zip + "</div>";
                id_school =result[idx % len].id_school;
                id_student =result[idx % len].id_student;
                school_name =result[idx % len].school;
                //input += "<input type='button' name='myschool_"+id_school+"' id='myschool_"+id_school+"' value='This is the school' onclick='set_cookie("+id_school+", "+id_student+")' />";
                input += "<input type='button' name='myschool_"+id_school+"' id='myschool_"+id_school+"' value='This is my school' onclick='set_cookie("+id_school+", "+id_student+", \""+school_name+"\")' />";
            
            }
            
            
            $('charity_name_column').update(str);
            $('charity_address_column').update(address);  
            $('charity_button').update(input);
           
	
	    
        
            Lightview.show({
                href: "#charity_quick_preview",
                rel: "inline",
                options: {
                    autosize: true,
                }
            });

        }  
        
    },
    
    onFailure: function(response) {
        if(response.status==403) {
            location.replace('/checkout.php?timedout=1');
        }
    },
        method: 'get',
        asynchronous: true
    });
   
    return false;  
  
    
}



















var xmpie_response = '';
var xmpie_check_count=0;
var xmpie_timer;

document.observe("dom:loaded", function() {
    doCheckStatus();
});

function doCheckStatus() {
    
    var xmpie_order_product_id = $F('xmpie_order_product_id');
    var xmpie_order_id = $F('xmpie_order_id');
    var proof_id = $F('proof_id');
   
    $('wheel').update('<img src="/images/ccpwheel.gif"/><br />Please be patients while your order is being created and added to your cart...</p>');
   
    xmpie_check_count++;

	xmpie_job_checkstatus();

	xmpie_timer=setTimeout("doCheckStatus()",5000);
	
	if(xmpie_check_count >= 12){
		stopXmpieCheck();
		$("wheel").update("Timeout Waiting For Preview - Try Again Later");

		xmpie_check_count=0;
	}
	
	if(xmpie_response == 'done'){
	    location.replace('/xproduct_cart.php?OrderId='+xmpie_order_id+'&OrderProductId='+xmpie_order_product_id+'&ProofId='+proof_id);
	    //javascript:window.parent.location.href="http://some.url" 
	}
	
	
	if(xmpie_response == 'FAIL'){
		stopXmpieCheck();
		$("wheel").update("Error - Try Again Later");

	}
}


function stopXmpieCheck() {
	clearTimeout(xmpie_timer);
}

function xmpie_job_checkstatus() {
    
    var job_id = $F('job_id'); //uproduce job id
        
    new Ajax.Request('/ajax/xmpie_ustore_status.php?job_id='+job_id, {
        onSuccess: function(response) {
           
            xmpie_response=response.responseText.trim();
                
           
        },
         
        onFailure: function(response) {  
            if(response.status==403) {      
				location.replace('/cart.php?timedout=1');
		    }
        },
        
        method: 'get',
		asynchronous: false    
        

    });

}
*/