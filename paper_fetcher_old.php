<?php
/* ReadPoppel - Paper fetching script */
require_once("configuration.php");

set_time_limit(0);

$dataquery = "SELECT brandCode FROM paper_brands";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows > 0) 
{
	while($papbrand = $dataresult->fetch_assoc()) 
	{
		
$xml = simplexml_load_file("http://www.e-pages.dk/" . $papbrand["brandCode"] . "/100/calendar/demo");

$papCCnt = $xml->meta->catalogs;
$papCnt = $dataconnection->query("SELECT paperID FROM papers WHERE paperBrand = '" . $papbrand["brandCode"] . "'");

if ($papCnt->num_rows < $papCCnt)
{
	$lastPap = $dataconnection->query("SELECT paperID FROM papers WHERE paperBrand = '" . $papbrand["brandCode"] . "' ORDER BY paperID DESC LIMIT 1")->fetch_object()->paperID;
	foreach($xml->catalogs->catalog as $paper)
{   
    if ($lastPap >= $paper["id"])
	{
		continue;
	}
	
	
	$dataconnection->query("INSERT INTO `papers` (`paperBrand`, `paperID`, `paperPublished`, `paperTitle`) VALUES ('" . $papbrand["brandCode"] . "', '" . $paper["id"] . "', '" . strtotime($paper["published"]) . "', '" . $paper->title . "')");
	
	// load xml with simplexml_load_file
//$xml = simplexml_load_file("http://www.e-pages.dk/" . $papbrand["brandCode"] . "/" . $paper["id"] . "/article/meta");

//foreach($xml->pages->page as $meta)
//{
//	echo "sivu: " . $meta["page_no"] . " otsikko: " . $meta["title"] . "<br>otsikko: " . $meta->article->title . "<br>" . "<br>kuvaus: " . $meta->article->teaser . "<br>kuva: <img src='" . $meta->article->images->image->medium . "'>";
	
//	$dataconnection->query("INSERT INTO `papers_metadatas` (`paperBrand`, `paperId`, `phrase_type`, `additionalImageURL`, `page_number`, `phrase_text`) VALUES ('" . $papbrand["brandCode"] . "/', '" . $paper["id"] . "', 'TITLE', '" . $meta->article->images->image->medium . "', '" . $meta["page_no"] . "' , '" . utf8_decode($meta->article->title) . "')");
	
//	$dataconnection->query("INSERT INTO `papers_metadatas` (`paperBrand`, `paperId`, `phrase_type`, `additionalImageURL`, `page_number`, `phrase_text`) VALUES ('" . $papbrand["brandCode"] . "/', '" . $paper["id"] . "', 'DESCRIPTION', '" . $meta->article->images->image->medium . "', '" . $meta["page_no"] . "' , '" . utf8_decode($meta->article->teaser) . "')");
	
//}
	
}
}
	}
}
	
	$dataconnection->close();

?>