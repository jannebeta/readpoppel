<?php

require_once("configuration.php");

header('Content-Type: application/json');

$paperAmount = 8;

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('HTTP/1.1 401 Unauthorized');
	echo(json_encode(array('error' => 'authentication-needed')));
	exit();
}

if (isset($_GET["amount"]))
{
	if (!preg_match('/^\d+$/',$_GET["amount"]))
	{
	header('HTTP/1.0 400 Bad Request');
	echo(json_encode(array('error' => 'bad-request')));
	exit();
	}
	else
	{
		if (intval($_GET["amount"]) > 100)
		{
		$paperAmount = 100;
		}
		else
		{
			$paperAmount = intval($_GET["amount"]);
		}

	}

}
$randomPaperArray = array();

/*class RandomPaper
{
	private static $paperId;
	private static $paperBrand;
	private static $paperTitle;
}*/

$dataquery = "SELECT paperBrand, paperID, paperTitle FROM papers GROUP BY paperBrand ORDER BY rand() LIMIT $paperAmount";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows > 0)
{
	while($randomPapers = $dataresult->fetch_assoc())
	{
		array_push($randomPaperArray, array("paperBrand" => $randomPapers["paperBrand"], "paperId" => $randomPapers["paperID"], "paperTitle" => $randomPapers["paperTitle"]));

	}
}

echo json_encode($randomPaperArray, JSON_PRETTY_PRINT);
?>
