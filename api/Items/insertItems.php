<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	// AD CREATION 
	
	try {

		requireFields(['StoreID', 'ChainID', 'FileName']);

		// $newItems = $_PHP_INPUT['items'];
		$StoreID = $_PHP_INPUT['StoreID'];
		$ChainID = $_PHP_INPUT['ChainID'];
		$FileName = basename($_PHP_INPUT['FileName']);
		$FilePath = $_PHP_INPUT['FileName'];
		if(strpos($FilePath, "./temp_downloads/") === 0) {
			$FileName = realpath("/var/www/html/xml-importer/".substr($FileName, 2));
		}

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
		$sqlLoadFile = "LOAD DATA LOCAL INFILE '$FilePath'
	    INTO TABLE Items
		CHARACTER SET binary
		LINES STARTING BY '<ITEM>' TERMINATED BY '</ITEM>'
		(@item)
		SET
		PriceUpdateDate = ExtractValue(@item, 'PRICEUPDATEDATE'),
		ItemCode = ExtractValue(@item, 'ITEMCODE'), 
		ItemPrice = ExtractValue(@item, 'ITEMPRICE'), 
		ItemName = IFNULL(ExtractValue(@item, 'ITEMNAME'), ExtractValue(@item, 'ITEMNM')), 
		ManufacturerName = ExtractValue(@item, 'MANUFACTURERNAME'), 
		ManufactureCountry = ExtractValue(@item, 'MANUFACTURECOUNTRY'), 
		ManufacturerItemDescription = ExtractValue(@item, 'MANUFACTURERITEMDESCRIPTION'), 
		UnitQty = ExtractValue(@item, 'UNITQTY'), 
		Quantity = ExtractValue(@item, 'QUANTITY'), 
		UnitOfMeasure = ExtractValue(@item, 'UNITOFMEASURE');";

		if($mysqli->query($sqlLoadFile) === FALSE){
			throw new Exception($mysqli->error);
		}

		// for ($i=0; $i < count($newItems); $i++) { 
		// 	$item = $newItems[$i];

		// 	// STEP 2: Insert Address

		// 	$stmt = $mysqli->prepare("INSERT INTO 
		// 		`FoodTech`.`Items` 
		// 		(`ItemCode`, 
		// 		`PriceUpdateDate`, 
		// 		`ItemPrice`, 
		// 		`ItemName`, 
		// 		`CurrencyCode`, 
		// 		`SourceTypeID`, 
		// 		`ETLLoadID`, 
		// 		`RefStoreID`, 
		// 		`ItemImageID`, 
		// 		`ManufacturerName`, 
		// 		`ManufactureCountry`, 
		// 		`ManufacturerItemDescription`, 
		// 		`UnitQty`, 
		// 		`Quantity`, 
		// 		`UnitOfMeasure`) 
  //               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?)");

		// 	$stmt->bind_param('sssssiiiissssss',
		// 		$item['ItemCode'], 
  //               $item['PriceUpdateDate'],
  //               $item['ItemPrice'],
  //               $item['ItemName'],
  //               $item['CurrencyCode'],
  //               $item['SourceTypeID'],
  //               $ETLLoadID,
  //               $RefStoreID,
  //               $item['ItemImageID'],
  //               $item['ManufacturerName'],
  //               $item['ManufactureCountry'],
  //               $item['ManufacturerItemDescription'],
  //               $item['UnitQty'],
  //               $item['Quantity'],
  //               $item['UnitOfMeasure']
		// 	);
		// 	$stmt->execute() or die($mysqli->error);
		// }



		// FINISHING
		echo json_encode(array(
			'error' => false,
			'affected_rows' => $mysqli->affected_rows
		), JSON_PRETTY_PRINT);

		$stmt->close();

	} catch (Exception $e) {
		echo json_encode(array('error' => true, 'message' => $e->getMessage()), JSON_PRETTY_PRINT); 
	}


	// FINISHING
	$mysqli->close();

?>