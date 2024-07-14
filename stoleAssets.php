<?php
// Spoofing proxy :O

if (!isset($_GET["paperBrand"]) || !isset($_GET["paperId"]) || !isset($_GET["pageNumber"]))
{
	die("403 - Forbidden");
}

$cacheEnabled = false;

$filetype = explode('.', $_GET["pageNumber"])[1];

if ($cacheEnabled)
{
	// UUSI CACHETOIMINTO!
	
		if (!file_exists($cacheFolder . $_GET["paperBrand"])) {
    mkdir($cacheFolder . $_GET["paperBrand"], 0777);
		}
			if (!file_exists($cacheFolder . $_GET["paperBrand"] . "/" . $_GET["paperId"])) {
    mkdir($cacheFolder . $_GET["paperBrand"] . "/" . $_GET["paperId"], 0777);
		}
	if (file_exists($cacheFolder . $_GET["paperBrand"] . "/" . $_GET["paperId"] . "/" . $_GET["pageNumber"])) {
		
		if ($filetype == "vec")
{
header("Access-Control-Allow-Origin:*"); 
header("Connection:keep-alive"); 
header("Content-Encoding:gzip"); 
header("Content-Type:application/octet-stream"); 
echo gzencode(file_get_contents($cacheFolder . $_GET["paperBrand"] . "/" . $_GET["paperId"] . "/" . $_GET["pageNumber"]));
//header("Transfer-Encoding:chunked"); 
}
else
{
	header("Content-Type: image/jpeg");
	echo file_get_contents($cacheFolder . $_GET["paperBrand"] . "/" . $_GET["paperId"] . "/" . $_GET["pageNumber"]);
}
	
	exit();
	}
	
}
$url = "http://device.e-pages.dk/data/" . $_GET["paperBrand"] . "/" . intval($_GET["paperId"]) . "/vector/" . $_GET["pageNumber"];

$options = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"Accept-Encoding: identity\r\nUser-Agent: Dalvik/1.6.0 (Linux; U; Android 4.3; VMware Virtual Platform Build/JSS15J)\r\nHost: device.e-pages.dk\r\n" 
  )
);

$context = stream_context_create($options);
$file = @file_get_contents($url, false, $context);


if ($file == null)
{
	header("HTTP/1.0 404 Not Found");
	echo "404 - Page not found";
	exit;
}

if ($filetype == "vec")
{
header("Access-Control-Allow-Origin:*"); 
header("Connection:keep-alive"); 
header("Content-Encoding:gzip"); 
header("Content-Type:application/octet-stream"); 
//header("Transfer-Encoding:chunked"); 
}
else
{
	header("Content-Type: image/jpeg");
}

if ($cacheEnabled)
	{
	$paperFile = fopen($cacheFolder . $_GET["paperBrand"] . "/" . $_GET["paperId"] . "/" . $_GET["pageNumber"], "w") or die("Unable to open file!");
	fwrite($paperFile, $file);
	fclose($paperFile);
	}
	
if ($filetype == "vec")
{
echo gzencode($file);
}
else
{
echo $file;	
}
?>