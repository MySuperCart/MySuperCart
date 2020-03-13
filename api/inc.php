<?php
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: text/json;charset=UTF-8');

	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.jsonpost.php");
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.mysqli.php");
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.generalfuncs.php");
?>