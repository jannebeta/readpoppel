<?php
require_once("configuration.php");

header('Content-Type: application/json');

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('HTTP/1.1 401 Unauthorized');
	echo(json_encode(array('error' => 'authentication-needed')));
	exit();
}

$page_number = intval(@$_POST["page"]);
$paper = @mysqli_real_escape_string($dataconnection, $_POST["paper"]);
$searchKeyword = @mysqli_real_escape_string($dataconnection, $_POST["searchKeyword"]);

if (strlen($searchKeyword) < 3)
{
	header('HTTP/1.1 400 Bad request');
	echo(json_encode(array('error' => 'search-query', 'reason' => 'Liian lyhyt hakulauseke. Minimipituus 3 merkkiä.')));
	exit();
}
$results = $dataconnection->prepare("SELECT paperID, additionalImageURL, phrase_text, page_number, phrase_title FROM papers_metadatas WHERE paperBrand = '$paper' AND phrase_text LIKE '%$searchKeyword%' OR paperBrand = '$paper' AND phrase_title LIKE '%$searchKeyword%' ORDER BY paperID DESC LIMIT 100");

$results->execute();
$results->bind_result($paperID, $imageURL, $phrase, $pagenumber, $phrasetitle);

$rounder = 0;
$searchResults = Array();
while($results->fetch()){



$locaet = strpos($phrase, $searchKeyword) - 20;

$searchResults[] = array("paperId" => $paperID, "pageNumber" => $pagenumber, "teaserImage" => $imageURL, "phraseTitle" => utf8_encode($phrasetitle), "phraseText" => utf8_encode(strip_tags($phrase)));

}
$pageArray = Array("paperBrand" => $paper, "searchKeyword" => $searchKeyword, "results" => $searchResults);

	echo json_encode($pageArray, JSON_PRETTY_PRINT);

?>
