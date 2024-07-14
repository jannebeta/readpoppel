<?php
require_once("configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/subscription_block");
}

$page_number = intval(@$_POST["page"]);
$paper = mysqli_real_escape_string($dataconnection, $_POST["paper"]);
$searchKeyword = @mysqli_real_escape_string($dataconnection, $_POST["searchKeyword"]);

if (strlen($searchKeyword) < 3)
{
	die("Liian lyhyt hakulauseke. Minimipituus 3 merkkiä.");
}
$results = $dataconnection->prepare("SELECT paperID, additionalImageURL, phrase_text, page_number, phrase_title FROM papers_metadatas WHERE paperBrand = '$paper' AND phrase_text LIKE '%$searchKeyword%' OR paperBrand = '$paper' AND phrase_title LIKE '%$searchKeyword%' ORDER BY paperID DESC LIMIT 100");

$results->execute();
$results->bind_result($paperID, $imageURL, $phrase, $pagenumber, $phrasetitle);

$rounder = 0;
while($results->fetch()){



if ($rounder == 5)
{
	echo '<div class="row">';
}

$locaet = strpos($phrase, $searchKeyword) - 20;
  
echo '<div class="col-lg-2 col-md-4 col-xs-6 thumb" style="margin-bottom: -99999px;
  padding-bottom: 99999px;">
                <a class="thumbnail" href="/paper/' . $paper . '/read/' . $paperID . '?page=' . $pagenumber . '">
                    <img class="img-responsive" style="height: 180px;" src="' . ($imageURL != "" ? $imageURL : "/assets/images/nophoto.png") . '" alt="">
                </a>
				<b>' . utf8_encode($phrasetitle) . '</b>
				' . utf8_encode(preg_replace("/\w*?$searchKeyword\w*/i", "<b style=\"background-color: yellow;\">$0</b>", substr($phrase, 0, 160) . (strlen($phrase) > 160 ? '...' : ''))) . '<br>
            </div>';
	
	
	if ($rounder == 5)
{
	echo '</div>';
	$rounder = 0;
}
else
{
	$rounder++;
}
}


?>