<?php
/**
*
*/
class FoodImporter
{
	private $API_DOMAIN = "http://ec2-35-180-25-13.eu-west-3.compute.amazonaws.com/api";
	private $SOCKET_API = "http://ec2-35-180-25-13.eu-west-3.compute.amazonaws.com:9001/news";
	// private $SOCKET_API = "http://localhost:9001/news";

	// Initialize a file URL to the variable
	private $URLS = array(
		'kingstore' => array(
			'json' => true,
			'page' => "https://www.kingstore.co.il/Food_Law/Main.aspx",
			'Stores' => "https://www.kingstore.co.il/Food_Law/MainIO_Hok.aspx?WStore=0&WFileType=1",
			'download' => "https://www.kingstore.co.il/Food_Law/Download/"
		),
		'maayan2000' => array(
			'json' => true,
			'page' => "http://maayan2000.binaprojects.com/Main.aspx",
			'Stores' => "http://maayan2000.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=1",
			'download' => "http://maayan2000.binaprojects.com/Download/"
		),
		'victory_mahsane_hashuk' => array(
			'html' => true,
			'page' => "http://matrixcatalog.co.il/NBCompetitionRegulations.aspx",
			'shouldFiltersLinks' => true
		),
		'zolvebegadol' => array(
			'json' => true,
			'page' => "http://zolvebegadol.binaprojects.com/Main.aspx",
			'Stores' => "http://zolvebegadol.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=1",
			'download' => "http://zolvebegadol.binaprojects.com/Download/"
		),
		'ybitan' => array(
			'html' => true,
			'page' => "http://publishprice.ybitan.co.il/",
			'shouldFiltersLinks' => true,
			//////// TODO
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
			'html' => true,
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
		// 'retalix' => array(
		// 	'ftp_host' => 'url.retail.publishedprices.co.il',
		// 	'username' => 'Retalix',
		// 	'password' => '12345'
		// ),
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
		$this->emit('FoodImporter starting...');
	}

	function emit($msg) {
		$result = $this->httpPost($this->SOCKET_API, ['message' => $msg], true, false);
	}

	private function importHtmlPublicPage($chainName, $fileType) {
		$this->emit("importHtmlPublicPage: $chainName / $fileType ");

		// Use basename() function to return the base name of file
		$fileName = $this->DIR_DOWNLOAD . $chainName .".html";
		$url = $this->URLS[$chainName][$fileType];

		// Use file_get_contents() function to get the file
		// from url and use file_put_contents() function to
		// save the file by using base name
		if(file_put_contents( $fileName,file_get_contents($url))) {
		    $this->emit("File downloaded successfully: $fileName");
		}
		else {
		    $this->emit("File downloading failed: $fileName.");
		}
		return $fileName;
	}

	private function downloadCerberusFiles($chainName, $fileType) {
		$this->emit("downloadCerberusFiles: $chainName / $fileType");

		// Mise en place d'une connexion basique
		$conn_id = ftp_ssl_connect($this->URLS[$chainName]['ftp_host']);

		$this->emit("downloadCerberusFiles: Ftp connected.");

		// Identification avec un nom d'utilisateur et un mot de passe
		$ftp_username = $this->URLS[$chainName]['username'];
		$ftp_password = isset($this->URLS[$chainName]['password']) ? $this->URLS[$chainName]['password'] : '';

		$login_result = ftp_login($conn_id, $ftp_username, $ftp_password);
		ftp_pasv($conn_id, TRUE);

		$this->emit("downloadCerberusFiles: Ftp Login successful.");

		// Récupération du contenu d'un dossier
		$contents = ftp_nlist($conn_id, "./".$fileType."*");

	    $this->emit(count($contents)." files should be downloaded ");

		foreach ($contents as $fileName) {
			// Confirm we are not download useless files
			if(strpos($fileName, $fileType) !== 0) continue;

			// local & server file path
			$localFilePath  = $this->DIR_DOWNLOAD . $fileName;
			$remoteFilePath = '/' . $fileName;

			// try to download a file from server
			if(ftp_get($conn_id, $localFilePath, $remoteFilePath, FTP_BINARY)){
			    $this->emit("Downloaded - $localFilePath ");
			}else{
			    $this->emit("Error - $localFilePath ");
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
	    $this->emit("download ".count($arr)." Files (From URLS Array)");
		$downloadedFiles = array();

		foreach ($arr as $url) {

			$fileName = $this->formatFileName($url);

			if(file_put_contents($fileName, file_get_contents($url))) {
			    $this->emit("Downloaded: $fileName");
			    $downloadedFiles[] = $fileName;
			}
			else {
			    $this->emit("Failed downloading: $fileName");
			}
		}
		return $downloadedFiles;
	}

	function pendingFiles($sectionName) {
		$this->emit("Searching pending files of $sectionName ");

		$files = scandir($this->DIR_DOWNLOAD);
		$pendingFiles = array();
		foreach ($files as $fileName) {
			if(strpos($fileName, $sectionName) !== FALSE)
				$pendingFiles[] = $this->DIR_DOWNLOAD . $fileName;
		}

		$this->emit("Found ".count($pendingFiles)." files ");
		return $pendingFiles;
	}


	function cleanXmlFile($fileName) {
		$this->emit("cleanXmlFile $fileName ");

		$xml = $this->file_get_contents_utf8($fileName);
		$realStart = strpos($xml, '<Root>');

		// maayan2000 stores files are with Root instead of root
		if($realStart !== FALSE) {
			  $xml = preg_replace('/<Root>/', '<root>', $xml);
			  $xml = preg_replace('/<\/Root>/', '</root>', $xml);
		}

		// shufersal files have asx tags...
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

	private function getFilesUrls($url, $fileType = null){
		$this->emit("getFilesUrls: $url ");

		$response = file_get_contents($url);
		$hrefIndex = strpos($response, 'href="');
		$urls = array();
		while($hrefIndex !== FALSE) {
			$hrefIndex = $hrefIndex + 6;
			$response = substr($response, $hrefIndex, strlen($response) - $hrefIndex);
			$url = substr($response, 0, strpos($response, '"'));
			$url = preg_replace('/&amp;/', '&', $url);
			if(strpos($url, "http") !== FALSE)
			{
				if(!$fileType || ($fileType && strpos($url, $fileType) !== FALSE))
					$urls[] = trim($url);
			}
			$hrefIndex = strpos($response, 'href="');
		}
		return $urls;
	}

	private function getJsonFilesUrls($url, $chainName) {
		$this->emit("getJsonFilesUrls: $url ");

		$response = file_get_contents($url);
		$jsonArray = json_decode($response, true);
		$urls = array();
		for ($i=0; $i < count($jsonArray); $i++) {
			$urls[] = $this->URLS[$chainName]['download'].$jsonArray[$i]['FileNm'];
		}
		return $urls;
	}

	function runGzExtractionOnFilesArray($arr) {
		$this->emit("runGzExtractionOnFilesArray...");
		$extractedFileNames = array();

		foreach ($arr as $fileName) {
			$xmlFileName = $this->uncompressIfNeeded($fileName);
			$extractedFileNames[] = $xmlFileName;

			// Beautify the final XML file
			// Warning: this can cause a heavier load of file_get_contents
			$this->cleanXmlFile($xmlFileName);
			$this->XmlBeautify($xmlFileName);
		}

		$this->emit(count($extractedFileNames)." files extracted ");
		return $extractedFileNames;
	}

	private function uncompressIfNeeded($fileName) {
		$this->emit("uncompressIfNeeded: $fileName");
		if($this->isGzFile($fileName)) {
			return $this->extractGZFile($fileName);
		} else if($this->isZipFile($fileName)) {
			return $this->extractZipFile($fileName);
		} else if($this->isRarFile($fileName)) {
			$this->emit("uncompressIfNeeded: $fileName - extractRarFile function is not implemented yet...");
		} else {
			$this->emit("uncompressIfNeeded: $fileName - No need...");
			return $fileName;
		}
		return;
	}

	private function isGzFile($fileName) {
		$isGzipped = FALSE;
		if(($zp = fopen($fileName, 'r'))!==FALSE) {
			$start = fread($zp, 3);
			if(0 === mb_strpos($start , "\x1f" . "\x8b" . "\x08")) { // this is a gzip'd file
				$isGzipped = TRUE;
			}
			fclose($zp);
		}

		if($isGzipped)
			$this->emit("isGzFile: $fileName - GZ");

		return $isGzipped;
	}

	private function isZipFile($fileName) {

		$fh = @fopen($fileName, "r");

		if (!$fh) {
		  $this->emit("ERROR: couldn't open $fileName.");
		  return false;
		}

		$blob = fgets($fh, 5);

		fclose($fh);

		if (strpos($blob, 'PK') !== false) {
			$this->emit("isZipFile: $fileName - Zip");
			return true;
		}
		return;
	}

	private function isRarFile($fileName) {

		$fh = @fopen($fileName, "r");

		if (!$fh) {
		  $this->emit("ERROR: couldn't open $fileName.");
		  return false;
		}

		$blob = fgets($fh, 5);

		fclose($fh);

		if (strpos($blob, 'Rar') !== false) {
			$this->emit("isZipFile: $fileName - Rar");
			return true;
		}
		return;
	}

	private function extractGZFile($fileName) {
		$this->emit("extractGZFile: $fileName");

		// Raising this value may increase performance
		$buffer_size = 4096; // read 4kb at a time
		$out_fileName = $fileName . ".xml";

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

	private function extractZipFile($fileName) {
		$this->emit("extractZipFile: $fileName");

		$zip = new ZipArchive;
		$res = $zip->open($fileName);
		if ($res === TRUE) {
			$out_fileName = $zip->getNameIndex(0);
			$zip->extractTo($this->DIR_DOWNLOAD);
			$zip->close();
			unlink($fileName);
			return $this->DIR_DOWNLOAD . $out_fileName;
		}
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
		    $this->emit("Failed loading XML: ");
		    foreach(libxml_get_errors() as $error) {
		        $this->emit($error->message);
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
			$this->emit("$fileName saved ");
		else
			$this->emit("Cannot save $fileName ");
		return $fileName;
	}

	function readHtmlFile($fileName) {
		return file_get_contents($fileName);
	}

	function XmlBeautify($fileName){

		$this->emit("XmlBeautify($fileName) ");

		$xml = file_get_contents($fileName);

		$xml = preg_replace_callback("/(<\/?\w+)(.*?>)/", function ($m) {
  			return strtoupper($m[1]) . $m[2];
  		}, $xml);

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
		$this->emit("removeDuplicatesOlderFiles... ");

		$files = scandir($this->DIR_DOWNLOAD);
		$uniqueFiles = array();
		foreach ($files as $fileName) {
			preg_match('/([A-Za-z0-9\-]*)-([0-9]*)(.xml)/', $fileName, $matches, PREG_OFFSET_CAPTURE);
			$fileKey = $matches[1][0];
			$fileDate = $matches[2][0];
			$fileExt = $matches[3][0];

			// index the fileKey
			if(!isset($uniqueFiles[$fileKey])) {
				$this->emit("indexing $fileKey ");
				$uniqueFiles[$fileKey] = $fileDate;
			}
			// compare
			else if($uniqueFiles[$fileKey] < $fileDate) {
				// previous file saved is older, delete it
				$previousFileName = $this->DIR_DOWNLOAD.$fileKey.'-'.$uniqueFiles[$fileKey].$fileExt;
				$this->emit("delete $previousFileName");
				unlink($previousFileName);
				$uniqueFiles[$fileKey] = $fileDate;
				$this->emit("keep ".$uniqueFiles[$fileKey]."");
			}
			else if($uniqueFiles[$fileKey] > $fileDate) {
				// the current fileName is older, delete it
				$this->emit("delete ".$this->DIR_DOWNLOAD.$fileName."");
				unlink($this->DIR_DOWNLOAD.$fileName);
				$this->emit("keep ".$uniqueFiles[$fileKey]."");
			}
		}
	}

	private function detect_utf_encoding($filename) {
		// Unicode BOM is U+FEFF, but after encoded, it will look like this.
		$UTF32_BIG_ENDIAN_BOM = chr(0x00) . chr(0x00) . chr(0xFE) . chr(0xFF);
		$UTF32_LITTLE_ENDIAN_BOM = chr(0xFF) . chr(0xFE) . chr(0x00) . chr(0x00);
		$UTF16_BIG_ENDIAN_BOM = chr(0xFE) . chr(0xFF);
		$UTF16_LITTLE_ENDIAN_BOM = chr(0xFF) . chr(0xFE);
		$UTF8_BOM = chr(0xEF) . chr(0xBB) . chr(0xBF);

	    $text = file_get_contents($filename);
	    $first2 = substr($text, 0, 2);
	    $first3 = substr($text, 0, 3);
	    $first4 = substr($text, 0, 3);
	    if ($first3 == $UTF8_BOM) return 'UTF-8';
	    elseif ($first4 == $UTF32_BIG_ENDIAN_BOM) return 'UTF-32BE';
	    elseif ($first4 == $UTF32_LITTLE_ENDIAN_BOM) return 'UTF-32LE';
	    elseif ($first2 == $UTF16_BIG_ENDIAN_BOM) return 'UTF-16BE';
	    elseif ($first2 == $UTF16_LITTLE_ENDIAN_BOM) return 'UTF-16LE';
	}

	function file_get_contents_utf8($fn) {
		$content = file_get_contents($fn);
		$encoding = $this->detect_utf_encoding($fn);

		$this->emit("file_get_contents_utf8: detected encoding ".$encoding);

		switch ($encoding) {
			case 'UTF-8':
				return $content;
				break;

			case 'UTF-16LE':
				return preg_replace("/^pack('H*','EFBBBF')/", '', iconv( 'UTF-16', 'UTF-8', $content));
				break;

			// case 'UTF-16BE':
			// 	return preg_replace("/^pack('H*','EFBBBF')/", '', iconv( 'UTF-16', 'UTF-8', $content));
			// 	break;


			// TODO: check the EFBBBF
			// case 'UTF-32BE':
			// case 'UTF-32LE':
			// 	return preg_replace("/^pack('H*','EFBBBF')/", '', iconv( 'UTF-32', 'UTF-8', $content));
			// 	break;

			default:
				return $content;
				break;
		}
	}

	// Loads the stores file list, identify the ChainCode, and runs on the stores list to save each store with its name and the ChainID
	function parseXMLStores($storeXmlFileName) {
		$this->emit("parseXMLStores $storeXmlFileName ");

		$XMLfileContent = file_get_contents($storeXmlFileName);
		$xml = $this->xmlStringToArray($XMLfileContent);

	    $ChainID = $xml->CHAINID;
	    $this->emit("ChainID found: $ChainID ");

	    $Stores = $xml->xpath('//STORES')[0];

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

	    $this->emit("Should insert ".count($newStores)." stores ");

	   	for ($i=0; $i < ceil(count($newStores)/50)*50; $i+=50) {

	   		$payload = array("stores"=> array_slice($newStores, $i, 50));

		    $insertStore = $this->httpPost(
		    	"/RefStore/insertRefStore.php",
		    	$payload
		    );

		    if($insertStore['error']){
		    	$shouldKeepFile = true;
		    	$this->emit($insertStore['message']."");
		    }
		    else {
		    	$this->emit("Updated ".count($payload['stores'])." stores of ChainID $ChainID");
		    }
	   	}

	    if(!isset($shouldKeepFile)) {
	    	unlink($storeXmlFileName);
	    }

	    return;
	}


	function parseXMLPriceFull($priceFullXmlFileName) {

		$this->emit("parseXMLPriceFull $priceFullXmlFileName ");

		$XMLfileContent = file_get_contents($priceFullXmlFileName);
		$xml = $this->xmlStringToArray($XMLfileContent);


		// Preparing data for ETLLoad
	    $ChainID 	= 	$xml->ChainId;
		$SubChainId = 	$xml->SubChainId;
		$StoreId 	= 	$xml->StoreId;

	    $this->emit("ChainID $ChainID / SubChainId $SubChainId / StoreID $StoreId");

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

		$this->emit("Should insert ".count($newItems)." items ");

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
		    	$this->emit($insertItems['message']."");
		    }
		    else {
		    	$this->emit("Updated ".count($payload['items'])." items of ChainID $ChainID");
		    }
	   	}

	    if(!isset($shouldKeepFile)) {
	    	unlink($priceFullXmlFileName);
	    }

	    return;
	}
	function download($chainName, $fileType) {
		// Download from Cerberus FTP of the Chain
		if(isset($this->URLS[$chainName]['ftp_host'])){
			$this->downloadCerberusFiles($chainName, $fileType);
			$gzipXmlFiles = $this->pendingFiles($fileType);
			$this->runGzExtractionOnFilesArray($gzipXmlFiles);
		}
		// Download from a public HTML file depending on the filetype
		else if(isset($this->URLS[$chainName]['html']) && !isset($this->URLS[$chainName]['shouldFiltersLinks'])) {
			$storePublicPage = $this->importHtmlPublicPage($chainName, $fileType);
			$urls = $this->getFilesUrls($storePublicPage);
			$downloadedFiles = $this->downloadFilesFromURLSArray($urls);
			$this->runGzExtractionOnFilesArray($downloadedFiles);
		}
		// Download from a public HTML file containing a whole list
		else if(isset($this->URLS[$chainName]['html']) && isset($this->URLS[$chainName]['shouldFiltersLinks'])) {
			$storePublicPage = $this->importHtmlPublicPage($chainName, 'page');
			$urls = $this->getFilesUrls($storePublicPage, $fileType);
			$downloadedFiles = $this->downloadFilesFromURLSArray($urls);
			$this->runGzExtractionOnFilesArray($downloadedFiles);
		}
		// Download from a public JSON API depending on the filetype
		else if(isset($this->URLS[$chainName]['json'])) {
			$urls = $this->getJsonFilesUrls($this->URLS[$chainName][$fileType]."&WDate=".date("d/m/Y"), $chainName);
			$downloadedFiles = $this->downloadFilesFromURLSArray($urls);
			$this->runGzExtractionOnFilesArray($downloadedFiles);
		}
		else {
			$this->emit("Could not download $fileType files of $chainName. Some configuration are missing.");
		}
	}
	function parseXML($fileType) {
		$xmlFiles = $this->pendingFiles($fileType);

		if($fileType == 'Stores') {
			foreach ($xmlFiles as $xmlFile) {
				$this->parseXMLStores($xmlFile);
			}
		}
		else if($fileType == 'PriceFull') {
			foreach ($xmlFiles as $xmlFile) {
				$this->parseXMLPriceFull($xmlFile);
			}
		}
	}
}
