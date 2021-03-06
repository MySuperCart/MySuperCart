<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/json;charset=UTF-8');

libxml_use_internal_errors(true);
ini_set("memory_limit","256M");
ini_set('max_execution_time', 3000);
require_once("../classes/FoodImporter.class.php");

//******* MAIN ******//
$fi = new FoodImporter();

// TEST API ENDPOINT
// $fi->httpPost('/Items/getItem.php', array('barcode' => '3605533307939'));

// TEST Files Scrapping Stores files from shufersal webpage to xml ungzipped...
// $storePublicPage = $fi->importHtmlPublicPage('shufersal', 'PriceFull');
// $urls = $fi->getFilesUrls($storePublicPage);
// $downloadedFiles = $fi->downloadFilesFromURLSArray($urls);
// $fi->runGzExtractionOnFilesArray($downloadedFiles);

// TEST Handle pending stores XML Files
// $storesNewXmlFiles = $fi->pendingFiles('Stores');
// foreach ($storesNewXmlFiles as $storeXmlFile) {
// 	$fi->parseXMLStores($storeXmlFile);
// }

// TEST clean gz files
// $priceFullXmlFiles = $fi->pendingFiles('PriceFull');
// $fi->runGzExtractionOnFilesArray($priceFullXmlFiles);

// TEST Handle pending pricefull xml Files
// $NewXmlFiles = $fi->pendingFiles('PriceFull');
// foreach ($NewXmlFiles as $storeXmlFile) {
// 	$fi->parseXMLPriceFull($storeXmlFile);
// }

// TEST download all files of yohananof
// $fi->downloadCerberusFiles('yohananof');
// $gzipXmlFiles = $fi->pendingFiles('.gz');
// $fi->runGzExtractionOnFilesArray($gzipXmlFiles);

// TEST remove duplicate files
// $xmlFiles = $fi->pendingFiles('xml');
// $fi->removeDuplicatesOlderFiles();

if (isset($argc)) {
	$action = $argv[1];
	$arg1 = $argv[2];
	$arg2 = $argv[3];
	$fi->LOG_LEVEL = 0;
} elseif (isset($_GET['action'])){
	$action = $_GET['action'];
	$arg1 = isset($_GET['arg1']) ? $_GET['arg1'] : '';
	$arg2 = isset($_GET['arg2']) ? $_GET['arg2'] : '';
	$fi->LOG_LEVEL = isset($_GET['log_level']) ? $_GET['log_level'] : 0;
}

switch ($action) {
	case 'download':
		$fi->download($arg1, $arg2);
		break;

	case 'parse':
		$fi->LOG_LEVEL = 0;
		$fi->parseXML($arg1);
		break;

	case 'extract':
		$fi->LOG_LEVEL = 0;
		$fi->runGzExtractionOnFilesArray(array($arg1));

	case 'stores':
		$fi->LOG_LEVEL = 0;
		$fi->syncAll('Stores');

	case 'pricefull':
		$fi->LOG_LEVEL = 1;
		$fi->syncAll('PriceFull');

	case 'price':
		$fi->LOG_LEVEL = 1;
		$fi->syncAll('Price');

	case 'insertChain':
		$fi->LOG_LEVEL = 2;
		$fi->insertChain("7290027600007", "Shufersal");

	case 'extractAll':
		$fi->LOG_LEVEL = 2;
		$fi->extractAll();

	default:
		# code...
		break;
}

?>