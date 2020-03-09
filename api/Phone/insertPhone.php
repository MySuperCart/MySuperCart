<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	// AD CREATION 
	
	try {

		requireFields(['PhoneNumber']);

        // STEP 2: Inserting 
        $stmt = $mysqli->prepare("INSERT INTO 
        	`FoodTech`.`Phone` 
        	(`PhoneType`, `PhoneNumber`) 
        	VALUES 
        	(1, ?)");
		
		$stmt->bind_param('s',
			$_PHP_INPUT['PhoneNumber']
		);
		$stmt->execute() or die($mysqli->error);

		// FINISHING
		echo json_encode(array('error' => false, 'ID' => $stmt->insert_id), JSON_PRETTY_PRINT); 
		$stmt->close();

	} catch (Exception $e) {
		echo json_encode(array('error' => true, 'message' => $e->getMessage()), JSON_PRETTY_PRINT); 
	}

	
	// FINISHING
	$mysqli->close();

?>