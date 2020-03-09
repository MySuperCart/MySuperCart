<?php
/**
*
*/
class FoodImporter
{
	private $API_DOMAIN = "http://ec2-35-180-25-13.eu-west-3.compute.amazonaws.com/api";
	private $SOCKET_API = "http://ec2-35-180-25-13.eu-west-3.compute.amazonaws.com:9001/";

	// Initialize a file URL to the variable
	private $URLS = array(
		'kingstore' => array(
			'page' => "https://www.kingstore.co.il/Food_Law/Main.aspx"
		),
		'maayan2000' => array(
			'page' => "http://maayan2000.binaprojects.com/Main.aspx"
		),
		'victory_mahsane_hashuk' => array(
			'page' => "http://matrixcatalog.co.il/NBCompetitionRegulations.aspx"
		),
		'zolvebegadol' => array(
			'page' => "http://zolvebegadol.binaprojects.com/Main.aspx"
		),
		'ybitan' => array(
			'page' => "http://publishprice.ybitan.co.il/"
		),
		'mega' => array(
			'page' => "http://publishprice.mega.co.il/"
		),
		'superpharm' => array(
			'page' => "http://prices.super-pharm.co.il/"
		),
		'superbareket' => array(
			'page' => "http://prices.super-bareket.co.il/"
		),
		'shukhayir' => array(
			'page' => "http://shuk-hayir.binaprojects.com/Main.aspx"
		),
		'shefabirkathashem' => array(
			'page' => "http://shefabirkathashem.binaprojects.com/Main.aspx"
		),
		'shufersal' => array(
			'Stores' => 'http://prices.shufersal.co.il/FileObject/UpdateCategory?catID=5&storeId=0',
			'PriceFull' => "http://prices.shufersal.co.il/FileObject/UpdateCategory?catID=2&storeId=0&asort=Time&sortdir=DESC&sort=Time"
		),
		'ramilevi' => array(
			'ftp_host' => 'url.retail.publishedprices.co.il',
			'username' => 'RamiLevi'
		),
		'doralon' => array(
			'ftp_host' => 'url.retail.publishedprices.co.il',
			'username' => 'doralon'
		),
		'tivtaam' => array(
			'ftp_host' => 'url.retail.publishedprices.co.il',
			'username' => 'TivTaam'
		),
		'hazihinam' => array(
			'ftp_host' => 'url.retail.publishedprices.co.il',
			'username' => 'HaziHinam'
		),
		'yohananof' => array(
			'ftp_host' => 'url.retail.publishedprices.co.il',
			'username' => 'yohananof'
		),
		'osherad' => array(
			'ftp_host' => 'url.retail.publishedprices.co.il',
			'username' => 'osherad'
		),
		'retalix' => array(
			'ftp_host' => 'url.retail.publishedprices.co.il',
			'username' => 'Retalix',
			'password' => '12345'
		),
		'superdosh' => array(
			'ftp_host' => 'url.retail.publishedprices.co.il',
			'username' => 'SuperDosh'
		),
		'stopmarket' => array(
			'ftp_host' => 'url.retail.publishedprices.co.il',
			'username' => 'Stop_Market'
		),
		'freshmarket' => array(
			'ftp_host' => 'url.retail.publishedprices.co.il',
			'username' => 'freshmarket'
		),
		'keshet' => array(
			'ftp_host' => 'url.retail.publishedprices.co.il',
			'username' => 'Keshet'
		)
	);

	private $DIR_DOWNLOAD = "./temp_downloads/";

	function __construct()
	{
		$this->emit([
		    'name' => 'Goku',
		    'age' => '23',
		    'address' => 'Sudbury, On, Canada',
		    'greetings' => 'FoodImporter ready...'
		]);
	}

	function emit($dataArrayMsg) {
		$result = $this->httpPost($this->SOCKET_API, $dataArrayMsg, true, false);
		if(!$result['error'])
		{
		    var_dump($result['message']);
		}
		return $result['error'];
	}

	function importHtmlPublicPage($chainName, $sectionName) {
		echo "importHtmlPublicPage: $chainName / $sectionName \r\n";

		// Use basename() function to return the base name of file
		$fileName = $this->DIR_DOWNLOAD . $chainName .".html";
		$url = $this->URLS[$chainName][$sectionName];

		// Use file_get_contents() function to get the file
		// from url and use file_put_contents() function to
		// save the file by using base name
		if(file_put_contents( $fileName,file_get_contents($url))) {
		    echo "File downloaded successfully: $fileName\r\n";
		}
		else {
		    echo "File downloading failed: $fileName.\r\n";
		}
		return $fileName;
	}

	function downloadCerberusFiles($chainName) {
		echo "downloadCerberusFiles: $chainName \r\n";

		// Mise en place d'une connexion basique
		$conn_id = ftp_ssl_connect($this->URLS[$chainName]['ftp_host']);

		echo "Ftp connected. \r\n";

		// Identification avec un nom d'utilisateur et un mot de passe
		$ftp_username = $this->URLS[$chainName]['username'];
		$ftp_password = isset($this->URLS[$chainName]['password']) ? $this->URLS[$chainName]['password'] : '';

		$login_result = ftp_login($conn_id, $ftp_username, $ftp_password);
		ftp_pasv($conn_id, TRUE);

		echo "Ftp Login successful. \r\n";

		// Récupération du contenu d'un dossier
		$contents = ftp_nlist($conn_id, ".");

	    echo count($contents)." files should be downloaded \r\n";

		foreach ($contents as $fileName) {
			// Should not download useless files
			if(
				strpos($fileName, "PriceFull") !== 0 &&
				strpos($fileName, "Stores") !== 0 &&
				strpos($fileName, "Promo") !== 0
			) continue;

			// local & server file path
			$localFilePath  = $this->DIR_DOWNLOAD . $fileName;
			$remoteFilePath = '/' . $fileName;

			// try to download a file from server
			if(ftp_get($conn_id, $localFilePath, $remoteFilePath, FTP_BINARY)){
			    echo "Downloaded - $localFilePath \r\n";
			}else{
			    echo "Error - $localFilePath \r\n";
			}
		}

		// // close the connection
		ftp_close($conn_id);
	}

	function strLenOrPos($str, $needle) {
		$pos = strpos($str, $needle);
		if(strpos($str, $needle) !== FALSE)
			return $pos;
		else
			return strlen($str);
	}
	function formatFileName($url) {
		$url = basename($url);
		$url = substr($url, 0, $this->strLenOrPos($url, '?') );
		$url = substr($url, 0, $this->strLenOrPos($url, '!') );
		$url = substr($url, 0, $this->strLenOrPos($url, '#') );
		return $this->DIR_DOWNLOAD . $url;
	}

	function downloadFilesFromURLSArray($arr) {
	    echo "download ".count($arr)." Files (From URLS Array)\r\n";
		$downloadedFiles = array();

		foreach ($arr as $url) {

			$fileName = $this->formatFileName($url);

			if(file_put_contents($fileName, file_get_contents($url))) {
			    echo "Downloaded: $fileName\r\n";
			    $downloadedFiles[] = $fileName;
			}
			else {
			    echo "Failed downloading: $fileName\r\n";
			}
		}
		return $downloadedFiles;
	}

	function pendingFiles($sectionName) {
		echo "Searching pending files of $sectionName \r\n";

		$files = scandir($this->DIR_DOWNLOAD);
		$pendingFiles = array();
		foreach ($files as $fileName) {
			if(strpos($fileName, $sectionName) !== FALSE)
				$pendingFiles[] = $this->DIR_DOWNLOAD . $fileName;
		}

		echo "Found ".count($pendingFiles)." files \r\n";
		return $pendingFiles;
	}


	function cleanXmlFile($fileName) {
		echo "cleanXmlFile $fileName \r\n";

		$xml = file_get_contents($fileName);
		$realStart = strpos($xml, '<root>');
		if($realStart === FALSE) {
			$realStart = strpos($xml,'<asx:values>');
			if($realStart !== FALSE) {

			  $xml = preg_replace('/asx:values/', 'root', $xml);
			  $xml = preg_replace('/<\/asx:abap>/', '', $xml);
			}
		}
		$xml = substr($xml, $realStart, strlen($xml));
		file_put_contents($fileName, $xml);
		unset($xml);
		return $fileName;
	}

	function getFilesUrls($url){
		echo "getFilesUrls: $url \r\n";

		$response = file_get_contents($url);
		$hrefIndex = strpos($response, 'href="');
		$urls = array();
		$url;
		while($hrefIndex !== FALSE) {
			$hrefIndex = $hrefIndex + 6;
			$response = substr($response, $hrefIndex, strlen($response) - $hrefIndex);
			$url = substr($response, 0, strpos($response, '"'));
			$url = preg_replace('/&amp;/', '&', $url);
			if(strpos($url, "http") !== FALSE)
			{
				$urls[] = trim($url);
			}
			$hrefIndex = strpos($response, 'href="');
		}
		return $urls;
	}


	function runGzExtractionOnFilesArray($arr) {
		echo "runGzExtractionOnFilesArray...\r\n";
		$extractedFileNames = array();

		foreach ($arr as $fileName) {
			if(strpos($fileName, 'gz') !== FALSE) {
				$xmlFileName = $this->extractGZFile($fileName);
				$extractedFileNames[] = $xmlFileName;

				// Beautify the final XML file
				// Warning: this can cause a heavier load of file_get_contents
				// $this->XmlBeautify($xmlFileName);
				$this->cleanXmlFile($xmlFileName);

			} else {
				// $this->XmlBeautify($fileName);
				$this->cleanXmlFile($fileName);
			}
		}

		echo count($extractedFileNames)." files extracted \r\n";
		return $extractedFileNames;
	}

	private function extractGZFile($fileName) {
		echo "extractGZFile: $fileName \r\n";

		// Raising this value may increase performance
		$buffer_size = 16384; // read 4kb at a time
		$out_fileName = str_replace('.gz', '.xml', $fileName);

		// Open our files (in binary mode)
		$file = gzopen($fileName, 'rb');
		$out_file = fopen($out_fileName, 'wb');

		// Keep repeating until the end of the input file
		while (!gzeof($file)) {
		    // Read buffer-size bytes
		    // Both fwrite and gzread and binary-safe
		    fwrite($out_file, gzread($file, $buffer_size));
		}

		// Files are done, close files
		fclose($out_file);
		gzclose($file);
		unlink($fileName);

		return $out_fileName;
	}

	function insertChain($ChainID) {
	  $Chain = $this->httpPost(
	  	"/RefChain/insertRefChain.php", 
	  	array("ChainCode" => $ChainID)
	  );
	  return $Chain['ChainID'];
	}

	function xmlStringToArray($myXMLData) {
		$xml = simplexml_load_string($myXMLData);
		if ($xml === false) {
		    echo "Failed loading XML: \r\n";
		    foreach(libxml_get_errors() as $error) {
		        echo "\r\n". $error->message;
		    }
		} else {
		    return $xml;
		}
	}

	function httpPost($route, $data, $returnJson=true, $toApi=true) {

		$url = $toApi ? $this->API_DOMAIN . $route : $route;

		// use key 'http' even if you send the request to https://...
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === FALSE) { /* Handle error */ }

		if($returnJson)
			return json_decode($result,true);
		else
			return $result;
	}

	function saveStrAsFile($str, $fileName) {
		if(file_put_contents($fileName, $str))
			echo "$fileName saved \r\n";
		else
			echo "Cannot save $fileName \r\n";
		return $fileName;
	}

	function readHtmlFile($fileName) {
		return file_get_contents($fileName);
	}

	function XmlBeautify($fileName){

		echo "XmlBeautify($fileName) \r\n";

		$xml = file_get_contents($fileName);

		// add marker linefeeds to aid the pretty-tokeniser (adds a linefeed between all tag-end boundaries)
		$xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);

		// now indent the tags
		$token      = strtok($xml, "\n");
		$result     = ''; // holds formatted version as it is built
		$pad        = 0; // initial indent
		$matches    = array(); // returns from preg_matches()

		// scan each line and adjust indent based on opening/closing tags
		while ($token !== false) {

			// test for the various tag states

			// 1. open and closing tags on same line - no change
			if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) :
			  $indent=0;
			// 2. closing tag - outdent now
			elseif (preg_match('/^<\/\w/', $token, $matches)) :
			  $pad--;
			// 3. opening tag - don't pad this one, only subsequent tags
			elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
			  $indent=1;
			// 4. no indentation needed
			else :
			  $indent = 0;
			endif;

			// pad the line with the required number of leading spaces
			$line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
			$result .= $line . "\n"; // add to the cumulative result, with linefeed
			$token   = strtok("\n"); // get the next token
			$pad    += $indent; // update the pad size for subsequent lines
		}

		file_put_contents($fileName, $result);
		return $result;
	}

	function removeDuplicatesOlderFiles() {
		echo "removeDuplicatesOlderFiles... \r\n";

		$files = scandir($this->DIR_DOWNLOAD);
		$uniqueFiles = array();
		foreach ($files as $fileName) {
			preg_match('/([A-Za-z0-9\-]*)-([0-9]*)(.xml)/', $fileName, $matches, PREG_OFFSET_CAPTURE);
			$fileKey = $matches[1][0];
			$fileDate = $matches[2][0];
			$fileExt = $matches[3][0];

			// index the fileKey
			if(!isset($uniqueFiles[$fileKey])) {
				echo "indexing $fileKey \r\n";
				$uniqueFiles[$fileKey] = $fileDate;
			}
			// compare
			else if($uniqueFiles[$fileKey] < $fileDate) {
				// previous file saved is older, delete it
				$previousFileName = $this->DIR_DOWNLOAD.$fileKey.'-'.$uniqueFiles[$fileKey].$fileExt;
				echo "delete $previousFileName\r\n";
				unlink($previousFileName);
				$uniqueFiles[$fileKey] = $fileDate;
				echo "keep ".$uniqueFiles[$fileKey]."\r\n";
			}
			else if($uniqueFiles[$fileKey] > $fileDate) {
				// the current fileName is older, delete it
				echo "delete ".$this->DIR_DOWNLOAD.$fileName."\r\n";
				unlink($this->DIR_DOWNLOAD.$fileName);
				echo "keep ".$uniqueFiles[$fileKey]."\r\n";
			}
		}
	}

	// Loads the stores file list, identify the ChainCode, and runs on the stores list to save each store with its name and the ChainID
	function parseXMLStores($storeXmlFileName) {
		echo "parseXMLStores $storeXmlFileName \r\n";

		$XMLfileContent = file_get_contents($storeXmlFileName);
		$xml = $this->xmlStringToArray($XMLfileContent);

	    $ChainID = $xml->CHAINID;
	    echo "ChainID found: $ChainID \r\n";

	    $Stores = $xml->STORES;
	    $newStores = array();

		foreach($Stores->children() as $Store) {
			$newStores[] = array(
				"Street1"		=> (string)$Store->ADDRESS,
				"City"			=> (string)$Store->CITY,
				"ZipCode"		=> (string)$Store->ZIPCODE,
				"StoreID"		=> (string)$Store->STOREID,
				"StoreName"		=> (string)$Store->STORENAME,
				"ChainID"		=> (string)$ChainID,
				"StorePhoneID"	=> 0
			);
	    }

	    echo "Should insert ".count($newStores)." stores \r\n";

	   	for ($i=0; $i < ceil(count($newStores)/50)*50; $i+=50) {

	   		$payload = array("stores"=> array_slice($newStores, $i, 50));

		    $insertStore = $this->httpPost(
		    	"/RefStore/insertRefStore.php",
		    	$payload
		    );

		    if($insertStore['error']){
		    	$shouldKeepFile = true;
		    	echo $insertStore['message']."\r\n";
		    }
		    else {
		    	echo "Updated ".count($payload['stores'])." stores of ChainID $ChainID\r\n";
		    }
	   	}

	    if(!isset($shouldKeepFile)) {
	    	unlink($priceFullXmlFileName);
	    }

	    return;
	}


	function parseXMLPriceFull($priceFullXmlFileName) {

		echo "parseXMLPriceFull $priceFullXmlFileName \r\n";

		$XMLfileContent = file_get_contents($priceFullXmlFileName);
		$xml = $this->xmlStringToArray($XMLfileContent);


		// Preparing data for ETLLoad
	    $ChainID 	= 	$xml->ChainId;
		$SubChainId = 	$xml->SubChainId;
		$StoreId 	= 	$xml->StoreId;

	    echo "ChainID $ChainID / SubChainId $SubChainId / StoreID $StoreId\r\n";

		// Preparing data for Items
		$newItems = array();
		foreach($xml->Items->children() as $Item) {

			$newItems[] = array(
				"PriceUpdateDate" 				=> (string) $Item->PriceUpdateDate,
				"ItemCode" 						=> (string) $Item->ItemCode,
				"ItemPrice" 					=> (string) $Item->ItemPrice,
				"ItemName" 						=> (string) $Item->ItemName,
				"ManufacturerName" 				=> (string) $Item->ManufacturerName,
				"ManufactureCountry" 			=> (string) $Item->ManufactureCountry,
				"ManufacturerItemDescription" 	=> (string) $Item->ManufacturerItemDescription,
				"UnitQty" 						=> (string) $Item->UnitQty,
				"Quantity" 						=> (string) $Item->Quantity,
				"UnitOfMeasure" 				=> (string) $Item->UnitOfMeasure,
				"CurrencyCode" 					=> (string) 'nis',
				"SourceTypeID" 					=> (string) 1,
				"ItemImageID" 					=> (string) 0
			);
		}

		echo "Should insert ".count($newItems)." items \r\n";

	   	for ($i=0; $i < ceil(count($newItems)/50)*50; $i+=50) {

	   		$payload = array(
	   			"items"=> array_slice($newItems, $i, 50),
				"ChainID" => (string)$ChainID,
				"StoreID" => (string)$StoreId,
				"FileName" => (string)basename($priceFullXmlFileName)
	   		);

		    $insertItems = $this->httpPost(
		    	"/Items/insertItems.php",
		    	$payload
		    );

		    if($insertItems['error']) {
		    	$shouldKeepFile = true;
		    	echo $insertItems['message']."\r\n";
		    }
		    else {
		    	echo "Updated ".count($payload['items'])." items of ChainID $ChainID\r\n";
		    }
	   	}

	    if(!isset($shouldKeepFile)) {
	    	unlink($priceFullXmlFileName);
	    }

	    return;
	}

}
