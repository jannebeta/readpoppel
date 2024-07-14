<?php
require_once("../configuration.php");


$dataquery = "SELECT id, brandCode, brandName, logoIMG, mainColor, secondColor FROM paper_brands";

$dataresult = $dataconnection->query($dataquery);

//$xml = new SimpleXMLElement('<newspapers/>');

header('Content-type: application/json');
$brandArray = array();

if ($dataresult->num_rows > 0) 
{
	while($papbrand = $dataresult->fetch_assoc()) 
	{
		array_push($brandArray, Array("id" => intval($papbrand["id"]), "machineCode" => $papbrand["brandCode"], "title" => utf8_encode($papbrand["brandName"])));
		/*$publish = $xml->addChild('newspaper');
		$publish->AddAttribute('id', $papbrand["id"]);
		$publish->addChild('code', $papbrand["brandCode"]);
		$publish->addChild('title', utf8_encode($papbrand["brandName"]));
		$publish->addChild('logo_url', 'http://api.lukupoppeli.fi/img/' . $papbrand["logoIMG"]);
		$paperLa = $dataconnection->query("SELECT paperID FROM papers WHERE paperBrand = '" . $papbrand["brandCode"] . "' ORDER BY paperPublished DESC LIMIT 1");
		$latestPaper = $paperLa->fetch_assoc();
		$paperid = $latestPaper['paperID'];
		$publish->addChild('latest_paper_id', $paperid);
		$publish->addChild('mainColor', "0x" . explode('#', $papbrand["mainColor"])[1]);
		$publish->addChild('secondColor', $papbrand["secondColor"]);
		*/
	}
}

		echo json_encode($brandArray);
	
	?>
