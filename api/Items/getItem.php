<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	// GET LISTING
	
	try {
		// No fields found
		if(!$_PHP_INPUT)
			throw new Exception("no_fields_sent");

		// Missing required field
		$_PHP_INPUT['barcode'] 	= isset($_PHP_INPUT['barcode'])	? intval($_PHP_INPUT['barcode']) : '0';
		$_PHP_INPUT['city'] 	= isset($_PHP_INPUT['city']) 	? strval($_PHP_INPUT['city'])	 : 'ירושלים';

		// STEP 1: Select Items details
		$itemQuery = "SELECT `i`.`itemid`, `i`.`priceupdatedate`, `i`.`itemname`,`i`.`itemprice`, `c`.`chainname`, `s`.`storename`, `a`.`city`
			FROM `items` as `i`
			inner join `refstore` as `s` on `i`.`refstoreid` = `s`.`refstoreid`
			inner join `address` as `a` on `s`.`StoreAddressID` = `a`.`addressid`
			inner join `refchain` as `c` on `c`.`chainid` = `s`.`chainid`
			WHERE `ItemCode` = ?
			AND `city` = ?
			ORDER BY `itemprice` ASC
			LIMIT 50";

		// Binding params
		$stmt = $mysqli->prepare($itemQuery);
		$stmt->bind_param('ss',
			$_PHP_INPUT['barcode'],
			$_PHP_INPUT['city']
		);

		$stmt->execute() or die($mysqli->error);

        // STEP 2: Formatting
		$items = array();

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
	        $row['minPrice'] = $minPrice;
	        $row['maxPrice'] = $maxPrice;
        	$items[] = $row;
        }

		$stmt->close();

		echo json_encode(array('error' => false, 'items' => $items), JSON_PRETTY_PRINT); 

	} catch (Exception $e) {
		echo json_encode(array('error' => true, 'message' => $e->getMessage()), JSON_PRETTY_PRINT); 
	}

	
	// FINISHING
	$mysqli->close();

?>