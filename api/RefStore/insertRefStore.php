<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	// AD CREATION 
	
	try {

		requireFields(['stores']);

		$newStores = $_PHP_INPUT['stores'];

		for ($i=0; $i < count($newStores); $i++) { 
			$store = $newStores[$i];

			// STEP 2: Insert Address

			$stmt = $mysqli->prepare("INSERT INTO 
				`FoodTech`.`Address` 
				(`Street1`, `City`, `ZipCode`) 
				VALUES (?, ?, ?)");

			$stmt->bind_param('sss',
				$store['Street1'],
				$store['City'],
				$store['ZipCode']
			);
			$stmt->execute() or die($mysqli->error);

			$store['AddressID'] = $stmt->insert_id;

			// STEP 2: Insert RefStore
			$stmt = $mysqli->prepare("INSERT IGNORE INTO `FoodTech`.`RefStore` (`StoreID`, `ChainID`, `StoreName`, `StoreAddressID`) VALUES (?, ?, ?, ?)");

			$stmt->bind_param('iisi',
				$store['StoreID'],
				$store['ChainID'],
				$store['StoreName'],
				$store['AddressID']
			);
			$stmt->execute() or die($mysqli->error);

		}

        
		// FINISHING
		echo json_encode(array('error' => false), JSON_PRETTY_PRINT); 

		$stmt->close();

	} catch (Exception $e) {
		echo json_encode(array('error' => true, 'message' => $e->getMessage()), JSON_PRETTY_PRINT); 
	}

	
	// FINISHING
	$mysqli->close();

?>