<?php 
	include($_SERVER['DOCUMENT_ROOT'] . "/api/inc.php");

	// GET LISTING
	
	try {
		// No fields found
		if(!$_PHP_INPUT)
			throw new Exception("no_fields_sent");

		// Missing required field
		$_PHP_INPUT['barcode'] = isset($_PHP_INPUT['barcode'])	? intval($_PHP_INPUT['barcode']) 		: '0';

		// Try finding lowest and biggest prices
		try {
        	// Find Min Price
        	$minPrice = $mysqli->query("SELECT `ItemPrice` as `minPrice` FROM `FoodTech`.`Items` WHERE `ItemCode` = '".$_PHP_INPUT['barcode']."' ORDER BY `ItemPrice` ASC LIMIT 1")->fetch_object()->minPrice;

        	// Find Max Price
        	$maxPrice = $mysqli->query("SELECT `ItemPrice` as `maxPrice` FROM `FoodTech`.`Items` WHERE `ItemCode` = '".$_PHP_INPUT['barcode']."' ORDER BY `ItemPrice` DESC LIMIT 1")->fetch_object()->maxPrice;

        } catch(Exception $e) {}

		// STEP 1: Select Items details
		$itemQuery = "SELECT * FROM `FoodTech`.`Items` WHERE ItemCode = ? LIMIT 1";

		// Binding params
		$stmt = $mysqli->prepare($itemQuery);
		$stmt->bind_param('s',
			$_PHP_INPUT['barcode']
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