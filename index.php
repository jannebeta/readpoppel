<?php
require_once("configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/subscription_block');
	exit;
}

include("includes/header.php");

?>
<script>
loadRandomPapers();
</script>
			<div class="jumbotron" style="background-image: url(/assets/images/illustrations/syksy002.jpg); background-size: 100%;">
			<a href="#" onclick="loadRandomPapers(); return false;">PÄIVITÄ</a>
				<h2 style="color: white;">
					Satunnaisia lehtiä
				</h2>
				<h4 style="color: white;">
			     satunnaisesti valikoituja lehtiä menneiltä vuosilta
				</h4>
				<p id="randomPapersContainer">
					<?php
/*
$dataquery = "SELECT DISTINCT paperBrand, paperID, paperTitle FROM papers ORDER BY rand() LIMIT 8";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows > 0)
{
	while($randomPapers = $dataresult->fetch_assoc())
	{
		echo '<a href="/paper/' . $randomPapers["paperBrand"] . '/read/' . $randomPapers["paperID"] . '"><img src="http://www.e-pages.dk/' . $randomPapers["paperBrand"] . '/' . $randomPapers["paperID"] . '/pic/tm1.jpg" style="height: 130px; margin-right: 20px;" onError="imageError(this);" id="randomPaper" data-toggle="tooltip" title="' . $randomPapers["paperTitle"] . '"></a>';
	}
}
*/
?>
				</p>
				<p>

				</p>
			</div>
		</div>
	</div>
	<h2>
					Lehdet
				</h2>
	<div class="row">
	<?php

$dataquery = "SELECT brandCode, brandName, logoIMG, platform, archived FROM paper_brands WHERE archived = '0' ORDER BY brandName ASC";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows > 0)
{
	while($papbrand = $dataresult->fetch_assoc())
	{
		switch ($papbrand["platform"])
		{
			case "visiolink_epages":
			{
				$paperResult = $dataconnection->query("SELECT * FROM papers WHERE paperBrand = '" . $papbrand["brandCode"] . "' ORDER BY paperPublished DESC LIMIT 1");
			$paperData = $paperResult->fetch_assoc();
	echo '<div class="col-lg-2 col-md-4 col-xs-6 thumb">
                <a class="thumbnail" href="/paper/' . $papbrand["brandCode"] . '">
				' . ($papbrand["archived"] == 1 ? '<div class="attention"><div class="rotation"><center>vain</center> arkisto</div></div>' : '') . '
                    <img class="img-responsive" style="height: 180px;" src="http://www.e-pages.dk/' . $paperData["paperBrand"] . '/' . $paperData["paperID"] . '/pic/tm1.jpg" onerror="imageError(this);" alt="">
			   </a>
            </div>';
			break;
			}
			case "readpoppel_envsafe":
			{
					$paperResult = $dataconnection->query("SELECT * FROM papers WHERE paperBrand = '" . $papbrand["brandCode"] . "' ORDER BY paperPublished DESC LIMIT 1");
			$paperData = $paperResult->fetch_assoc();

				echo '<div class="col-lg-2 col-md-4 col-xs-6 thumb">
                <a class="thumbnail" href="/paper/' . $papbrand["brandCode"] . '">
                    <img class="img-responsive" style="height: 180px;" src="" onerror="imageError(this);" alt="">
                </a>
            </div>';
				break;
			}

	}



	}
}
	?>
</div>

<?php
include("includes/footer.php");
?>
