<?php
	require_once ("../classes/autoloader.php"); 
	try {
	$id_bid = $_REQUEST['id_bid'];
	
	$bid_request = new bid_request();
	$bid_info = current($bid_request->getBidInfoWithImage($id_bid));
	
	if (empty($bid_info)) {
		echo 'error';
	} else {
		echo json_encode($bid_info);
	}
	
	
	} catch(Exception $e) {
		echo "error";
	}
	
?>