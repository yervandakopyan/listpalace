<?
session_start();
require_once ("../classes/autoloader.php"); 
	
if (!isset($_SESSION['id_user']) || !isset($_SESSION['id_user_type'])) {
		header( 'Location: ../login.php' );
}

$category=new category();
$validate=new validate();
$bid_request = new bid_request();
	
if($_POST) {
	
	try{
		
		$fields_array = array(
			'Service'=>$_POST['main_category'], 
			'Type of Job'=>$_POST['sub_category'],
			'City'=>$_POST['city'],
			'State'=>$_POST['state'],
			'Zipcode'=>$_POST['zip'],
			'Job Description'=>$_POST['job_description'],
		);

		$validate->validate_fields($fields_array);
		
		$images=array($_FILES['image1'], $_FILES['image2'], $_FILES['image3'], $_FILES['image4'], $_FILES['image5'], $_FILES['image6']);
	
		foreach ($images as $image_check) {
			if($image_check['name'] == "") {
				throw new Exception("Please upload 6 images to help make your estimate more accurate.");
			}

		}
		
		$bid_args = array( 
			'id_user'=>$_SESSION['id_user'],
			'id_user_type'=>$_SESSION['id_user_type'],
			'id_main_category'=>$_POST['main_category'],
			'id_sub_category'=>$_POST['sub_category'],
			'city'=>trim($_POST['city']),
			'state'=>$_POST['state'],
			'zipcode'=>trim($_POST['zip']),
			'job_sq_ft'=>trim($_POST['sq_ft']),
			'description'=>trim($_POST['job_description']),
		);
		
		$id_bid_request = $bid_request->add_bid($bid_args);
		
		$image_resize=new image();
		

		//upload the thumbnail sizes
		//$thumb_image=array($_FILES['image1']);
		//$thumbnail_path = "../images/job_thumbnails/";
		//$max_upload_width = 110;
		//$max_upload_height = 110;
		//$thumb_names['thumb_images'] = $image_resize->resize_image($thumb_image, $thumbnail_path, $max_upload_width, $max_upload_height);
		
		//upload the regular sizes
		$job_image_path = "../images/job_images/";
		$job_names = $image_resize->resize_image($images, $job_image_path , 420, 420);
		
		$thumb_path = str_replace('..', '', $thumbnail_path);
		$job_img_path = str_replace('..', '', $job_image_path);
		
		//merge both image names being returned into single array
		//$image_info = array_merge($job_names, $thumb_names);
		
		/*
		foreach($image_info['job_images'] as $key=>$value) {				
			$image_info_arges = array(
				'id_bid'=>$id_bid,
				'thumbnails'=>$thumb_path . $image_info['thumb_images'][$key],
				'job_images'=>$job_img_path . $value,
			);
			$id_image_array = $bid_request->add_images($image_info_arges);
		}
		*/
		
		$link = trim($_POST['vid_link']);
		parse_str(substr(strrchr($link, "?"), 1), $params);
		$video_link = $params['v'];
		
		
		$image_info_args=array(
			'id_bid_request'=>$id_bid_request,
			//'video_link'=>$video_link,     //removing this for now video not needed now
			'job_image_1'=>$job_img_path . $job_names[0],
			'job_image_2'=>$job_img_path . $job_names[1],
			'job_image_3'=>$job_img_path . $job_names[2],
			'job_image_4'=>$job_img_path . $job_names[3],
			'job_image_5'=>$job_img_path . $job_names[4],
			'job_image_6'=>$job_img_path . $job_names[5],
		);
		
		
		$id_image_array = $bid_request->add_images($image_info_args);
		
		$error_message = 
				"<div class='complete_icon'></div>
					<div class='error_ok'>
						Thank you for your request. Your request has been added successfully.
					</div>
					<div class='clear'>
				</div>";
		
	} catch(Exception $e) {
		$error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
	}
}

	
try {
	
	$cats="";
	$categories=$category->getCategories();
	foreach ($categories as $cat) {
		$cats .= "<option value=\"{$cat['id_main_category']}\">{$cat['display_name']}</option>\n";
	}
	
	$state="";
	$state_array = $validate->return_states();
	foreach ($state_array as $key=>$value) {
		$state .= "<option value=\"{$key}\">{$value}</option>\n";
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
	<link rel="stylesheet" href="/css/request_bids.css" type="text/css" media="screen">
	<script src="/js/request_bids.js" type="text/javascript"></script>
	
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
				<h3 style="text-align:center;">Request a Bid</h3>
                <em class="text-1 margin-bot">To submit a bid request please fill out the following form.</em>
            </article>
       </div>
    </div>
	
	<div class="clear"></div>
	
	<form method="post" enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		
		<div class="bid_request">
		
			<div class="cat_div">
				<div class="even">
					<div class="words">Service:</div>    
					<div class="fields">
						<select id="main_category" name="main_category">
							<option value="">--choose a category--</option>
								<?php echo $cats; ?>
						</select>
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			
			<div class="sub_cat_div" id="sub_cat_div" style="display:none;">
				<div class="even">
					<div class="words">Type of job:</div>    
					<div class="fields">
						<select id="sub_category" name="sub_category">
						</select>
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
			
			</div>
			
			<div class="clear"></div>
			
			<div class="job_info" id="job_info" style="display:none;">
				
				<div class="job_location">
					<div class="title" style="margin-right:91px; text-align:center; padding-top:15px; padding-bottom:10px;">
						Location of Job
					</div>
					
					<div class="clear"></div>
					
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
					
					<!--div class="even">
						<div class="words">Video link from youtube: </div>
						<div class="fields"><input type="text" id="vid_link" name="vid_link"  value="" />
							<span class="fields_msg" name="message"></span>
						</div>
						<div class="clear"></div>
					</div-->
					
				</div>
				
				<div class="clear"></div>
				
				<div class="job_details">
					
					<div class="title" style="margin-right:100px; text-align:center; padding-top:15px; padding-bottom:10px;">
						Upload detailed 6 images of the job
					</div>
					
					<div class="image_upload">
						<div class="name">
							<div class="even">
								<div class="fields"><input type="file" name="image1" id="image1" /></div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
						
						<div class="name">
							<div class="even">
								<div class="fields"><input type="file" name="image2" id="image2" /></div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
						
						<div class="name">
							<div class="even">
								<div class="fields"><input type="file" name="image3" id="image3" /></div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
						
						<div class="name">
							<div class="even">
								<div class="fields"><input type="file" name="image4" id="image4" /></div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
						
						<div class="name">
							<div class="even">
								<div class="fields"><input type="file" name="image5" id="image5" /></div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>

						<div class="name">
							<div class="even">
								<div class="fields"><input type="file" name="image6" id="image6" /></div>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>
						
					</div>
					
					<div class="clear"></div>
					
					<div class="title" style="margin-right:100px; text-align:center; padding-top:15px; padding-bottom:10px;">
						Job Details
					</div>
					
					<div class="clear"></div>
					
					<div class="even">
						<div class="words">SQ Ft of Job: </div>
						<div class="fields"><input type="text" id="sq_ft" name="sq_ft"  value="" />
							<span class="redstar">* <small>(If applicable)</small></span>
							<span class="fields_msg" name="message"></span>
						</div>
						<div class="clear"></div>
					</div>
					
					
					<div class="even">
						<div class="words" style="width:57%; padding-top:5px;">Job Description: <span class="redstar">(Please be specific)</span></div><br />
						<div class="fields" style="margin-left:29%; padding-top:5px;">
							<textarea cols="25" rows="7" name="job_description" style="width: 340px; height: 99px;"></textarea>
							<span class="redstar">*</span>
							<span class="fields_msg" name="message"></span>
						</div>
						<div class="clear"></div>
					</div>
					
					<input type="submit" value="Place Request" />
					
					<div class="clear"></div>
					
				</div><!--end of job_info -->
				
			</div>
			
		</div> <!--end of bid_request -->
		
	</form>
</body>
</html>