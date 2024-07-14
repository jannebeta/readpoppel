<?php
require_once("configuration.php");

header('Content-Type: application/json');

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('HTTP/1.1 401 Unauthorized');
	echo(json_encode(array('error' => 'authentication-needed')));
	exit();
}

if (!isset($_GET["paper"]) || !isset($_GET["searchKeyword"]))
{
	header('HTTP/1.0 400 Bad Request');
	echo(json_encode(array('error' => 'bad-request')));
	exit();
}
$page_number = intval(@$_GET["page"]);
$paper = mysqli_real_escape_string($dataconnection, @$_GET["paper"]);
$searchKeyword = @mysqli_real_escape_string($dataconnection, $_GET["searchKeyword"]);

if (strlen($searchKeyword) < 3)
{
	echo json_encode(array("error" => "too_short_searchquery"));
	//die("Liian lyhyt hakulauseke. Minimipituus 3 merkkiä.");
}
$results = $dataconnection->prepare("SELECT paperID, additionalImageURL, phrase_text, page_number, phrase_title FROM papers_metadatas WHERE paperBrand = '$paper' AND phrase_text LIKE '%$searchKeyword%' OR paperBrand = '$paper' AND phrase_title LIKE '%$searchKeyword%' ORDER BY paperID DESC LIMIT 100");

$results->execute();
$results->bind_result($paperID, $imageURL, $phrase, $pagenumber, $phrasetitle);

$searchResults = array();

while($results->fetch()){

 	array_push($searchResults, array("paperBrand" => $paper, "paperId" => $paperID , "pageNumber" => $pagenumber, "additionalImageUrl" => $imageURL, "phraseTitle" => utf8_encode($phrasetitle), "longPhrase" => utf8_encode($phrase)));

}

echo json_encode($searchResults, JSON_PRETTY_PRINT);

?>