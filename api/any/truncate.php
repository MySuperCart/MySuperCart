<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	try {

		// Missing required field
		requireFields(['tableName','offset']);

		// Truncate
		$query = "TRUNCATE TABLE ".$_PHP_INPUT['tableName'];

		// Binding params
		$stmt = $mysqli->prepare($query);

		$stmt->execute() or die($mysqli->error);
		$stmt->close();

		echo json_encode(array('error' => false), JSON_PRETTY_PRINT); 

	} catch (Exception $e) {
		echo json_encode(array('error' => true, 'message' => $e->getMessage()), JSON_PRETTY_PRINT); 
	}

	
	// FINISHING
	$mysqli->close();

?>