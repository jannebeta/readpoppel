<?php
require_once("configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/subscription_block');
	exit;
}

include("includes/header.php");

?>
	<div class="container">

	
	<div class="row">
	<?php

$dataquery = "SELECT brandCode, brandName, logoIMG FROM paper_brands ORDER BY brandName ASC";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows > 0) 
{
	while($papbrand = $dataresult->fetch_assoc()) 
	{
			$paperResult = $dataconnection->query("SELECT * FROM papers WHERE paperBrand = '" . $papbrand["brandCode"] . "' ORDER BY paperPublished DESC LIMIT 1");
			$paperData = $paperResult->fetch_assoc();
	echo '<div class="col-lg-2 col-md-4 col-xs-6 thumb">
                <a class="thumbnail" href="/paper/' . $paperData["paperBrand"] . '">
                    <img class="img-responsive" style="height: 180px;" src="http://www.e-pages.dk/' . $paperData["paperBrand"] . '/' . $paperData["paperID"] . '/pic/tm1.jpg" onerror="imageError(this);" alt="">
                </a>
            </div>';
		
	
	}
}
	?>
</div>
</div>
<?php
include("includes/footer.php");
?>