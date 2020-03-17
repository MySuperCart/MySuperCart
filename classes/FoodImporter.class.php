<?php
/**
*
*/
class FoodImporter
{
	// private $API_DOMAIN = "http://ec2-35-180-25-13.eu-west-3.compute.amazonaws.com/api";
	private $API_DOMAIN = "http://techfood.local/api";
	// private $SOCKET_API = "http://ec2-35-180-25-13.eu-west-3.compute.amazonaws.com:9001/news";
	private $SOCKET_API = "http://techfood.local:9001/news";

	// Initialize a file URL to the variable
	private $URLS = array(
		'kingstore' => array(
			'json' => true,
			'page' => "https://www.kingstore.co.il/Food_Law/Main.aspx",
			'Stores' => "https://www.kingstore.co.il/Food_Law/MainIO_Hok.aspx?WStore=0&WFileType=1",
			'PriceFull' => "https://www.kingstore.co.il/Food_Law/MainIO_Hok.aspx?WStore=0&WFileType=4",
			'Price' => "https://www.kingstore.co.il/Food_Law/MainIO_Hok.aspx?WStore=0&WFileType=2",
			'Promo' => "https://www.kingstore.co.il/Food_Law/MainIO_Hok.aspx?WStore=0&WFileType=3",
			'download' => "https://www.kingstore.co.il/Food_Law/Download/",
			'shouldAppendDateFormatToPage' => "m/d/Y"
		),
		'maayan2000' => array(
			'json' => true,
			'page' => "http://maayan2000.binaprojects.com/Main.aspx",
			'Stores' => "http://maayan2000.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=1",
			'PriceFull' => "http://maayan2000.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=4",
			'Price' => "http://maayan2000.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=2",
			'Promo' => "http://maayan2000.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=3",
			'download' => "http://maayan2000.binaprojects.com/Download/",
			'shouldAppendDateFormatToPage' => "d/m/Y"
		),
		'victory_mahsane_hashuk' => array(
			'html' => true,
			'page' => "http://matrixcatalog.co.il/NBCompetitionRegulations.aspx",
			'shouldFiltersLinks' => true,
			'shouldPrependDownloadFieldForDownload' => true,
			'download' => "http://matrixcatalog.co.il/",
		),
		'zolvebegadol' => array(
			'json' => true,
			'page' => "http://zolvebegadol.binaprojects.com/Main.aspx",
			'Stores' => "http://zolvebegadol.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=1",
			'PriceFull' => "http://zolvebegadol.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=4",
			'Price' => "http://zolvebegadol.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=2",
			'Promo' => "http://zolvebegadol.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=3",
			'download' => "http://zolvebegadol.binaprojects.com/Download/",
			'shouldAppendDateFormatToPage' => "d/m/Y"
		),
		'ybitan' => array(
			'html' => true,
			'page' => "http://publishprice.ybitan.co.il/",
			'shouldFiltersLinks' => true,
			'shouldAppendDateFormatToPage' => "Ymd",
			'shouldPrependPageFieldForDownload' => true
		),
		'mega' => array(
			'html' => true,
			'page' => "http://publishprice.mega.co.il/",
			'shouldFiltersLinks' => true,
			'shouldAppendDateFormatToPage' => "Ymd",
			'shouldPrependPageFieldForDownload' => true
		),
		'superpharm' => array(
			'html' => true,
			'page' => "http://prices.super-pharm.co.il/",
			'Stores' => "http://prices.super-pharm.co.il/?type=StoresFull&store=&date=",
			'PriceFull' => "http://prices.super-pharm.co.il/?type=PriceFull&store=&date=",
			'Price' => "http://prices.super-pharm.co.il/?type=Price&store=&date=",
			'Promo' => "http://prices.super-pharm.co.il/?type=Promo&store=&date=",
			'shouldAppendDateFormatToCustom' => "Y-m-d",
			'shouldPrependPageFieldForDownload' => true
			// TODO: handle pagination
		),
		// 'superbareket' => array(
		// 	'page' => "http://prices.super-bareket.co.il/"
		// ),
		'shukhayir' => array(
			'json' => true,
			'page' => "http://shuk-hayir.binaprojects.com/Main.aspx",
			'Stores' => "http://shuk-hayir.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=1",
			'PriceFull' => "http://shuk-hayir.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=4",
			'Price' => "http://shuk-hayir.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=2",
			'Promo' => "http://shuk-hayir.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=3",
			'download' => "http://shuk-hayir.binaprojects.com/Download/",
			'shouldAppendDateFormatToPage' => "d/m/Y"
		),
		'shefabirkathashem' => array(
			'json' => true,
			'page' => "http://shefabirkathashem.binaprojects.com/Main.aspx",
			'Stores' => "http://shefabirkathashem.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=1",
			'PriceFull' => "http://shefabirkathashem.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=4",
			'Price' => "http://shefabirkathashem.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=2",
			'Promo' => "http://shefabirkathashem.binaprojects.com/MainIO_Hok.aspx?WStore=0&WFileType=3",
			'download' => "http://shefabirkathashem.binaprojects.com/Download/",
			'shouldAppendDateFormatToPage' => "d/m/Y"
		),
		'shufersal' => array(
			'html' => true,
			'Stores' => 'http://prices.shufersal.co.il/FileObject/UpdateCategory?catID=5&storeId=0',
			'PriceFull' => "http://prices.shufersal.co.il/FileObject/UpdateCategory?catID=2&storeId=0&asort=Time&sortdir=DESC&sort=Time",
			'Price' => "http://prices.shufersal.co.il/FileObject/UpdateCategory?catID=1&storeId=0&asort=Time&sortdir=DESC&sort=Time",
			'Promo' => "http://prices.shufersal.co.il/FileObject/UpdateCategory?catID=3&storeId=0&asort=Time&sortdir=DESC&sort=Time"
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
	private $COOKIES;
	public $LOG_LEVEL;

	function __construct() {}

	function emit($importance, $msg) {
		if($this->LOG_LEVEL <= $importance)
			$result = $this->httpPost($this->SOCKET_API, ['message' => $msg], true, false);
	}

	private function importHtmlPublicPage($chainName, $fileType) {
		$this->emit(0, "importHtmlPublicPage: $chainName / $fileType ");

		// Use basename() function to return the base name of file
		$fileName = $this->DIR_DOWNLOAD . $chainName .".html";
		$url = $this->URLS[$chainName][$fileType];
		// append date() to 'page'
		$url .= (isset($this->URLS[$chainName]['shouldAppendDateFormatToPage'])) ? 
			date($this->URLS[$chainName]['shouldAppendDateFormatToPage']) :
			'';
		// append date() to 'Stores' for example
		$url .= (isset($this->URLS[$chainName]['shouldAppendDateFormatToCustom'])) ? 
			date($this->URLS[$chainName]['shouldAppendDateFormatToCustom']) :
			'';

		// Use file_get_contents() function to get the file
		// from url and use file_put_contents() function to
		// save the file by using base name
		if(file_put_contents( $fileName, $this->httpGet($url))) {
		    $this->emit(0, "File downloaded successfully: $fileName");
		}
		else {
		    $this->emit(0, "File downloading failed: $fileName.");
		}
		return $fileName;
	}

	private function downloadCerberusFiles($chainName, $fileType) {
		$this->emit(0, "downloadCerberusFiles: $chainName / $fileType");

		// Mise en place d'une connexion basique
		$conn_id = ftp_ssl_connect($this->URLS[$chainName]['ftp_host']);

		$this->emit(0, "downloadCerberusFiles: Ftp connected.");

		// Identification avec un nom d'utilisateur et un mot de passe
		$ftp_username = $this->URLS[$chainName]['username'];
		$ftp_password = isset($this->URLS[$chainName]['password']) ? $this->URLS[$chainName]['password'] : '';

		$login_result = ftp_login($conn_id, $ftp_username, $ftp_password);
		ftp_pasv($conn_id, TRUE);

		$this->emit(0, "downloadCerberusFiles: Ftp Login successful.");

		// Récupération du contenu d'un dossier
		$contents = ftp_nlist($conn_id, "./".$fileType."*");
		$contents = $this->filterUniqueUrlsInArray($contents);

	    $this->emit(1, count($contents)." files should be downloaded ");

		foreach ($contents as $fileName) {
			// Confirm we are not download useless files
			if(strpos($fileName, $fileType) !== 0) continue;

			// local & server file path
			$localFilePath  = $this->DIR_DOWNLOAD . $fileName;
			$remoteFilePath = '/' . $fileName;

			// try to download a file from server
			if(ftp_get($conn_id, $localFilePath, $remoteFilePath, FTP_BINARY)){
			    $this->emit(0, "Downloaded - $localFilePath ");
			}else{
			    $this->emit(0, "Error - $localFilePath ");
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
	private function formatOutputFileName($url) {
		if (filter_var($url, FILTER_VALIDATE_URL) !== FALSE) {
			stream_context_set_default(
			    array(
			        'http' => array(
			            'method' => 'HEAD',
			    		'header'=>"Cookie: ".$this->COOKIES."\r\n",
			    		'timeout' => 5
			        )
			    )
			);
			$headers = get_headers($url, 1);
			if(isset($headers['Content-Disposition'])) {
	            $tmp_name = explode('=', $headers['Content-Disposition']);
	            if ($tmp_name[1])
	            	return $this->DIR_DOWNLOAD . trim($tmp_name[1],'";\'');
	        }
	    }

		$stripped_url = preg_replace('/\\?.*/', '', $url);
	    return $this->DIR_DOWNLOAD . basename($stripped_url);
	}

	function downloadFilesFromURLSArray($arr) {
	    $this->emit(0, "download ".count($arr)." Files (From URLS Array)");
		$downloadedFiles = array();

		foreach ($arr as $url) {

	    	$fileContent = $this->httpGet($url);
			$fileName = $this->formatOutputFileName($url);

	    	$this->emit(0, "downloading $url into $fileName");

			if(file_put_contents($fileName, $fileContent)) {
			    $this->emit(0, "Downloaded: $fileName");
			    $downloadedFiles[] = $fileName;
			}
			else {
			    $this->emit(0, "Failed downloading: $fileName");
			}
		}
		return $downloadedFiles;
	}

	function pendingFiles($sectionName) {
		$this->emit(0, "Searching pending files of $sectionName ");

		$files = scandir($this->DIR_DOWNLOAD);
		$pendingFiles = array();
		foreach ($files as $fileName) {
			if(strpos($fileName, $sectionName) !== FALSE)
				$pendingFiles[] = $this->DIR_DOWNLOAD . $fileName;
		}

		$this->emit(0, "Found ".count($pendingFiles)." files ");
		return $pendingFiles;
	}


	function cleanXmlFile($fileName) {
		$this->emit(0, "cleanXmlFile $fileName ");

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

	private function getFilesUrls($chainName, $htmlFileToParse, $fileType = null){
		$this->emit(0, "getFilesUrls: $chainName / $htmlFileToParse / $fileType");

		$response = file_get_contents($htmlFileToParse);
		$hrefIndex = strpos($response, 'href=');
		$urls = array();
		while($hrefIndex !== FALSE) {
			$delimiter = substr($response, $hrefIndex + 5, 1);
			$hrefIndex = $hrefIndex + 6;
			$response = substr($response, $hrefIndex, strlen($response) - $hrefIndex);
			$url = substr($response, 0, strpos($response, $delimiter));
			$url = preg_replace('/&amp;/', '&', $url);

			$isSuperPharmLink = (strpos($url, 'getlink') !== FALSE);

			// The URL found should at least contain the 13 numbers of the chain barcode
			if(strlen($url) > 13 || $isSuperPharmLink)
			{
				// If we filtered urls by file type, we assert the file type keyword (like "Promo")
				// is indeed in the file name
				if($isSuperPharmLink || !$fileType || ($fileType && strpos($url, $fileType) !== FALSE)) {

					$url = trim($url);

					// append the base url to the url if no http found
					if(strpos($url, "http") === FALSE) {
						$prefix = "";
						$prefix .= (isset($this->URLS[$chainName]['shouldPrependDownloadFieldForDownload'])) ? $this->URLS[$chainName]['download'] : '';
						$prefix .= (isset($this->URLS[$chainName]['shouldPrependPageFieldForDownload'])) ? $this->URLS[$chainName]['page'] : '';
						$prefix .= (isset($this->URLS[$chainName]['shouldAppendDateFormatToPage'])) ? date($this->URLS[$chainName]['shouldAppendDateFormatToPage']).'/' : '';
						$url = $prefix . $url;
					}

					// ensure the url does not contains \ instead of /
					$url = str_replace('\\', '/', $url);

					// check the url is not a relative path
					if(strpos($url, "http") == FALSE)
						$urls[] = $url;
				}
			}
			$hrefIndex = strpos($response, 'href=');
		}

		// We can now remove the html file
		unlink($htmlFileToParse);

		$urls = $this->filterUniqueUrlsInArray($urls);
		return $urls;
	}

	private function getJsonFilesUrls($url, $chainName) {
		$this->emit(0, "getJsonFilesUrls: $url ");

		$response = $this->httpGet($url);
		$jsonArray = json_decode($response, true);
		$urls = array();
		for ($i=0; $i < count($jsonArray); $i++) {
			$urls[] = $this->URLS[$chainName]['download'].$jsonArray[$i]['FileNm'];
		}

		$this->emit(0, "getJsonFilesUrls: found ".count($urls)." URLS ");
		return $urls;
	}

	function runGzExtractionOnFilesArray($arr) {
		$this->emit(0, "runGzExtractionOnFilesArray...");
		$extractedFileNames = array();

		foreach ($arr as $fileName) {
			if(substr($fileName, -3) === 'xml') {
				$this->cleanXmlFile($fileName);
				$this->XmlBeautify($fileName);
				$extractedFileNames[] = $fileName;
			}
			else {
				$xmlFileName = $this->uncompressIfNeeded($fileName);

				// Beautify the final XML file
				// Warning: this can cause a heavier load of file_get_contents
				$this->cleanXmlFile($xmlFileName);
				$this->XmlBeautify($xmlFileName);
				$extractedFileNames[] = $xmlFileName;
			}
		}

		$this->emit(1, count($extractedFileNames)." files are ready for parsing.");
		return $extractedFileNames;
	}

	private function uncompressIfNeeded($fileName) {
		$this->emit(0, "uncompressIfNeeded: $fileName");
		if($this->isGzFile($fileName)) {
			return $this->extractGZFile($fileName);
		} else if($this->isZipFile($fileName)) {
			return $this->extractZipFile($fileName);
		} else if($this->isRarFile($fileName)) {
			$this->emit(0, "uncompressIfNeeded: $fileName - extractRarFile function is not implemented yet...");
		} else {
			$this->emit(0, "uncompressIfNeeded: $fileName - No need...");
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
			$this->emit(0, "isGzFile: $fileName - GZ");

		return $isGzipped;
	}

	private function isZipFile($fileName) {

		$fh = @fopen($fileName, "r");

		if (!$fh) {
		  $this->emit(0, "ERROR: couldn't open $fileName.");
		  return false;
		}

		$blob = fgets($fh, 5);

		fclose($fh);

		if (strpos($blob, 'PK') !== false) {
			$this->emit(0, "isZipFile: $fileName - Zip");
			return true;
		}
		return;
	}

	private function isRarFile($fileName) {

		$fh = @fopen($fileName, "r");

		if (!$fh) {
		  $this->emit(0, "ERROR: couldn't open $fileName.");
		  return false;
		}

		$blob = fgets($fh, 5);

		fclose($fh);

		if (strpos($blob, 'Rar') !== false) {
			$this->emit(0, "isZipFile: $fileName - Rar");
			return true;
		}
		return;
	}

	private function extractGZFile($fileName) {
		$this->emit(0, "extractGZFile: $fileName");

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
		$this->emit(0, "extractZipFile: $fileName");

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

	function insertChain($ChainID, $ChainName) {
	  $Chain = $this->httpPost(
	  	"/RefChain/insertRefChain.php",
	  	array("ChainID" => $ChainID, "ChainName" => $ChainName)
	  );
	  return $Chain['ChainID'];
	}

	private function xmlStringToArray($myXMLData) {
		$xml = simplexml_load_string($myXMLData);
		if ($xml === false) {
		    $this->emit(1, "Failed loading XML: ");
		    foreach(libxml_get_errors() as $error) {
		        $this->emit(1, $error->message);
		    }
		} else {
		    return $xml;
		}
	}

	private function httpPost($route, $data, $returnJson=true, $toApi=true) {

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

	private function httpGet($url) {
		if (filter_var($url, FILTER_VALIDATE_URL) === FALSE)
		    return;
		// Create a stream
		$opts = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header'=>"Cookie: ".$this->COOKIES
		  )
		);

		$context = stream_context_create($opts);

		// Open the file using the HTTP headers set above
		$file = file_get_contents($url, false, $context);

		foreach($http_response_header as $header)
	    {
	        if (strpos(strtolower($header),'set-cookie') !== false)
	        {
	            $tmp_name = explode(';', $header);
	            if ($tmp_name[0]) {
	            	$setCookieValue = trim(substr($tmp_name[0], 12));
	            	$this->COOKIES = $setCookieValue;
	            }
	        }
	    }

	    return $file;

	}
	function saveStrAsFile($str, $fileName) {
		if(file_put_contents($fileName, $str))
			$this->emit(0, "$fileName saved ");
		else
			$this->emit(0, "Cannot save $fileName ");
		return $fileName;
	}

	function readHtmlFile($fileName) {
		return file_get_contents($fileName);
	}

	function XmlBeautify($fileName){

		$this->emit(0, "XmlBeautify($fileName) ");

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
		$this->emit(0, "removeDuplicatesOlderFiles... ");

		$files = scandir($this->DIR_DOWNLOAD);
		$uniqueFiles = array();
		foreach ($files as $fileName) {
			preg_match('/([A-Za-z0-9\-]*)-([0-9]*)(.xml)/', $fileName, $matches, PREG_OFFSET_CAPTURE);
			$fileKey = $matches[1][0];
			$fileDate = $matches[2][0];
			$fileExt = $matches[3][0];

			if(empty($fileKey)) continue;

			// index the fileKey
			if(!isset($uniqueFiles[$fileKey])) {
				$this->emit(0, "indexing $fileKey ");
				$uniqueFiles[$fileKey] = $fileDate;
			}
			// compare
			else if($uniqueFiles[$fileKey] <= $fileDate) {
				// previous file saved is older, delete it
				$previousFileName = $this->DIR_DOWNLOAD.$fileKey.'-'.$uniqueFiles[$fileKey].$fileExt;
				$this->emit(0, "delete $previousFileName");
				unlink($previousFileName);
				$uniqueFiles[$fileKey] = $fileDate;
				$this->emit(0, "keep ".$uniqueFiles[$fileKey]."");
			}
			else if($uniqueFiles[$fileKey] > $fileDate) {
				// the current fileName is older, delete it
				$this->emit(0, "delete ".$this->DIR_DOWNLOAD.$fileName."");
				unlink($this->DIR_DOWNLOAD.$fileName);
				$this->emit(0, "keep ".$uniqueFiles[$fileKey]."");
			}
		}
	}

	// TODO: Check if $returnUrls contains really the most recent files only 
	function filterUniqueUrlsInArray($arr) {
		$this->emit(0, "filterUniqueUrlsInArray with ".count($arr)." URLs");

		$uniqueFiles = array();
		foreach ($arr as $fileName) {
			preg_match('/([A-Za-z0-9\-]*)-([0-9]*)(\..*)/', $fileName, $matches, PREG_OFFSET_CAPTURE);
			$fileKey = $matches[1][0];
			$fileDate = $matches[2][0];
			$fileExt = $matches[3][0];

			// if the fileName has another pattern
			if(!$fileKey && !$fileDate && !$fileExt){
				$uniqueFiles[$fileName] = array('fileName' => $fileName);
				continue;
			}

			// index the fileKey
			if(!isset($uniqueFiles[$fileKey])) {
				$uniqueFiles[$fileKey] = array('fileDate' => $fileDate, 'fileName' => $fileName);
			}
			// compare
			else if($uniqueFiles[$fileKey]['fileDate'] <= $fileDate) {
				$uniqueFiles[$fileKey]['fileDate'] = $fileDate;
				$this->emit(0, "keep ".$uniqueFiles[$fileKey]['fileName']);
			}
			else if($uniqueFiles[$fileKey]['fileDate'] > $fileDate) {
				$this->emit(0, "keep ".$uniqueFiles[$fileKey]['fileName']);
			}
		}

		$returnUrls = array();
		foreach ($uniqueFiles as $key => $value) {
			$returnUrls[] = $uniqueFiles[$key]['fileName'];
		}

		return $returnUrls;
	}

	private function callUrlsAndExtractFileUrlsFromJson($arr) {
		$this->emit(0, "callUrlsAndExtractFileUrlsFromJson");
		$fileUrls = array();

		foreach ($arr as $url) {
			$this->emit(0, "Call $url");
			$json = $this->httpGet($url);
			$json = json_decode($json, true);

			if($json['status'] === 0) {
				$parsedUrl = parse_url($url);
				$fileUrls[] = $parsedUrl["scheme"] . '://' . $parsedUrl["host"] . $json['href'];
			}
		}
		return $fileUrls;
	}
	private function detect_encoding($filename) {
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
	    // elseif (strpos($text, 'ISO-8859-8') !== false) return 'ISO-8859-8';
	}

	function file_get_contents_utf8($fn) {
		$content = file_get_contents($fn);
		$encoding = $this->detect_encoding($fn);

		$this->emit(0, "file_get_contents_utf8: detected encoding ".$encoding);

		switch ($encoding) {
			case 'UTF-8':
				return $content;
				break;

			case 'UTF-16LE':
				return preg_replace("/^pack('H*','EFBBBF')/", '', iconv( 'UTF-16', 'UTF-8', $content));
				break;

			// case 'ISO-8859-8':
			// 	return mb_convert_encoding($content, 'UTF-8', 'ISO-8859-8');
			// 	break;

			// case 'UTF-16BE':
			// 	return preg_replace("/^pack('H*','EFBBBF')/", '', iconv( 'UTF-16', 'UTF-8', $content));
			// 	break;


			// TODO: check the EFBBBF
			// case 'UTF-32BE':
			// case 'UTF-32LE':
			// 	return preg_replace("/^pack('H*','EFBBBF')/", '', iconv( 'UTF-32', 'UTF-8', $content));
			// 	break;

			default:
				// return $content;
				return $content;
				break;
		}
	}

	// Loads the stores file list, identify the ChainCode, and runs on the stores list to save each store with its name and the ChainID
	function parseXMLStores($storeXmlFileName) {
		$this->emit(0, "parseXMLStores $storeXmlFileName ");

		$XMLfileContent = file_get_contents($storeXmlFileName);
		$xml = $this->xmlStringToArray($XMLfileContent);

	    $ChainID = $xml->xpath('//CHAINID')[0];
	    $this->emit(1, "ChainID found: $ChainID ");
	    $ChainName = $xml->xpath('//CHAINNAME');
	    $ChainName = count($ChainName) ? $ChainName[0] : "";

	    $this->insertChain(strval($ChainID), strval($ChainName));

	    $Stores = $xml->xpath('//STOREID')[0];

	    try {
	    	$Stores = $Stores->xpath("..")[0]->xpath("..")[0];
	    } catch (Exception $e) {
			$this->emit(1, "ERROR with: $storeXmlFileName");
			$this->emit(1, $e->getMessage());
	    }


	    $newStores = array();

		foreach($Stores->children() as $Store) {
			$newStores[] = array(
				"Street1"		=> (string)$Store->ADDRESS,
				"City"			=> (string)$Store->CITY,
				"ZipCode"		=> (string)$Store->ZIPCODE,
				"StoreID"		=> (string)$Store->STOREID,
				"StoreName"		=> (string)$Store->STORENAME,
				"ChainID"		=> (string)$ChainID
			);
	    }

	    $this->emit(1, "Should insert ".count($newStores)." stores ");

	   	for ($i=0; $i < ceil(count($newStores)/50)*50; $i+=50) {

	   		$payload = array("stores"=> array_slice($newStores, $i, 50));

		    $insertStore = $this->httpPost(
		    	"/RefStore/insertRefStore.php",
		    	$payload
		    );

		    if($insertStore['error']){
		    	$shouldKeepFile = true;
		    	$this->emit(0, $insertStore['message']."");
		    }
		    else {
		    	$this->emit(1, "Updated ".count($payload['stores'])." stores of ChainID $ChainID");
		    }
	   	}

	    if(!isset($shouldKeepFile)) {
	    	unlink($storeXmlFileName);
	    }

	    return;
	}


	function parseXMLPriceFull($priceFullXmlFileName) {

		$this->emit(0, "parseXMLPriceFull $priceFullXmlFileName ");

		$XMLfileContent = file_get_contents($priceFullXmlFileName);
		$xml = $this->xmlStringToArray($XMLfileContent);

		// Preparing data for ETLLoad
	    $ChainID 	= 	$xml->xpath('//CHAINID')[0];
		$SubChainId = 	$xml->xpath('//SUBCHAINID')[0];
		$StoreId 	= 	$xml->xpath('//STOREID')[0];

		// Insert the RefChain is case it's missing
	    $this->insertChain(strval($ChainID), "");

		// ItemNameFieldName
		if( $xml->xpath('//ITEMNAME') ) {
			$ItemNameFieldName = "ITEMNAME";
		} 
		else if( $xml->xpath('//ITEMNM') ) {
			$ItemNameFieldName = "ITEMNM";
		} else {
			$this->emit(1, "ERROR with: $priceFullXmlFileName");
			die();
		}

		// ItemFieldName
	    try {
	    	$ItemFieldName = $xml->xpath('//ITEMCODE')[0]->xpath("..")[0]->getName();
	    } catch(Exception $e) {
			$this->emit(1, "ERROR: Cannot find the ITEMS XML node name with: $priceFullXmlFileName");
			die();
		}

	    $this->emit(0, "ChainID $ChainID / SubChainId $SubChainId / StoreID $StoreId / ItemFieldName $ItemFieldName / ItemNameFieldName $ItemNameFieldName");

   		$payload = array(
			"ChainID" => (string)$ChainID,
			"StoreID" => (string)$StoreId,
			"FileName" => (string)realpath($priceFullXmlFileName),
			"ItemNameFieldName" => (string)$ItemNameFieldName,
			"ItemFieldName" => (string)$ItemFieldName
   		);

	    $insertItems = $this->httpPost(
	    	"/Items/insertItems.php",
	    	$payload
	    );

	    if($insertItems['error']) {
	    	$shouldKeepFile = true;
	    	$this->emit(1, "ERROR with file $priceFullXmlFileName");
	    	$this->emit(1, $insertItems['message']);
	    }
	    else {
	    	if($insertItems['affected_rows'] == 0) 
	    		$shouldKeepFile = true;

	    	$this->emit(1, "Updated ".$insertItems['affected_rows']." items of ChainID $ChainID");
	    }

	    if(!isset($shouldKeepFile)) {
	    	unlink($priceFullXmlFileName);
	    }

	    return;
	}


	function parseXMLPromo($promoXmlFileName) {

		$this->emit(0, "parseXMLPriceFull $promoXmlFileName ");

		$XMLfileContent = file_get_contents($promoXmlFileName);
		$xml = $this->xmlStringToArray($XMLfileContent);

		// Preparing data for ETLLoad
	    $ChainID 	= 	$xml->CHAINID;
		$SubChainId = 	$xml->SUBCHAINID;
		$StoreId 	= 	$xml->STOREID;

		// Insert the RefChain is case it's missing
	    $this->insertChain(strval($ChainID), "");

		// ItemNameFieldName
		if( $xml->xpath('//ITEMNAME') ) {
			$ItemNameFieldName = "ITEMNAME";
		} 
		else if( $xml->xpath('//ITEMNM') ) {
			$ItemNameFieldName = "ITEMNM";
		} else {
			$this->emit(1, "ERROR with: $promoXmlFileName");
			die();
		}

		// ItemFieldName
	    try {
	    	$ItemFieldName = $xml->xpath('//ITEMCODE')[0]->xpath("..")[0]->getName();
	    } catch(Exception $e) {
			$this->emit(1, "ERROR: Cannot find the ITEMS XML node name with: $promoXmlFileName");
			die();
		}

	    $this->emit(0, "ChainID $ChainID / SubChainId $SubChainId / StoreID $StoreId");

   		$payload = array(
			"ChainID" => (string)$ChainID,
			"StoreID" => (string)$StoreId,
			"FileName" => (string)realpath($promoXmlFileName),
			"ItemNameFieldName" => (string)$ItemNameFieldName,
			"ItemFieldName" => (string)$ItemFieldName
   		);

	    $insertItems = $this->httpPost(
	    	"/Items/insertItems.php",
	    	$payload
	    );

	    if($insertItems['error']) {
	    	$shouldKeepFile = true;
	    	$this->emit(1, "ERROR with file $promoXmlFileName");
	    	$this->emit(1, $insertItems['message']);
	    }
	    else {
	    	if($insertItems['affected_rows'] == 0) 
	    		$shouldKeepFile = true;

	    	$this->emit(1, "Updated ".$insertItems['affected_rows']." items of ChainID $ChainID");
	    }

	    if(!isset($shouldKeepFile)) {
	    	unlink($promoXmlFileName);
	    }

	    return;
	}

	function download($chainName, $fileType) {
		$this->emit(1, "Download $chainName - $fileType");
		// Download from Cerberus FTP of the Chain
		if(isset($this->URLS[$chainName]['ftp_host'])){
			$this->downloadCerberusFiles($chainName, $fileType);
			$gzipXmlFiles = $this->pendingFiles($fileType);
			$this->runGzExtractionOnFilesArray($gzipXmlFiles);
		}
		// Download from a public HTML file depending on the filetype
		else if(isset($this->URLS[$chainName]['html'])) {

			// the filetype URLs are ready to be called
			// but the links on the page should be filtered
			// depending on the fileType
			if(!isset($this->URLS[$chainName]['shouldFiltersLinks'])) {
				$storePublicPage = $this->importHtmlPublicPage($chainName, $fileType);
				$urls = $this->getFilesUrls($chainName, $storePublicPage);

				// specific for superpharm
				if($chainName == 'superpharm') {
					$urls = $this->callUrlsAndExtractFileUrlsFromJson($urls);
				}
				$downloadedFiles = $this->downloadFilesFromURLSArray($urls);
				$this->runGzExtractionOnFilesArray($downloadedFiles);
			}
			// Download from a public HTML file containing a whole list
			// the filetype URLs are ready to be called
			// but the links on the page should be filtered
			// depending on the fileType
			else if(isset($this->URLS[$chainName]['shouldFiltersLinks'])) {
				$storePublicPage = $this->importHtmlPublicPage($chainName, 'page');
				$urls = $this->getFilesUrls($chainName, $storePublicPage, $fileType);
				$downloadedFiles = $this->downloadFilesFromURLSArray($urls);
				$this->runGzExtractionOnFilesArray($downloadedFiles);
			}
		}
		// Download from a public JSON API depending on the filetype
		else if(isset($this->URLS[$chainName]['json'])) {
			$urls = $this->getJsonFilesUrls($this->URLS[$chainName][$fileType]."&WDate=".date($this->URLS[$chainName]['shouldAppendDateFormatToPage']), $chainName);
			$downloadedFiles = $this->downloadFilesFromURLSArray($urls);
			$this->runGzExtractionOnFilesArray($downloadedFiles);
		}
		else {
			$this->emit(0, "Could not download $fileType files of $chainName. Some configuration are missing.");
		}
	}
	function parseXML($fileType) {
		$this->removeDuplicatesOlderFiles();
		$xmlFiles = $this->pendingFiles($fileType);

		if($fileType == 'Stores') {
			foreach ($xmlFiles as $xmlFile) {
				$this->parseXMLStores($xmlFile);
			}
		}
		else if($fileType == 'PriceFull' || $fileType == 'Price') {
			foreach ($xmlFiles as $xmlFile) {
				$this->parseXMLPriceFull($xmlFile);
			}
		}
		else if($fileType == 'PromoFull' || $fileType == 'Price') {
			foreach ($xmlFiles as $xmlFile) {
				$this->parseXMLPromo($xmlFile);
			}
		}
	}

	function extractAll() {
		$this->removeDuplicatesOlderFiles();
		$downloadedFiles = $this->pendingFiles('.gz');
		$this->runGzExtractionOnFilesArray($downloadedFiles);
	}

	function syncAll($fileType) {
		foreach ($this->URLS as $chainKey => $value) {
			$this->download($chainKey, $fileType);
		}
		$this->parseXML($fileType);
	}

}
