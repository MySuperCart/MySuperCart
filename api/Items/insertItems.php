<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	// AD CREATION 
	
	try {

		requireFields(['items', 'StoreID', 'ChainID', 'FileName']);

		$newItems = $_PHP_INPUT['items'];
		$StoreID = $_PHP_INPUT['StoreID'];
		$ChainID = $_PHP_INPUT['ChainID'];
		$FileName = $_PHP_INPUT['FileName'];

		// Insert ETLLoad
		$stmt = $mysqli->prepare("INSERT INTO 
			`FoodTech`.`ETLLoad` 
			(`ChainID`, `FileName`) 
			VALUES (?, ?)");

		$stmt->bind_param('is',
			$ChainID,
			$FileName
		);
		$stmt->execute() or die($mysqli->error);

		$ETLLoadID = $stmt->insert_id;

		// Find RefStoreID
		$RefStoreID = $mysqli->query("SELECT `ID` 
			FROM `FoodTech`.`RefStore` 
			WHERE `ChainID`='$ChainID' AND `StoreID`='$StoreID' 
			LIMIT 1")->fetch_object()->ID;

		// Insert Items
		for ($i=0; $i < count($newItems); $i++) { 
			$item = $newItems[$i];

			// STEP 2: Insert Address

			$stmt = $mysqli->prepare("INSERT INTO 
				`FoodTech`.`Items` 
				(`ItemCode`, 
				`PriceUpdateDate`, 
				`ItemPrice`, 
				`ItemName`, 
				`CurrencyCode`, 
				`SourceTypeID`, 
				`ETLLoadID`, 
				`RefStoreID`, 
				`ItemImageID`, 
				`ManufacturerName`, 
				`ManufactureCountry`, 
				`ManufacturerItemDescription`, 
				`UnitQty`, 
				`Quantity`, 
				`UnitOfMeasure`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?)");

			$stmt->bind_param('sssssiiiissssss',
				$item['ItemCode'], 
                $item['PriceUpdateDate'],
                $item['ItemPrice'],
                $item['ItemName'],
                $item['CurrencyCode'],
                $item['SourceTypeID'],
                $ETLLoadID,
                $RefStoreID,
                $item['ItemImageID'],
                $item['ManufacturerName'],
                $item['ManufactureCountry'],
                $item['ManufacturerItemDescription'],
                $item['UnitQty'],
                $item['Quantity'],
                $item['UnitOfMeasure']
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