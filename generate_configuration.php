<?php
require_once("configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/subscription_block");
}

$alku = true;
$numero = @$_GET["papid"];
$brand = @$_GET["papbrand"];

if (!isset($_GET["papbrand"]))
{
	die("ERROR: Paper brand not defined");
}
if (!isset($_GET["papid"]))
{
	die("ERROR: Paper id not defined.");
}
$numero = intval($numero);

header('Content-Type: text/xml');

$konfis = file_get_contents('http://www.e-pages.dk/' . $brand . '/' . $numero . '/demo/configuration/flash/v1/application/configuration.xml');
$konfis = str_replace("demoMode=\"true\"","demoMode=\"false\"",$konfis);
$konfis = str_replace("searchable=\"false\"","searchable=\"true\"",$konfis);
$konfis = str_replace("demo pageLimit=\"2\"","demo pageLimit=\"2000\"",$konfis);
$konfis = str_replace("demo pageLimit=\"3\"","demo pageLimit=\"3000\"",$konfis);
$konfis = str_replace("demo pageLimit=\"4\"","demo pageLimit=\"4000\"",$konfis);
$konfis = str_replace("demo pageLimit=\"5\"","demo pageLimit=\"5000\"",$konfis);
if ($brand != "pohjalainen" && $brand != "ilkka" && $brand != "savonsanomat" && $brand != "viiskunta" && $brand != "ylakainuu" && $brand != "komiatlehti")
{
	$konfis = str_replace("<map source=\"stat\" cluster=\"stat\"/>","<map source=\"stat\" cluster=\"stat\"/><map source=\"vector\" cluster=\"vector\"/>",$konfis);
	$konfis = str_replace("</clusters>","<cluster name=\"vector\">
<server priority=\"10\" key=\"key4\">
http://front.device.e-pages.dk/[key4]/" . $brand . "/" . $numero . "
</server>
</cluster></clusters>",$konfis);
}


echo $konfis;
?>