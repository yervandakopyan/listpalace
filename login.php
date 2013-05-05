<?
session_start();
require_once ("classes/autoloader.php"); 

	try {
		
		$validate=new validate();
		$users = new users();
		
		if($_POST['cmd']=='login') {
			$fields=array('Email'=>trim($_POST['email']), 'Password'=>trim($_POST['password']));
			$validate->validate_fields($fields);
			
			$login_array = array(
				'email'=>trim(strtolower($_POST['email'])),
				'password'=>trim(strtolower($_POST['password'])),
				'is_enabled'=>'1',
			);
			
			$user_info = current($users->getUsers($login_array));
			
			if(!$user_info) {
				throw new Exception("Invalid user name or password.");
			}
			
			if($user_info['is_verified']=='0') {
				throw new Exception("This email address has not been verified. Please click on the link  in you email that was sent upon registration");
			}
			
			if(!empty($user_info)) {

				$user_type = current($users->getUsersType(array('id_user_type'=>$user_info['id_user_type'])));

				if(empty($user_type)) {
					throw new Exception("Unkown Users. Please try again");
				}

				$_SESSION['id_user']=$user_info['id_user'];
				$_SESSION['id_user_type']=$user_info['id_user_type'];
				$_SESSION['user_type'] = $user_type['user_type'];


				//if they are coming from another page take them back to that page
				if(array_key_exists('callback', $_REQUEST)) {
					header("Location: {$_REQUEST[callback]}");
				} else {
					header( 'Location: /account/myaccount.php' );
				}

			}
			
			
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
	<?php include ("template/head_header.php"); ?>
	<link rel="stylesheet" href="/css/login.css" type="text/css" media="screen">
	<script src="/js/login.js" type="text/javascript"></script>
	
</head>
<body id="page1">
	
	<?php include ("template/header.php"); ?>
	
	<div class="error_alert"><?php echo $error_message; ?></div>
	<div class="clear"></div>
	
	<div class="error_box" id="error_box" style="display: block; display:none;"></div>
	<div class="clear"></div>
	
	<div class="login-form">
		<div class="login">
			<div id="lpmod">
				<span id="si3">
					<h3 id="lpHeader">Sign In</h3>
				</span>
			</div>
			<form method="post" onsubmit="return validateFields();" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="hidden" name="cmd" id="cmd" value="login" />
				<div class="even">
					<div class="words">Email: </div>
					<div class="fields"><input type="text" id="email" name="email" value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Password: </div>
					<div class="fields"><input type="password" id="password" name="password" value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
				
				<div class="login_button">
					<input id="submitID" type="submit" alt="Sign In" title="Sign In" tabindex="3" value="Sign In" onclick="return validateSubmit()";>
				</div>
				
				<div class="clear"></div>
				
				<a id="getSn" tabindex="6" target="_top" href="/register/register.php">Register</a>
				
			</form>
			
		</div>
	</div>
</body>
</html>