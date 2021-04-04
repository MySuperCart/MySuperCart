<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	// AD CREATION 
	
	try {

		requireFields(['Street1', 'City', 'ZipCode']);

        // STEP 2: Inserting 
        $stmt = $mysqli->prepare("INSERT INTO 
        	`Address` 
        	(`Street1`, `City`, `ZipCode`) 
        	VALUES (?, ?, ?)");
		
		$stmt->bind_param('sss',
			$_PHP_INPUT['Street1'],
			$_PHP_INPUT['City'],
			$_PHP_INPUT['ZipCode']
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