<?php
require_once("configuration.php");

header("Content-Type: text/plain");

$dataresult = $dataconnection->query("SELECT paperID, paperBrand, pageCount FROM papers");

ob_start();

if ($dataresult->num_rows > 0) 
{
	
	while($paperData = $dataresult->fetch_assoc()) 
	{
    if ($paperData["pageCount"] > 0)
	{
		continue;
	}
	
$json_file = file_get_contents('http://device.e-pages.dk/market/get_item.php?apiversion=4&model=android&customer=' . $paperData["paperBrand"] . '&catalog=' . $paperData["paperID"]);

$pdat = json_decode($json_file);

$pageCount = $pdat->pages;

$dataconnection->query("UPDATE papers SET pageCount = '" . $pageCount . "' WHERE paperBrand = '" . $paperData["paperBrand"] . "' AND paperID = '" . $paperData["paperID"] . "';");

echo "Updated pageCount on " . $paperData["paperBrand"]  . " #" . $paperData["paperID"] . "\r\n";
ob_flush();
//$dataconnection->query("UPDATE papers SET pageCount = '" . $pageCount . "' WHERE paperBrand = '" . $paperData["paperBrand"] . "' AND paperID = '" . $paperData["paperID"] . "'");


}
}

ob_end_flush(); 

?>