<?php
	
	
	//include all files in config directory
	$files = glob($_SERVER['DOCUMENT_ROOT'] . "/config/*.php");
	foreach ($files as $file) {
    	include $file;
	}
	
	//check config/index.php values for displaying errors or not
	if ($config['debug'] == false) {
		error_reporting(0);
	} else {
		error_reporting(-1);
	}

	//always include cabstract file
	set_include_path('/home/yervand/listpalace.com/classes'. PATH_SEPARATOR . get_include_path());
	require_once('cabstract/cabstract.class.php');

	
	//class files located in same directory as autoloader
	function __autoload_HTTP_Client($class_name) {
		$HC = $class_name . '.class.php';
		return require_once($HC);
	}
	
	spl_autoload_register('__autoload_HTTP_Client');
	
	//print_r function for better output
	function pr($a,$b=false) {
	    echo '<pre>'; 
	    print_r($a); 
	    echo "</pre>\n";
	    if($b) die();
    }
	
	define('INTERNAL_ERROR', "A internal error has occured. Please contact support or try again");
	
	
	
	function loged_in () {
		if(!($_SESSION['id_user']) || empty($_SESSION['id_user'])) {
			header("Location: ../../login.php?callback={$_SERVER[REQUEST_URI]}");
		}
	}
	
	
	
?>