<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	// AD CREATION 
	
	try {

		requireFields(['ChainID']);

		// Check if ChainID already exists
		$stmt = $mysqli->prepare("SELECT ChainID FROM `FoodTech`.`RefChain` WHERE `ChainID` = ? LIMIT 1");
		
		$stmt->bind_param('s',
			$_PHP_INPUT['ChainID']
		);

		$items = array();
		$stmt->execute() or die($mysqli->error);
		$result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
        	$items[] = $row;
        }

        if(count($items)) {
        	echo json_encode(array('error' => false, 'RefChain' => $row), JSON_PRETTY_PRINT); 
        }

        else {
			// STEP 2: Inserting 
			$stmt = $mysqli->prepare("INSERT INTO 
				`FoodTech`.`RefChain` 
				(`ChainID`) 
				VALUES (?)");

			$stmt->bind_param('s',
				$_PHP_INPUT['ChainID']
			);
			$stmt->execute() or die($mysqli->error);

			// FINISHING
			echo json_encode(array('error' => false, 'ChainID' => $stmt->insert_id), JSON_PRETTY_PRINT); 
        }
        
		$stmt->close();

	} catch (Exception $e) {
		echo json_encode(array('error' => true, 'message' => $e->getMessage()), JSON_PRETTY_PRINT); 
	}

	
	// FINISHING
	$mysqli->close();

?>