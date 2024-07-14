<?php
require_once("../configuration.php");
$paper = $_GET["paper"];

$results = $dataconnection->prepare("SELECT paperID, paperPublished, paperTitle FROM papers WHERE paperBrand = '$paper' ORDER BY paperID DESC");
$results->execute(); //Execute prepared Query
$results->bind_result($paperID, $paperPublished, $paperTitle); //bind variables to prepared statement

header('Content-type: application/json');
$paperArray = array();

while($results->fetch()){ //fetch values

/*$sivu = file_get_contents('http://www.e-pages.dk/' . $paper . '/' . $paperID . '/demo/');
$secretKey = @explode("key4: '", $sivu)[1];
$secretKey = substr($secretKey, 0, 16);
*/
		array_push($paperArray, Array("id" => intval($paperID), "title" => $paperTitle, "published" => intval($paperPublished)));

}
	
		echo json_encode($paperArray);
?>

