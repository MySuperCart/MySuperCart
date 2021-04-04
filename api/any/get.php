<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");
	
	try {

		// Missing required field
		// requireFields(['tableName','offset']);

		// STEP 1: Select Ad details
		$query = "SELECT * FROM `".$_REQUEST['tableName']."` LIMIT ?,10";

		// Binding params
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('i',
			$_REQUEST['offset']
		);

		$stmt->execute() or die($mysqli->error);

        // STEP 2: Formatting
		$entries = array();

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
        	$entries[] = $row;
        }
		$stmt->close();

		echo json_encode(array('error' => false, 'entries' => $entries), JSON_PRETTY_PRINT); 

	} catch (Exception $e) {
		echo json_encode(array('error' => true, 'message' => $e->getMessage()), JSON_PRETTY_PRINT); 
	}

	
	// FINISHING
	$mysqli->close();

?>