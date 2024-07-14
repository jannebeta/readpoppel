<?php

require_once("configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/subscription_block");
}

if (!isset($_GET["brandName"]) || !isset($_GET["paperId"]))
{
	die("ReadPoppel v2 - Missing arguments!");
}

$brandName = $_GET["brandName"];
$paperId = intval($_GET["paperId"]);

$dataquery = "SELECT * FROM papers WHERE paperBrand = '" . mysqli_real_escape_string($dataconnection, $brandName) . "' AND paperId = '" . $paperId . "' LIMIT 1";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows <= 0) 
{
	header("HTTP/1.0 404 Not Found");
	die("paper data missing");
}

$paperSData = $dataresult->fetch_assoc();

header('Content-Type: application/json');

   class PaperData {
      public $brandSystemName = "";
      public $brandMarketingName  = "";
      public $paperNumber = "";
	  public $paperPublished = "";
	  public $paperPageCount = "";
	  public $paperTitle = "";
   }
	
   $paper = new PaperData();
   $paper->brandSystemName = $paperSData["paperBrand"];
   $paper->brandMarketingName  = ucfirst($paperSData["paperBrand"]);
   $paper->paperNumber  = $paperSData["paperID"];
   $paper->paperPublished = $paperSData["paperPublished"];
   $paper->paperPageCount = $paperSData["pageCount"];
	$paper->paperTitle = $paperSData["paperTitle"];
   echo json_encode($paper);
?>