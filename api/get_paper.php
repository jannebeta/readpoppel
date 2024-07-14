<?php
require_once("../configuration.php");
$paper = $_GET["paperName"];
$paperiid = intval($_GET["paperId"]);

$results = $dataconnection->prepare("SELECT paperID, paperPublished, paperTitle FROM papers WHERE paperBrand = '$paper' and paperId = '$paperiid' LIMIT 1");
$results->execute(); //Execute prepared Query
$results->bind_result($paperID, $paperPublished, $paperTitle); //bind variables to prepared statement

$xml = new SimpleXMLElement('<paper/>');

while($results->fetch()){ //fetch values

$sivu = file_get_contents('http://www.e-pages.dk/' . $paper . '/' . $paperID . '/demo/');
$secretKey = @explode("key4: '", $sivu)[1];
$secretKey = substr($secretKey, 0, 16);

		$xml->addChild('id', $paperID);
		$xml->addChild('title',  $paperTitle);
		$xml->addChild('published', date('j.n.Y', $paperPublished));
		$xml->addChild('secretKey',  $secretKey);
		
		//$paging = $xml->addChild('pages');
		
		$xmla = simplexml_load_file("http://www.e-pages.dk/" . $paper . "/" . $paperID . "/demo/configuration/flash/v1/catalog/sections.xml");

foreach($xmla->section as $paperi)
{

	 $pages = count($paperi->pages->page);
$xml->addChild('pageCount',  $pages);
 /*  foreach($pages as $page)
{
	if ($page["page_no"] == "")
	{
		continue;
	}
$sivu = $paging->addChild('page');
$sivu->addAttribute('id', $page["page_no"]);
$sivu->addChild('textVector', $page->vector);
$sivu->addChild('bgImage', $page["bgimage"]);

}
*/
}

}
	Header('Content-type: text/xml');
		print($xml->asXML());
?>

