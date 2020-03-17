<?php
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	$remoteImage = "https://image.maps.ls.hereapi.com/mia/1.6/stat?apiKey=$_HERE_MAPS_API_KEY&z=13.5&ofc=880000FF&o0=31.8,35.2;10&tx0=31.8,35.2;".urlencode("ים סוף 17");

	$imginfo = getimagesize($remoteImage);
	header("Content-type: {$imginfo['mime']}");
	readfile($remoteImage);

	$mysqli->close();

?>