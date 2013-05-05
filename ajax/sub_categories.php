<?
	/* Get the sub categories associated with the main category chosen*/
	require_once ("../classes/autoloader.php");
	$id_main_cat = $_POST['id_main_category'];
	
	if (empty($id_main_cat) || $id_main_cat == '0') {
		echo json_encode('Fail');
		exit;
	}

	$category = new category();
	$sub_categories=$category->get_catAssociationDetails($id_main_cat);
	echo json_encode($sub_categories);

?>