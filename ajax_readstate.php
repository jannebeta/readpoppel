<?php

require_once("configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/subscription_block');
	exit;
}
header("Content-Type: text/plain");

if (!isset($_GET["paperBrand"]) || !isset($_GET["paperId"]) || !isset($_GET["pageId"]))
{
	echo "Pyyntöä ei voida toteuttaa, syy: puuttuvat parametrit. Vaadittavat parametrit: julkaisija, numero, nykyinen sivu.";
	exit;
}

$paperBrand = mysqli_real_escape_string($dataconnection, $_GET["paperBrand"]);
$paperId = intval($_GET["paperId"]);
$pageId = intval($_GET["pageId"]);

if ($pageId < 0)
{
	die("Virhe #1");
}
if ($paperId < 0)
{
	die("Virhe #2");
}

$dataquery = "SELECT * FROM papers WHERE paperBrand = '" . $paperBrand . "' AND paperId = '" . $paperId . "' LIMIT 1";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows <= 0) 
{
	header("HTTP/1.0 404 Not Found");
	die("paper data missing");
}

$searchCheck = $dataconnection->query("SELECT * FROM user_readhistory WHERE user_id = '1' AND paperBrand = '" . $paperBrand . "' AND paperId = '" . $paperId . "' LIMIT 1");

if ($searchCheck->num_rows <= 0) 
{
	echo "NEW";
	$dataconnection->query("INSERT INTO `user_readhistory` (`user_id`, `paperBrand`, `paperId`, `pagesReaded`, `timestamp_start`, `timestamp_end`, `open_count`) VALUES ('1', '" . $paperBrand . "', '" . $paperId . "', '1', '" . time() . "', '0', '1')");
}
else
{
	echo "EXIST";
}
//$dataconnection->query("INSERT INTO `papers_metadatas` (`paperBrand`, `paperId`, `additionalImageURL`, `page_number`, `phrase_title`, `phrase_text`) VALUES ('" . $paperName . "', '" . $paper["id"] . "', '" . $meta->images->image->medium . "', '" . $meta->page . "' , '" . utf8_decode($meta->titles->title) . "', '" . strip_tags(utf8_decode($meta->content)) . "')");


?>