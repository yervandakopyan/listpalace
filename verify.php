<?
	require_once ("classes/autoloader.php"); 
	
	try{
		$users=new users();
		
		$email = $_REQUEST['email'];
		$id_user = $_REQUEST['user'];
		$args=array('email'=>$email, 'id_user'=>$id_user, 'is_verified'=>'0');
		
		$user_info=current($users->getUsers($args));
		
		if(!empty($user_info)) {
			
			$update_array=array('is_verified'=>'1');

			$update=$users->updateUsers($update_array, $id_user);
		
			if($user_info['id_user_type']=='1') {
				$error_message = 
					"<div class='complete_icon'></div>
						<div class='error_ok'>
							Your email has been confirmed. Please log in <a href='login.php' >Here</a>
						</div>
						<div class='clear'>
					</div>";
			} else {
				$error_message = 
					"<div class='complete_icon'></div>
						<div class='error_ok'>
							Your email has been confirmed. Please be patient while we approve your business for registration.<br />
							You will receive a email once your business is approved.
						</div>
						<div class='clear'>
					</div>";
			
			}
		} else {
			throw new Exception("We are unable to verify your email at this time. Please try clicking the link in your email again.");
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
	<link rel="stylesheet" href="/css/register.css" type="text/css" media="screen">
	<script src="/js/register.js" type="text/javascript"></script>
	
</head>
<body id="page1">
	
	<?php include ("template/header.php"); ?>
	
	<div class="error_alert"><?php echo $error_message; ?></div>
	<div class="clear"></div>
	
	<div class="error_box" id="error_box" style="display: block; display:none;"></div>
	<div class="clear"></div>
	
	
</body>
</html>