<?
session_start();
require_once ("../classes/autoloader.php"); 
loged_in();

	try {
	

		if($_SESSION['user_type'] == 'Home Owner') {
			//show the requests they have and how much each person has bid
			$bid_request = new bid_request();
			$args = array('a.id_user'=>$_SESSION['id_user']);

			//$homeowner_history = $bid_request->getBids($args);
			$homeowner_history = $bid_request->getBidsInfo($args);

		} else if ($_SESSION['user_type'] == 'Contractor') {
			//show the contractor status and so on

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
	<link rel="stylesheet" href="/css/login.css" type="text/css" media="screen">
	<script src="/js/login.js" type="text/javascript"></script>
	
</head>
<body id="page1">
	
	
	<style type="text/css">
        table {
            clear: both;
            float: left;
            margin: 20px 5px;
            max-width: 800px;
        }
        table tr th {
            background: none repeat scroll 0 0 #E0EBEF;
            border-bottom: 1px solid #F1F1F1;
            color: #000000;
            padding: 8px;
        }
        
        table tr td {
            border-bottom: 1px solid #F1F1F1;
            color: #000000;
            padding: 8px;
        }

    </style>

	
	
	
	
	<?php include ("../template/header.php"); ?>
	
	<div class="error_alert"><?php echo $error_message; ?></div>
	<div class="clear"></div>
	
	<div class="error_box" id="error_box" style="display: block; display:none;"></div>
	<div class="clear"></div>
	
	<?php if(!empty($homeowner_history)): ?>
	
        <div class="request_history">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th><?php echo 'Category';?></th>
                    <th><?php echo 'Sub Category';?></th>
                    <th><?php echo 'City';?></th>
                    <th><?php echo 'Date';?></th>
                    <th><?php echo 'Username';?></th>
                    <th class="actions"></th>
                </tr>

                <?php
                foreach ($homeowner_history as $history):
                ?>

                    <tr class="internal">
                        
                        <td><?php echo $history['main_cat'] ?></td>
                        <td><?php echo $history['sub_cat'] ?></td>
                        <td><?php echo $history['city'] ?></td>
                        <td><?php echo date('m/d/Y', strtotime($history['cdate'])); ?></td>
                        <td><a href="/bid-details/<?php echo $history[id_bid_request]; ?>">View Details</a></td>
                        <td></td>
                        <td class="actions">
                    
                        </td>
            
                    </tr>


                <?php endforeach; ?>

            </table>
        
        </div>
        
    <?php endif;?>
    
</body>
</html>