<?php
	
	session_start();
	require_once ("classes/autoloader.php"); 
	//function in autoloader to check if logged in
	loged_in();
	
	if($_POST && array_key_exists("job_quote", $_POST)) {
		
		try{
			//functionality to add a job quote or bid from a contractor

			if($_SESSION['user_type'] == "Home Owner") {
				//throw new Exception("You are registered as a home owner and cannot give a quote for a project");
			}

			$completion_time = $_POST['years'] . " year(s) " . $_POST['months'] . " month(s) " . $_POST['days'] . " day(s)";
			$quote_notes = trim($_POST['bid_notes']);
			$amount = trim(number_format(preg_replace('/[\$,]/', '', $_POST['bid_amount']),2));
			

		} catch(Exception $e) {
			$error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
		}
	}


	try {

		$back_url = $_SERVER['HTTP_REFERER'];

		if(!array_key_exists('id_bid_request', $_REQUEST)) {
			header("Location: {$back_url}");
		}
	
		$id_bid_request=$_REQUEST['id_bid_request'];
		
		$bid_request = new bid_request();
		$categories = new category();
		
		$bid_args = array('a.id_bid_request'=>$id_bid_request);

		$bid_info = current($bid_request->getBidInfoWithImage($bid_args));
        
		//pop out only the image path from the array
		$image_array = array_slice($bid_info, -7, 6);

		$img_url='';
		foreach($image_array as $key=>$value) {
			$img_url .="<a href='{$value}' class='lightview' rel='set[myset]'><img class='thumb_image' src={$value} /></a>";
			$video = "<a href='http://www.youtube.com/v/{$bid_info[video_link]}' class='lightview' rel='set[myset][flash]'>
				<img src='/images/play_button.png' class='thumb_image' />
			</a>";
			
		}

		$sub_cat_id=$bid_info['id_sub_category'];
		$main_cat_id=$bid_info['id_main_category'];
		
		//get main category info
		$main_cat_args = array('id_main_category'=>$main_cat_id);
		$main_cat_details=current($categories->getCategoryInfo($main_cat_args));
		
		//get sub category info
		$sub_cat_args=array('id_sub_category'=>$sub_cat_id);
		$sub_cat_details=current($categories->getSubCategoryDetails($sub_cat_args));
		
		//numbers to display as drop down for estimated time of completion
		$numbers_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", 
			"18", "19", "20", "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31",
		);
		foreach($numbers_array as $key=>$value) {
			$years .= "<option value=\"{$key}\">{$value}</option>\n";
			$months .= "<option value=\"{$key}\">{$value}</option>\n";
			$days .= "<option value=\"{$key}\">{$value}</option>\n";
		}
		
		
		//if the person who created the request is the same as the person logged in right now
		//show the bids placed for the order and see if they accept or not
        if($bid_info['id_user'] == $_SESSION['id_user']) {
            $user_logged_in = true;
            
            
        }
        
		
		
		
	} catch(Exception $e) {
		$error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Request Bids</title>
    <meta charset="utf-8">
	<?php include ("template/head_header.php"); ?>
	<link rel="stylesheet" href="/css/bid_details.css" type="text/css" media="screen">	
</head>
<body id="page1">
	
	<?php include ("template/header.php"); ?>
	
	<div class="error_alert"><?php echo $error_message; ?></div>
	<div class="clear"></div>
	
	<div class="error_box" id="error_box" style="display: block; display:none;"></div>
	<div class="clear"></div>
	
	<div class='job_details_box'>
		
		<div class='thumbs'>
			<?php echo $img_url; 
				//echo $video;
			?>
		</div>
	
		<div class="job_desc_box_wrapper">
			<div class="cat_box">
				<?php
					echo $main_cat_details['display_name'] . " - " . $sub_cat_details['sub_display_name'];
				?>
			</div>
			
			<div class="description">
				<?php
					echo $bid_info['description'];
				?>
			</div>
			
			<div class="sqft">
				<?php
					echo "Square Feet: " . $bid_info['job_sq_ft'];
				?>
			</div>
			
			<div class="location">
				<?php
					echo $bid_info['city'] . " , " . $bid_info['state'] . " " . $bid_info['zipcode'];
				?>
			
			</div>
			
		</div>
		
		<!--hide the form for submitting estimate if the person viewing the page is the same as the person who had created the post-->
		<?php if (!$user_logged_in):?>
            <div class="bid_box_wrapper">
                <div class="bid_box">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="cmd" value="bid" />
                        <input type="hidden" name="id_bid_request" value=<? echo $bid_info['id_bid_request']; ?> />
                    
                        <div class="even">
                            <div class="words">Bid Notes</div>

                            <div class="clear"></div>

                            <div class="fields"><textarea cols="25" rows="7" name="bid_notes" style="width: 340px; height: 99px;"></textarea>
                                <span class="redstar">*</span>
                                <span class="fields_msg" name="message"></span>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="estimate"> 
                            <div class="words" style="width:58%;">Estimated Time to Complete: </div>

                            <div class="clear"></div>
                        
                            <select id="years_est" name="years">
                                <option value="">--Years--</option>
                                    <?php echo $years; ?>
                            </select>
                            <select id="months_est" name="months">
                                <option value="">--Months--</option>
                                    <?php echo $months; ?>
                            </select>
                            <select id="days_est" name="days">
                                <option value="">--Days--</option>
                                    <?php echo $days; ?>
                            </select>

                        </div>

                        <div class="clear"></div>

                        <div class="even">
                            <div class="words">Amount: $</div>
                            <div class="fields"><input type="text" name="bid_amount" id="bid_amount" value="" />
                                <span class="redstar">*</span>
                                <span class="fields_msg" name="message"></span>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <input type="submit" value="Place Bid" name="job_quote" />
                
                    </form>
                </div>
            </div>
		<?endif;?>
		
		
	</div>
	
	
	
	
</body>
</html>