<?
	
	require_once ("../classes/autoloader.php"); 
		
	$email = new email();	
	$users=new users();
	$category=new category();
	$validate=new validate();
	//CONTRACTOR REGISTRATION//
	if ($_POST['cmd']=='register_contractor') {
		try{
			
			if(strlen(trim($_POST['email'])) < 6) {
				throw new Exception("Password must be a minimum of 6 characters.");
			}
		
			$fields=array(
				'Email'=>trim($_POST['email']),
				'Password'=>trim($_POST['password']),
				'Confirm Password'=>trim($_POST['password2']),
				'Business Name'=>trim($_POST['buss_name']),
				'First Name'=>trim($_POST['first_name']),
				'Last Name'=>trim($_POST['last_name']),
				'Licensed'=>$_POST['insured'],
				'Bonded'=>$_POST['bonded'],
				'Address'=>trim($_POST['address']),
				'City'=>trim($_POST['city']),
				'State'=>trim($_POST['state']),
				'Zipcode'=>trim($_POST['zip']),
				'Phone Number'=>trim($_POST['contact_phone']),
				'Type of work'=>$_POST['contract_type'],
				'Specialty'=>$_POST['specialty'],
				);
			//validate all fields are filled in
			$validate->validate_fields($fields);
			
			//check if user exists
			$user_exists=array('email'=>trim(strtolower($_POST['email'])));
			
			$user_check = $users->getUsers($user_exists);
			
			if($user_check) {
				throw new Exception("An account already exists for this email address.");
			}
			
			if (trim(strtolower($_POST['password'])) != trim(strtolower($_POST['password2']))) {
				throw new Exception("Please make sure your passwords match.");
			}
			
			//validate phone number and reformat it
			$phone = $validate->validate_telephone_number(trim($_POST['contact_phone']));
			
			//add to users table
			$users_info = array( 
				'id_user_type'=>$_POST['usr_type'],
				'email'=>trim(strtolower($_POST['email'])),
				'password'=>trim(strtolower($_POST['password'])),
				'is_enabled'=>'0',
			);
			
			$id_user=$users->add_user($users_info);
			
			$user_info_ext = array(
				'id_user'=>$id_user,
				'business_name'=>trim($_POST['buss_name']),
				'first_name'=>trim(ucfirst($_POST['first_name'])),
				'last_name'=>trim(ucfirst($_POST['last_name'])),
				'insured'=>$_POST['insured'],
				'license_number'=>trim($_POST['license_num']),
				'bonded'=>$_POST['bonded'],
				'address'=>trim($_POST['address']),
				'address2'=>trim($_POST['address2']),
				'city'=>trim(ucfirst($_POST['city'])),
				'state'=>$_POST['state'],
				'zipcode'=>trim($_POST['zip']),
				'phone'=>$phone,
				'website'=>trim(strtolower($_POST['website'])),
				'work_type'=>$_POST['contract_type'],
				'id_category_specialty'=>$_POST['specialty'],
				
			);
			
			//add to user info table
			$users->add_user_contractor($user_info_ext);
			
			$link="http://listpalace.com/verify.php?user={$id_user}&email={$_POST['email']}";
			
			$mail_info=array(
			'template'=>'../classes/email_template/validateContractor.html',
			'to'=>trim($_POST['email']),
			'%business_name%'=>trim($_POST['buss_name']),
			'%link%'=>$link,
			"subject"=>"Listpalace.com registration confirmation",
			);
			$mail = $email->sendMail($mail_info);
			
			$error_message = 
					"<div class='complete_icon'></div>
						<div class='error_ok'>
							Thank you for registering your business. You will receive a email once your account has been approved.
						</div>
						<div class='clear'>
					</div>";
			
		} catch(Exception $e) {
			$error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
		}
	}
	
	//HOMEOWNER REGISTRATION//
	if ($_POST['cmd']=='register_homeowner') {
		try{
			if(strlen(trim($_POST['email_home'])) < 6) {
				throw new Exception("Password must be a minimum of 6 characters.");
			}
		
			$fields=array(
				'Email'=>trim($_POST['email_home']),
				'Password'=>trim($_POST['password_home']),
				'Confirm Password'=>trim($_POST['password2_home']),
				'First Name'=>trim($_POST['first_name_home']),
				'Last Name'=>trim($_POST['last_name_home']),
				'Residency Type'=>$_POST['residence_type'],
				'City'=>trim($_POST['city_home']),
				'State'=>trim($_POST['state_home']),
				'Zipcode'=>trim($_POST['zip_home']),
				'Phone Number'=>trim($_POST['contact_phone_home']),
				);
			//validate all fields are filled in
			$validate->validate_fields($fields);
			
			//check if user exists
			$user_exists=array('email'=>trim(strtolower($_POST['email_home'])));			
			$user_check = $users->getUsers($user_exists);
			
			if($user_check) {
				throw new Exception("An account already exists for this email address.");
			}
			
			if (trim(strtolower($_POST['password_home'])) != trim(strtolower($_POST['password2_home']))) {
				throw new Exception("Please make sure your passwords match.");
			}
			
			//validate phone number and reformat it
			$phone_home = $validate->validate_telephone_number(trim($_POST['contact_phone_home']));
			
			$users_info = array( 
				'id_user_type'=>$_POST['usr_type'],
				'email'=>trim(strtolower($_POST['email_home'])),
				'password'=>trim(strtolower($_POST['password_home'])),
			);
			
			$id_user=$users->add_user($users_info);
			
			$user_ext_home = array(
				'id_user'=>$id_user,
				'first_name'=>trim(ucfirst($_POST['first_name_home'])),
				'last_name'=>trim(ucfirst($_POST['last_name_home'])),
				'residence_type'=>$_POST['residence_type'],
				'city'=>trim(ucfirst($_POST['city_home'])),
				'state'=>$_POST['state_home'],
				'zipcode'=>trim($_POST['zip_home']),
				'phone'=>$phone_home,
			);
		
			$users->add_user_homeowner($user_ext_home);
			
			$link="http://listpalace.com/verify.php?user={$id_user}&email={$_POST['email_home']}";
			
			$mail_info=array(
			'template'=>'../classes/email_template/validateHomeowner.html',
			'to'=>trim($_POST['email_home']),
			'%link%'=>$link,
			"subject"=>"Listpalace.com registration confirmation",
			);
			$mail = $email->sendMail($mail_info);
			
			$error_message = 
					"<div class='complete_icon'></div>
						<div class='error_ok'>
							Thank you for registering. You will receive a email shortly.
						</div>
						<div class='clear'>
					</div>";
					
		} catch(Exception $e) {
			$error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
		}
	}
	
	try{
		
		
		$site_users=$users->getUsersType();
		$user="";
		$state="";
		$cats="";
		
		foreach($site_users as $site_user) {
			$user .= "<option value=\"{$site_user['id_user_type']}\">{$site_user['user_type']}</option>\n";
		}
		
		$state_array = $validate->return_states();
		foreach ($state_array as $key=>$value) {
			$state .= "<option value=\"{$key}\">{$value}</option>\n";
		}
		
		$categories=$category->getCategories();
		
		foreach ($categories as $cat) {
			$cats .= "<option value=\"{$cat['id_main_category']}\">{$cat['display_name']}</option>\n";
		}
		
	} catch(Exception $e) {
		$error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
	}
	
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration for Bidding</title>
    <meta charset="utf-8">
	<?php include ("../template/head_header.php"); ?>
	<link rel="stylesheet" href="/css/register.css" type="text/css" media="screen">
	<script src="/js/register.js" type="text/javascript"></script>
	
</head>
<body id="page1">
	
	<?php include ("../template/header.php"); ?>
	
	<div class="error_alert"><?php echo $error_message; ?></div>
	<div class="clear"></div>
	
	<div class="error_box" id="error_box" style="display: block; display:none;"></div>
	<div class="clear"></div>
	
	<div class="container_12" style="width:700px; padding-top:25px;">
		<div class="wrapper">
			<article class="grid_8" style="width:640px; text-align:center;"">
				<h3 style="text-align:center;">Registration</h3>
                <em class="text-1 margin-bot">Please choose a user type and fill out the corresponding form for registration.</em>
            </article>
       </div>
    </div>
    
	<div class="register_form">
	
		<div class="even">
			<div class="words">Are you a... </div>
			<div class="fields">
				<select id="user_type" name="user_type">
					<option value="">Choose One</option>
					<?php echo $user; ?>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		
		<form method="post" onsubmit="return validateFields();" id="contractor_register" style="display:none;" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="cmd" id="cmd" value="register_contractor" />
			<input type="hidden" name="usr_type" id="usr_type" value="2" />
			
			<div class="title">Contractor Registration:</div>
			<div class="clear"></div>
				
			<div class="register_fields">
			
				<div class="even">
					<div class="words">Email / Login: </div>
					<div class="fields"><input type="text" id="email" name="email"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
						</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Password: </div>
					<div class="fields"><input type="password" id="password" name="password"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Confirm Password: </div>
					<div class="fields"><input type="password" id="password2" name="password2"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
						</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Business Name: </div>
					<div class="fields"><input type="text" id="buss_name" name="buss_name" value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">First Name: </div>
					<div class="fields"><input type="text" id="first_name" name="first_name"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">last Name: </div>
					<div class="fields"><input type="text" id="last_name" name="last_name"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Are You Licensed?</div>
					<div class="fields">
						<select id="insured" name="insured">
							<option value="">Choose One:</option>
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div id="license" class="even" style="display:none;">
					<div class="words">License Number: </div>
					<div class="fields"><input type="text" id="license_num" name="license_num"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Are You Bonded?</div>
					<div class="fields">
						<select id="bonded" name="bonded">
							<option value="">Choose One:</option>
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Address: </div>
					<div class="fields"><input type="text" id="address" name="address"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Address 2: </div>
					<div class="fields"><input type="text" id="address2" name="address2"  value="" /></div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">City: </div>
					<div class="fields"><input type="text" id="city" name="city"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">State:</div>    
					<div class="fields">
						<select id="state" name="state">
							<option value="">State</option>
								<?php echo $state; ?>
						</select>
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
			
				<div class="even">
					<div class="words">Zipcode: </div>
					<div class="fields"><input type="text" id="zip" name="zip"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Phone: </div>
					<div class="fields"><input type="text" id="contact_phone" name="contact_phone" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Website: </div>
					<div class="fields"><input type="text" id="website" name="website" value="" /></div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Type of Work: </div>
					<div class="fields">
						<select id="contract_type" name="contract_type">
							<option value="">Choose One:</option>
							<option value="commercial">Commercial</option>
							<option value="residential">Residential</option>
							<option value="both">Both</option>
						</select>
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Specialty:</div>    
					<div class="fields">
						<select id="specialty" name="specialty">
							<option value="">Specialty</option>
								<?php echo $cats; ?>
						</select>
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
				
				<div class="even">
					<div class="words"></div>
					<div class="fields"><input type="submit" id="button" value="Register" onclick="return validateSubmit()"; /></div>
					<div class="clear"></div>
				</div>
				
					
			</div><!--end of register_fields-->
			
		</form><!--end of contractor form-->
		
		<div class="clear"></div>
		
		<form method="post" id="homeowner_registration" onsubmit="return validateFieldsHome();" style="display:none;" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<input type="hidden" name="cmd" id="cmd" value="register_homeowner" />
			<input type="hidden" name="usr_type" id="usr_type" value="1" />
			
			<div class="title">Home Owner / HOA Registration:</div>
			<div class="clear"></div>
				
			<div class="register_fields">
				
				<div class="even">
					<div class="words">Email / Login: </div>
					<div class="fields"><input type="text" id="email_home" name="email_home"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Password: </div>
					<div class="fields"><input type="password" id="password_home" name="password_home"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Confirm Password: </div>
					<div class="fields"><input type="password" id="password2_home" name="password2_home"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
			
				<div class="even">
					<div class="words">First Name: </div>
					<div class="fields"><input type="text" id="first_name_home" name="first_name_home"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Last Name: </div>
					<div class="fields"><input type="text" id="last_name_home" name="last_name_home"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Type of Residence</div>
					<div class="fields">
						<select id="residence_type" name="residence_type">
							<option value="">Choose One:</option>
							<option value="commercial">Commercial</option>
							<option value="residential">Residential</option>
						</select>
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">City: </div>
					<div class="fields"><input type="text" id="city_home" name="city_home"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">State:</div>    
					<div class="fields">
						<select id="state_home" name="state_home">
							<option value="">State</option>
								<?php echo $state; ?>
						</select>
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
			
				<div class="even">
					<div class="words">Zipcode: </div>
					<div class="fields"><input type="text" id="zip_home" name="zip_home"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Phone: </div>
					<div class="fields"><input type="text" id="contact_phone_home" name="contact_phone_home" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
				
				<div class="even">
					<div class="words"></div>
					<div class="fields"><input type="submit" id="button_home" value="Register" onclick="return validateSubmitHome();" /></div>
					<div class="clear"></div>
				</div>
					
			</div><!--end of register_fields-->
			
		</form><!-- end of homeowner form-->
		
	</div><!-- end of register_form-->

	
</body>
</html>