<?
session_start();
require_once ("../classes/autoloader.php"); 

if (!isset($_SESSION['id_user'])) {
	header( 'Location: index.php' );
}

$categories=new category;

try {
	
	//add main category
	if($_POST['cmd']=='add_main_category') {
		$category_name=trim($_POST['category_name']);
		$display_name=trim($_POST['display_name']);
		
		if(empty($category_name) || empty($display_name)) {
			throw new Exception("Category name and display name cannot be empty.");
		}
		
		$args = array('category_name'=>$category_name, 'display_name'=>$display_name);
		$add_cat = $categories->add_category($args);
		
		if($add_cat) {
			$error_message = 
				"<div class='complete_icon'></div>
					<div class='error_ok'>
						Category added successfully.
					</div>
					<div class='clear'>
				</div>";
		}

	}
	
	//add sub category
	if($_POST['cmd']=='add_sub_category') {
		$sub_category_name=trim($_POST['subcategory_name']);
		$sub_display_name=trim($_POST['sub_display_name']);
		
		if(empty($sub_category_name) || empty($sub_display_name)) {
			throw new Exception("Category name and display name cannot be empty.");
		}
		
		$sub_args = array('subcategory_name'=>$sub_category_name, 'sub_display_name'=>$sub_display_name);
		$add_sub_cat = $categories->add_SubCategory($sub_args);
		
		$error_message = 
			"<div class='complete_icon'></div>
				<div class='error_ok'>
					Sub Category added successfully.
				</div>
				<div class='clear'>
			</div>";
	
	}
	
	if($_POST['cmd']=='delete_main_category') {
		$category_id=$_POST['specialty'];
		
		if(empty($category_id)) {
			throw new Exception("Please choose a category to delete.");
		}
		
		$args=array('id_main_category'=>$category_id);
		
		$delete = $categories->delete_category($args);
	
		$error_message = 
			"<div class='complete_icon'></div>
				<div class='error_ok'>
					Category deleted successfully.
				</div>
				<div class='clear'>
			</div>";
		
	}
	
	
	if($_POST['cmd']=='delete_subcategory') {
		$sub_category_id=$_POST['sub_specialty'];
		
		if(empty($sub_category_id)) {
			throw new Exception("Please choose a sub category to delete.");
		}
		
		$args=array('id_sub_category'=>$sub_category_id);
		
		$delete = $categories->delete_subCategory($args);
	
		$error_message = 
			"<div class='complete_icon'></div>
				<div class='error_ok'>
					Sub Category deleted successfully.
				</div>
				<div class='clear'>
			</div>";
		
		
	}
	
	if($_POST['cmd']=='associate_categories') {
		$sub_category_ids = $_POST['sub_cat_id'];
		$main_category_id = $_POST['main_cats'];
		
		if(empty($main_category_id)) {
			throw new Exception("Please choose a category.");
		}
		
		if(empty($sub_category_ids)) {
			throw new Exception("Please choose a sub category.");
		}
		
		foreach ($sub_category_ids as $key=>$value) {
			$args = array ('id_main_category'=>$main_category_id, 'id_sub_category'=>$value);
			$categories->add_association($args);
		}
		
		$error_message = 
			"<div class='complete_icon'></div>
				<div class='error_ok'>
					Categories associated successfully.
				</div>
				<div class='clear'>
			</div>";
	}
	
} catch(Exception $e) {
	$error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
}

try {

	$main_categories = $categories->getCategories();
	foreach ($main_categories as $cat) {
		$cats .= "<option value=\"{$cat['id_main_category']}\">{$cat['display_name']}</option>\n";
	}

	$sub_categories=$categories->getSubCategories();

	foreach ($sub_categories as $sub_cat) {
		$sub_cats .= "<option value=\"{$sub_cat['id_sub_category']}\">{$sub_cat['sub_display_name']}</option>\n";
		$sub_cats_box .=" <input type='checkbox' name='sub_cat_id[]' value={$sub_cat['id_sub_category']} /> {$sub_cat['sub_display_name']}";
	}
	
} catch(Exception $e) {
	$error_message = "<div class='error_icon'></div><div class='error'>".$e->getMessage() . "</div><div class='clear'></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin</title>
    <meta charset="utf-8">
	<?php include ("../template/head_header.php"); ?>
	<!--link rel="stylesheet" href="/css/login.css" type="text/css" media="screen"-->
	<script src="/js/admin.js" type="text/javascript"></script>
	
	<style type="text/css">
	.checkboxes {width:98%; margin:15px; padding-right:15px; display:block;}
	</style>
	
</head>
<body id="page1">
	<?php include ("../template/header.php"); ?>
	
	<div class="error_alert"><?php echo $error_message; ?></div>
	<div class="clear"></div>
	
	<div class="error_box" id="error_box" style="display: block; display:none;"></div>
	<div class="clear"></div>
	
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin-top:25px;">
		<input type="hidden" name="cmd" id="cmd" value="delete_main_category" />
		<div class="even">
			<div class="words">Main Categories:</div>    
			<div class="fields" style="width:16%;">
				<select id="specialty" name="specialty">
					<option value="">Categories</option>
						<?php echo $cats; ?>
				</select>
			</div>
			<input type="submit" value="Delete" onclick="return confirm('Delete category?');" />
			<div class="clear"></div>
		</div>
	</form>
	
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin-top:25px;">
		<input type="hidden" name="cmd" id="cmd" value="delete_subcategory" />
		<div class="even">
			<div class="words">Sub Categories:</div>    
			<div class="fields" style="width:16%;">
				<select id="sub_specialty" name="sub_specialty">
					<option value="">Sub Categories</option>
						<?php echo $sub_cats; ?>
				</select>
			</div>
			<input type="submit" value="Delete" onclick="return confirm('Delete sub category?');" />
			<div class="clear"></div>
		</div>
	
	</form>
	
	<div class="clear"></div>
	
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin-top:25px;">
		<input type="hidden" name="cmd" id="cmd" value="add_main_category" />
		
			<div class="title" style="margin-right:91px; text-align:center" onclick="display_div('category');">
				<a href="#">ADD MAIN CATEGORY</a>
			</div>
			
			<div id="category" style="display:none;">
				<div class="even">
					<div class="words">Category: </div>
					<div class="fields"><input type="text" id="category_name" name="category_name"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Display Name: </div>
					<div class="fields"><input type="text" id="display_name" name="display_name"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				
				<div class="fields" style="margin-right:91px; text-align:center">
					<input type="submit" id="button" value="Add Main Category" />
				</div>
				
				<div class="clear"></div>
				
			</div>
	</form>
	
	<div class="clear"></div>
	
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin-top:25px;">
		<input type="hidden" name="cmd" id="cmd" value="add_sub_category" />
		
			<div class="title" style="margin-right:91px; text-align:center" onclick="display_div('sub_category');">
				<a href="#">ADD SUB CATEGORY</a>
			</div>
			
			<div id="sub_category" style="display:none;">
				<div class="even">
					<div class="words">Sub Category: </div>
					<div class="fields"><input type="text" id="subcategory_name" name="subcategory_name"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="even">
					<div class="words">Display Name: </div>
					<div class="fields"><input type="text" id="sub_display_name" name="sub_display_name"  value="" />
						<span class="redstar">*</span>
						<span class="fields_msg" name="message"></span>
					</div>
					<div class="clear"></div>
				</div>
				
				<div class="fields" style="margin-right:91px; text-align:center">
					<input type="submit" id="button" value="Add Sub Category" />
				</div>
					
				<div class='clear'></div>
			</div>
	</form>
	<p>------------------------------------------------------------------------------------------------------------------------------
	------------------------------------------------------------------------------
	</p>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin-top:25px;">
		<input type="hidden" name="cmd" value="associate_categories" />
		<div class="even">
			<div class="words">Main Categories:</div>    
			<div class="fields" style="width:16%;">
				<select id="main_cats" name="main_cats">
					<option value="">Categories</option>
						<?php echo $cats; ?>
				</select>
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="checkboxes">
			<?php echo $sub_cats_box; ?>
		</div>
		
		<input type="submit" value="Associate Categories" />
	</form>
	
</body>
</html>