<?php
libxml_use_internal_errors(true);
ini_set("memory_limit","256M");
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

// TEST download public page of yohananof
// $fi->downloadCerberusFiles('yohananof');
// $gzipXmlFiles = $fi->pendingFiles('.gz');
// $fi->runGzExtractionOnFilesArray($gzipXmlFiles);

// TEST remove duplicate files
// $xmlFiles = $fi->pendingFiles('xml');
// $fi->removeDuplicatesOlderFiles();

if (isset($argc)) {
	for ($i = 0; $i < $argc; $i++) {
		echo "Argument #" . $i . " - " . $argv[$i] . "\n";
	}
}
else {
	echo "argc and argv disabled\n";
}
?>