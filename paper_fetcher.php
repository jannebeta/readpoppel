<?php
/* ReadPoppel - Paper fetching script

Uusi versio (v2 - 11.10.2016)

Muutosloki:
----------------

Lisätty datakyselyyn parametrit archieved ja platform
Lisätty sivunumerointi
Lisätty metakeräys

 */

require_once("configuration.php");
error_reporting(0);
set_time_limit(0);

$dataquery = "SELECT brandCode FROM paper_brands WHERE archived = '0' AND platform = 'visiolink_epages'"; // PÄIVITYS: Ei tarkisteta arkistoituja lehtiä (niitä ei ole tulossa enää lisää!) ja vain ePagesin lehdet käydään läpi

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows > 0)
{
	while($papbrand = $dataresult->fetch_assoc())
	{

$xml = simplexml_load_file("http://www.e-pages.dk/" . $papbrand["brandCode"] . "/100/calendar/demo"); // Haetaan lehtien numerolistaus kalenteritiedostosta

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

	$json_file = file_get_contents('http://device.e-pages.dk/market/get_item.php?apiversion=4&model=android&customer=' . $papbrand["brandCode"] . '&catalog=' . $paper["id"]);

$pdat = json_decode($json_file);

$pageCount = $pdat->pages;

echo "Adding new paper #" . time() . "<br>";
	$dataconnection->query("INSERT INTO `papers` (`paperBrand`, `paperID`, `paperPublished`, `paperTitle`, `pageCount`) VALUES ('" . $papbrand["brandCode"] . "', '" . $paper["id"] . "', '" . strtotime($paper["published"]) . "', '" . $paper->title . "', '" . $pageCount . "')");



	// load xml with simplexml_load_file
	$xml = simplexml_load_file("http://www.e-pages.dk/" . $papbrand["brandCode"] . "/" . $paper["id"] . "/article/meta");

foreach($xml->pages->page as $meta)
{


	$dataconnection->query("INSERT INTO `papers_metadatas` (`paperBrand`, `paperId`, `phrase_title`, `additionalImageURL`, `page_number`, `phrase_text`) VALUES ('" . $papbrand["brandCode"] . "', '" . $paper["id"] . "', '" . utf8_decode($meta->article->title) . "', '" . $meta->article->images->image->medium . "', '" . $meta["page_no"] . "' , '" . utf8_decode($meta->article->teaser) . "')");

	//$dataconnection->query("INSERT INTO `papers_metadatas` (`paperBrand`, `paperId`, `phrase_type`, `additionalImageURL`, `page_number`, `phrase_text`) VALUES ('" . $papbrand["brandCode"] . "/', '" . $paper["id"] . "', 'DESCRIPTION', '" . $meta->article->images->image->medium . "', '" . $meta["page_no"] . "' , '" . utf8_decode($meta->article->teaser) . "')");

}

}
}
	}
}

	$dataconnection->close();

?>
