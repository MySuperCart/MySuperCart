<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	// AD CREATION 
	
	try {

		requireFields(['StoreID', 'ChainID', 'FileName', 'ItemNameFieldName', 'ItemFieldName']);

		// $newItems = $_PHP_INPUT['items'];
		$StoreID = $_PHP_INPUT['StoreID'];
		$ChainID = $_PHP_INPUT['ChainID'];
		$FileName = basename($_PHP_INPUT['FileName']);
		$FilePath = $_PHP_INPUT['FileName'];
		if(strpos($FilePath, "./temp_downloads/") === 0) {
			$FileName = realpath("/var/www/html/xml-importer/".substr($FileName, 2));
		}
		$ItemNameFieldName = $_PHP_INPUT['ItemNameFieldName'];
		$ItemFieldName = $_PHP_INPUT['ItemFieldName'];

		// Insert ETLLoad
		$stmt = $mysqli->prepare("INSERT INTO 
			`ETLLoad` 
			(`ChainID`, `FileName`) 
			VALUES (?, ?)");

		$stmt->bind_param('is',
			$ChainID,
			$FileName
		);
		$stmt->execute() or die($mysqli->error);

		$ETLLoadID = $stmt->insert_id;

		// Find RefStoreID
		$RefStoreID = $mysqli->query("SELECT `RefStoreID` 
			FROM `RefStore` 
			WHERE `ChainID`='$ChainID' AND `StoreID`='$StoreID' 
			LIMIT 1")->fetch_object()->RefStoreID;

		if(!$RefStoreID) {
			// Insert RefStore
			$stmt = $mysqli->prepare("INSERT INTO 
				`RefStore` 
				(`ChainID`, `StoreID`) 
				VALUES (?, ?)");

			$stmt->bind_param('ii',
				$ChainID,
				$StoreID
			);
			$stmt->execute() or die($mysqli->error);
			$RefStoreID = $stmt->insert_id;
		}

		// Insert Items
		$sqlLoadFile = "LOAD DATA LOCAL INFILE '$FilePath'
	    INTO TABLE Items
		CHARACTER SET binary
		LINES STARTING BY '<".$ItemFieldName.">' TERMINATED BY '</".$ItemFieldName.">'
		(@item)
		SET
		RefStoreID = '".$RefStoreID."',
		PriceUpdateDate = ExtractValue(@item, 'PRICEUPDATEDATE'),
		ItemCode = ExtractValue(@item, 'ITEMCODE'), 
		ItemPrice = ExtractValue(@item, 'ITEMPRICE'), 
		ItemName = ExtractValue(@item, '".$ItemNameFieldName."'), 
		ManufacturerName = ExtractValue(@item, 'MANUFACTURERNAME'), 
		ManufactureCountry = ExtractValue(@item, 'MANUFACTURECOUNTRY'), 
		ManufacturerItemDescription = ExtractValue(@item, 'MANUFACTURERITEMDESCRIPTION'), 
		UnitQty = ExtractValue(@item, 'UNITQTY'), 
		Quantity = ExtractValue(@item, 'QUANTITY'), 
		UnitOfMeasure = ExtractValue(@item, 'UNITOFMEASURE');";

		if($mysqli->query($sqlLoadFile) === FALSE){
			throw new Exception($mysqli->error);
		}

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