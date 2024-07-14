<?php
// ReadPoppel - Meta Data Fetcher
// Started writing 24 january 2016
// (c) Juho Sulka 2015 - 2016

// get configuration file from main dir
include("configuration.php");

error_reporting(0);

$paperName = "iltasanomat";
$xml = simplexml_load_file("http://www.e-pages.dk/" . $paperName . "/100/calendar/demo");

$papCCnt = $xml->meta->catalogs;
//$papCnt = $dataconnection->query("SELECT paperID FROM papers WHERE paperBrand = 'pohjalainen'");


	foreach($xml->catalogs->catalog as $paper)
{  

// load xml with simplexml_load_file
$xml = simplexml_load_file("http://localhost/testprox.php?paperbrand=" . $paperName . "&paperId=" . $paper["id"]);

foreach($xml->articles->article as $meta)
{
	
	$dataconnection->query("INSERT INTO `papers_metadatas` (`paperBrand`, `paperId`, `additionalImageURL`, `page_number`, `phrase_title`, `phrase_text`) VALUES ('" . $paperName . "', '" . $paper["id"] . "', '" . $meta->images->image->medium . "', '" . $meta->page . "' , '" . utf8_decode($meta->titles->title) . "', '" . strip_tags(utf8_decode($meta->content)) . "')");
	
}

}

?>