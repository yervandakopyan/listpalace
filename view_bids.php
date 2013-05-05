<?
	session_start();
	
	require_once ("classes/autoloader.php"); 
	
	//function in autoloader to check if logged in
	loged_in();
	
	$category=new category();
	$validator=new validate();
	$bid_request=new bid_request();
	
	try {
		//get url and pass to get the category from url
		$url = $_SERVER['REQUEST_URI'];
		$url_category=$validator->getCategoryNameFromUrl($url);
		
		//get the main category id 
		$cat_args=array('category_name'=>$url_category);
		$main_cat_info = current($category->getCategoryInfo($cat_args));
		
		if(empty($main_cat_info)) {
			throw new Exception("Invalid request. Please choose a category.");
		}
		
		//get all sub category associated with main category
		$sub_cats = $category->get_catAssociationDetails($main_cat_info['id_main_category']);
	} catch(Exception $e) {
		$error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
	}
	
	
	try {
		$sub_cat_id = '';
		
		if(array_key_exists('sub_cat', $_REQUEST)) {
			$sub_cat_id=$_REQUEST['sub_cat'];
		}
		
		$bid_request_args = array(
			'id_sub_category'=>$sub_cat_id, 
			'id_main_category'=>$main_cat_info['id_main_category']
		
		);
		$bid_request_info = $bid_request->getBids($bid_request_args);
		
		if(empty($bid_request_info)) {
			$error_message = "<div class='error_icon'></div><div class='error'>'No jobs found at the moment.</div><div class='clear'></div>";
		}
		
		$job_array=array();
		foreach ($bid_request_info as $job_request) {
			$sub_cat_args=array('id_sub_category'=>$job_request['id_sub_category']);
			
			$sub_cat_info = current($category->getSubCategoryDetails($sub_cat_args));			
			array_push($job_array, array(
				'id_bid_request'=>$job_request['id_bid_request'],
				'id_user'=>$job_request['id_user'],
				'id_main_category'=>$job_request['id_main_category'],
				'id_sub_category'=>$job_request['id_sub_category'],
				'job_sq_ft'=>$job_request['job_sq_ft'],
				'description'=>$job_request['description'],
				'city'=>$job_request['city'],
				'state'=>$job_request['state'],
				'zipcode'=>$job_request['zipcode'],
				'sub_display_name'=>$sub_cat_info['sub_display_name'],
			));
			
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
	<link rel="stylesheet" href="/css/view_bids.css" type="text/css" media="screen">	
</head>
<body id="page1">
	
	<?php include ("template/header.php"); ?>
	
	<div class="error_alert"><?php echo $error_message; ?></div>
	<div class="clear"></div>
	
	<div class="error_box" id="error_box" style="display: block; display:none;"></div>
	<div class="clear"></div>
	

	<?php
		foreach ($sub_cats as $cat) {
			$cat_display = str_replace (" ", "-", $cat['sub_display_name']);
			$main_cat_display = str_replace (" ", "-", $main_cat_info['display_name']);
			$cat_url = "/contractor-jobs/" .strtolower(str_replace("-&-","-", $main_cat_display)) . "/" . strtolower(str_replace("-&-","-",$cat_display));
			$form_id = "sub_category_" . $cat['id_sub_category'];
			$form  = "<form id='{$form_id}' method='post' action='{$cat_url}'>" . 
					"<input type='hidden' name='id_cat' id='id_cat' value='{$main_cat_info[id_main_category]}' />" .
					"<input type='hidden' name='sub_cat' id='sub_cat' value='{$cat[id_sub_category]}' />" .
					"<a onclick='javascript:{$form_id}.submit();' href='#'>" . $cat['sub_display_name'] ."</a>" .
					"</form>";
			
			echo $form;		
		}
	?>
	
	
	<div class="job_box">
		
		<?php
		
			foreach($job_array as $job) {
				$bid_image_arg=array('id_bid_request'=>$job['id_bid_request']);
				$images=current($bid_request->getBidImages($bid_image_arg));
				
				$display=
					"<div class='job_display'>
						<div class='sub_cat'>{$job[sub_display_name]}</div>
						<div class='clear'></div>
						<div class='job_place'>{$job[city]} , {$job[state]} {$job[zipcode]}</div>
						<div class='job_list'>
							<div class='prev' name='quick-preview'>
								<a href=/bid-details/{$job[id_bid_request]}><img src={$images[job_image_1]} width='110' height='80' /> View Details</a>
								<div class='prod_img' name='quick-preview'>
									<input type='hidden' name='id_bid_request' id='id_bid_request' value={$job[id_bid_request]} />
								</div>
							
							</div>
						</div>
				</div>";
				
				echo $display;
			}
			
		?>
		
		
		
		
		<?php/*
		
			foreach($job_array as $job) {
				//pr ($job);
				
				$bid_image_arg=array('id_bid_request'=>$job['id_bid_request']);
				$images=current($bid_request->getBidImages($bid_image_arg));
				
				$display=
					"<div class='job_display'>
						<div class='sub_cat'>{$job[sub_display_name]}</div>
						<div class='clear'></div>
						<div class='job_place'>{$job[city]} , {$job[state]} {$job[zipcode]}</div>
						<div class='job_list'>
							<div class='prev' name='quick-preview'>
								<a href=#><img src={$images[thumbnails]} /> View Details</a>
								<div class='prod_img' name='quick-preview'>
									<input type='hidden' name='id_bid_request' id='id_bid_request' value={$job[id_bid_request]} />
								</div>
							
							</div>
						</div>
				</div>";
				
				echo $display;
			}
			*/
		?>
		
		
		<div id="quick_preview" class="quick-prev-wrap" style="display: none;">
				
				<div id="message"></div>
				
				<div class="img-preview">
					<img id="img_1" />
					<img id="img_2" />
					<img id="img_3" />
					<img id="img_4" />
				</div>
				
				<div class="desc_div" id="description" ></div>
				
				<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<input type="hidden" name="bid_id" id="bid_id" value="" />
					<div class="bid_area">
						<input type="text" name="bid_amount" id="bid_amount" value="" />
						<input type="submit" value="submit" />
					</div>
				</form>
				
		</div>
		
	</div>
</body>
</html>