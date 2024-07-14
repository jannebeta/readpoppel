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

$dataquery = "SELECT * FROM papers_metadatas WHERE paperBrand = '" . mysqli_real_escape_string($dataconnection, $brandName) . "' AND paperId = '" . $paperId . "'";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows <= 0) 
{
	header("HTTP/1.0 404 Not Found");
	die("not found");
}


header('Content-Type: application/json');
 $metaList = "";
 $metacounter = 0;
   class PaperData {
      public $paperBrand = "";
      public $paperId  = "";
	  //public $additionalImageURLS = "";
	  //public $pageNumber = "";
	  //public $phrase_title = "";
	 // public $phrase_text = "";
   }
	while($paperSData = $dataresult->fetch_assoc()) 
	{
	
   $paper = new PaperData();
   $paper->paperBrand = $paperSData["paperBrand"];
   $paper->paperId  = $paperSData["paperId"];
   $paper->additionalImageURL  = $paperSData["additionalImageURL"];
  $paper->pageNumber = $paperSData["page_number"];
 $paper->phrase_title = utf8_encode($paperSData["phrase_title"]);
 $paper->phrase_text = utf8_encode($paperSData["phrase_text"]);
 $metaList[$metacounter] = $paper;
 $metacounter++;
   //echo json_encode($paper);
	}
	
	echo json_encode($metaList)
?>