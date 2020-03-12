<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	// AD CREATION 
	
	try {

		requireFields(['ChainID', 'ChainName']);

		$_ChainID = strval($_PHP_INPUT['ChainID']);
		$_ChainName = strval($_PHP_INPUT['ChainName']);

		// Check if ChainID already exists
		$query = $mysqli->query("SELECT `ChainID` FROM `FoodTech`.`RefChain` WHERE `ChainID` = '$_ChainID' LIMIT 1");
		if($query->num_rows) {
        	echo json_encode(array('error' => false, 'RefChain' => $query->fetch_object()->ChainID), JSON_PRETTY_PRINT); 
        }

        else {
			// STEP 2: Inserting 
			$stmt = $mysqli->prepare("INSERT INTO 
				`FoodTech`.`RefChain` 
				(`ChainID`,`ChainName`) 
				VALUES (?,?)");

			$stmt->bind_param('ss',
				$_ChainID,
				$_ChainName
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