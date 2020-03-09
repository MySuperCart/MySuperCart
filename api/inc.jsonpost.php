<?php 
	if(empty($_POST)) {
	    $_PHP_INPUT = file_get_contents('php://input');
	    $_PHP_INPUT = json_decode($_PHP_INPUT, true);
	} else {
	    $_PHP_INPUT = $_POST;
	}


	$_HTTP_AUTHORIZATION = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : false;
	if($_HTTP_AUTHORIZATION) {
		$base64 = explode(' ', $_HTTP_AUTHORIZATION);
		$decoded = base64_decode($base64[1]);
		$decoded_parts = explode(':', $decoded);
		$_AUTH_USERID = $decoded_parts[0];
		$_AUTH_SESSION = $decoded_parts[1];
	} else {
		$_AUTH_USERID = false;
		$_AUTH_SESSION = false;
	}


	$_HTTP_ACCEPT_LANGUAGE = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : false;
	$_HTTP_LANG = isset($_SERVER['HTTP_LANG']) ? $_SERVER['HTTP_LANG'] : false;
	if($_HTTP_ACCEPT_LANGUAGE) {
		$_HTTP_ACCEPT_LANGUAGE = explode(';', $_HTTP_ACCEPT_LANGUAGE)[0]; // First lang in the list
		$_LANG = explode(',', $_HTTP_ACCEPT_LANGUAGE)[0]; // First lang in the list
		$_LANG = explode('-', $_LANG)[0];
	} else if($_HTTP_LANG){
		$_LANG = $_HTTP_LANG;
	} else {
		$_LANG = false;
	}
?>