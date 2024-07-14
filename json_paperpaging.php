<?php
require_once("configuration.php");

header('Content-Type: application/json');


if (!isset($_SESSION["LOGIN_USER"]))
{
	header('HTTP/1.1 401 Unauthorized');
	echo(json_encode(array('error' => 'authentication-needed')));
	exit();
}

$page_number = intval($_POST["page"]);
$paper = mysqli_real_escape_string($dataconnection, $_POST["paper"]);
$paperYear = mysqli_real_escape_string($dataconnection, @$_POST["paperPublishYear"]);
$paperMonth = mysqli_real_escape_string($dataconnection, @$_POST["paperPublishMonth"]);
$paperDay = mysqli_real_escape_string($dataconnection, @$_POST["paperPublishDay"]);
$dateLimiter = "";
$dateLimiter2 = "";
$sqllimiter = "";

if (isset($paperYear) && !isset($paperMonth) && !isset($paperDay))
{
	$dateLimiter = strtotime('01-01-' . $paperYear);
	$dateLimiter2 = strtotime('01-01-' . ($paperYear + 1));
}
else if (isset($paperYear) && isset($paperMonth) && !isset($paperDay))
{
	$dateLimiter = strtotime('01-' . $paperMonth . '-' . $paperYear);
	$dateLimiter2 = strtotime('01-' . ($paperMonth + 1) . '-' . $paperYear);
}
else if (isset($paperYear) && isset($paperMonth) && isset($paperDay))
{
	$dateLimiter = strtotime($paperDay . '-' . $paperMonth . '-' . $paperYear);
	$dateLimiter2 = @strtotime(($paperDay + 1) . '-' . $paperMonth . '-' . $paperYear);
	$sqllimiter = "LIMIT 1";
}
$position = ($page_number * 20);
$lastPap = $dataconnection->query("SELECT paperID FROM papers WHERE paperBrand = '$paper' ORDER BY paperID DESC LIMIT 1")->fetch_object()->paperID;
$secondCOLOR = $dataconnection->query("SELECT * FROM paper_brands WHERE brandCode = '$paper' LIMIT 1")->fetch_object()->secondColor;

if ($dateLimiter != "")
{
	$results = $dataconnection->prepare("SELECT paperID, paperPublished, paperTitle FROM papers WHERE paperBrand = '$paper' AND paperPublished BETWEEN $dateLimiter AND '$dateLimiter2' ");
}
else
{
	$results = $dataconnection->prepare("SELECT paperID, paperPublished, paperTitle FROM papers WHERE paperBrand = '$paper' ORDER BY paperID DESC LIMIT $position, 24");
}

$results->execute(); //Execute prepared Query
$results->bind_result($paperID, $paperPublished, $paperTitle); //bind variables to prepared statement

$pageItems = Array();

while($results->fetch()){ //fetch values

$pageItems[] = array("id" => $paperID, "published" => $paperPublished);

  /*  echo '<a class="paperbutton" style="text-decoration: none; color: white;" href="/paper/' . $paper . '/read/' . $paperID . '" title="' . $paperTitle . '" data-toggle="popover" data-trigger="hover" data-content="Klikkaa lehteä lukeaksesi sitä."><img id="classi" class="paperimage" src="http://www.e-pages.dk/' . $paper . '/' . $paperID . '/pic/tm1.jpg" border="1">
       ' . ($paperID == $lastPap ? '<div style="position:relative;
    text-align:center; height: 30px;  top: -125px; background-color: ' . $secondCOLOR . '; font-size: 20px; opacity: 0.95;">uusin</div>' : '<div style="position:relative;
    text-align:center; height: 30px;  top: -125px; background-color: ' . $secondCOLOR . '; font-size: 20px; opacity: 0.95; text-decoration: none;">' . date('j.n.Y', $paperPublished) . '</div>') . '
    </a>';
	*/


	/*echo '<div class="col-lg-2 col-md-4 col-xs-6 thumb">
                <a class="thumbnail" href="/paper/' . $paper . '/read/' . $paperID . '" style="text-decoration: none;">
                    <img class="img-responsive" src="http://www.e-pages.dk/' . $paper . '/' . $paperID . '/pic/tm1.jpg" onerror="imageError(this);" alt=""><div style="font-family: Open Sans Condensed; font-weight: 300; position:relative;
    text-align:center; height: 30px;  width: 100px; left: 42px; top: -125px; background-color: ' . $secondCOLOR . '; font-size: 20px; opacity: 0.95; color: white; -webkit-border-radius: 6px;
-moz-border-radius: 6px;
border-radius: 6px;">' . date('j.n.Y', $paperPublished) . '</div>

                </a>
            </div>';
						*/
}

$pageArray = Array("paperBrand" => $paper, "page" => $page_number, "brandColor" => $secondCOLOR, "lastPaper" => $lastPap, "items" => $pageItems);

	echo json_encode($pageArray, JSON_PRETTY_PRINT);

/*$kohta = $maara-$position;
for ($i = $kohta ; $i >= $kohta-19; $i--) {

if ($i <= 0)
{
	continue;
}

    echo '<a class="paperbutton" href="/paper/' . $brandCODE . '/read/' . $i . '" title="' . $brandNAME . ' (nro. ' . $i . ')" data-toggle="popover" data-trigger="hover" data-content="Klikkaa lehteä lukeaksesi sitä."><img id="classi" class="paperimage" src="http://www.e-pages.dk/' . $brandCODE . '/' . $i . '/pic/tm1.jpg" border="1">
       ' . ($maara == $i ? '<div class="paperheader" style="background-color: ' . $secondCOLOR . ';">uusin</div>' : "") . '
    </a>';
}
*/
?>
