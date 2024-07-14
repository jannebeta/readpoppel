<?php
/* ReadPoppel - Paper fetching script */
error_reporting(0);
set_time_limit(0);

header("Content-Type: text/plain");

$xml = simplexml_load_file("http://www.e-pages.dk/pohjalainen/100/calendar/demo");

$papCCnt = $xml->meta->catalogs;
//$papCnt = $dataconnection->query("SELECT paperID FROM papers WHERE paperBrand = 'pohjalainen'");


	foreach($xml->catalogs->catalog as $paper)
{   
/*if ($papbrand["brandCode"] == "pohjalainen" && $papbrand["brandCode"] == "ilkka")
{
	continue;
}
*/
if ($paper["id"] <= 1794)
{
	continue;
}
	// load xml with simplexml_load_file
$xml2 = simplexml_load_file("http://www.e-pages.dk/pohjalainen/" . $paper["id"] . "/article/meta");

foreach($xml2->pages->page as $meta)
{
	echo "INSERT INTO `papers_metadatas` (`paperBrand`, `paperId`, `additionalImageURL`, `page_number`, `phrase_title`, `phrase_text`) VALUES ('pohjalainen', '" . $paper["id"] . "', '" . $meta->article->images->image->medium . "', '" . $meta["page_no"] . "' , '" . mysql_real_escape_string($meta->article->title) . "', '" . mysql_real_escape_string($meta->article->teaser) . "'),\r";
	//$dataconnection->query("INSERT INTO `papers_metadatas` (`paperBrand`, `paperId`, `additionalImageURL`, `page_number`, `phrase_title`, `phrase_text`) VALUES ('pohjalainen', '" . $paper["id"] . "', '" . $meta->article->images->image->medium . "', '" . $meta["page_no"] . "' , '" . utf8_decode($meta->article->title) . "', '" . utf8_decode($meta->article->teaser) . "')");
	
	
}
	

}
	

	
//	$dataconnection->close();

?>