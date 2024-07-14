<?php
require_once("configuration.php");

$_BRANDFP = true;

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('Location: ' . $SITE_URL . '/subscription_block');
	exit;
}

$brand = @$_GET["papbrand"];
if (!isset($_GET["papbrand"]))
{
	die("ERROR: Paper brand not defined");
}

$papCnt = $dataconnection->query("SELECT paperID FROM papers WHERE paperBrand = '$brand'");
$pages = $papCnt->num_rows;
$dataquery = "SELECT brandCode, mainColor, secondColor, brandName, logoIMG, archived FROM paper_brands WHERE brandCode = '" . mysqli_real_escape_string($dataconnection, $brand) . "' LIMIT 1";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows <= 0)
{
	die("paper data missing");
}

$brandCODE = "UNDEFINED";
$brandNAME = "UNDEFINED";
$mainCOLOR = "UNDEFINED";
$secondCOLOR = "UNDEFINED";
$logoIMG = "UNDEFINED";
$archived = 0;

	while($papbrand = $dataresult->fetch_assoc())
	{
		$brandCODE = $papbrand["brandCode"];
		$brandNAME = $papbrand["brandName"];
		$mainCOLOR = $papbrand["mainColor"];
		$secondCOLOR = $papbrand["secondColor"];
		$logoIMG = $papbrand["logoIMG"];
		$archived = $papbrand["archived"];
	}
	$dateresult = $dataconnection->query("SELECT paperPublished FROM papers WHERE paperBrand = '" . mysqli_real_escape_string($dataconnection, $brand) . "' ORDER BY paperPublished LIMIT 1");
	$row = $dateresult->fetch_assoc();


include("includes/header.php");

?>
<script>
$(document).ready(function(){
   loadFirstPage();
    $('.search-panel .dropdown-menu').find('a').click(function(e) {
		e.preventDefault();
		var param = $(this).attr("href").replace("#","");
		var concept = $(this).text();
		$('.search-panel span#search_concept').text(concept);
		$('.input-group #search_param').val(param);
	});
   $( "#searchBut" ).click(function() {
  $("#results").html("<div class=\"col-xs-6 col-xs-offset-3\"><h2><i>Haetaan..</i></h2></div>");

   $.post('/json_searchpapers.php',{'paper':'<?php echo $brandCODE; ?>', 'searchKeyword': document.getElementsByName('searchBox')[0].value }, function(data) {
		 $("#results").empty();

var searchResults = JSON.parse(JSON.stringify(data));
var papBrand = searchResults.paperBrand;

for (i = 0; i < searchResults.results.length; i++)
{
  //$("#results").append(pageData.items[i].id);
  var paperId = searchResults.results[i].paperId;
	var pNumber = searchResults.results[i].pageNumber;
  var paperId = searchResults.results[i].paperId;
	var phraseTitle = searchResults.results[i].phraseTitle;
	var phraseText = searchResults.results[i].phraseText;
	var teaserImage = searchResults.results[i].teaserImage;

if (teaserImage == "")
{
	teaserImage = "/assets/images/nophoto.png";
}

  $("#results").append('<div class="col-lg-2 col-md-4 col-xs-6 thumb" style="margin-bottom: -99999px; padding-bottom: 99999px;"><a class="thumbnail" href="/paper/' + papBrand + '/read/' + paperId + '?page=' + pNumber + '"><img class="img-responsive" style="overflow: hidden;" src="' + teaserImage + '" alt="Uutiskuva"></a><b>' + phraseTitle + '</b><br><p class="phraseText" style="height: 100px; overflow: hidden;">' + phraseText + '</p></div>');
}


	 })

});

});
</script>

<h2 style="color: <?php echo $mainCOLOR; ?>; text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;">
					<?php echo utf8_encode($brandNAME); ?>
				</h2>
				<?php if ($archived) {
					echo "<p><i>Tämä lehtijulkaisija on arkistoitu. Uusia lehtiä ei enää ole tulossa ja aiemmat eivät ole mahdollisesti luettavissa. Julkaisija on vaihtanut alustaa.</i></p>";
				}
				?>
				  <div class="col-xs-5 col-xs-offset-3">
				  <div class="input-group">
              <!--  <div class="input-group-btn search-panel">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    	<span id="search_concept">Suodata</span> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a href="#contains">Otsikko</a></li>
                      <li><a href="#its_equal">Sisältö</a></li>
                    </ul>
                </div>
			-->
                <input type="hidden" name="search_param" value="all" id="search_param">
                <input type="text" class="form-control" name="searchBox" placeholder="Etsitkö jotain? Kirjoita se tähän">
                <span class="input-group-btn">
                    <button id="searchBut" class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
                </span>
            </div>
			</div>
			<br><br>
			<?php
			if (isset($_GET["error"]))
			{
				$currentErr = "";

				switch (intval($_GET["error"]))
				{
					case 1:

						$currentErr = "<strong>Ounou!</strong> Lehtitietojen lataaminen epäonnistui. Ole hyvä ja yritä myöhemmin uudelleen.";
						break;


					default:


					$currentErr = "Mitä sinä yrität!?";

					break;
				}
				echo '<div class="alert alert-danger">
 ' . $currentErr . '
</div>';
			}
			?>
	<div class="row" id="results">	<a href="#" onclick="loadFirstPage(); return false;"></a>

</div>
</div>

<?php
include("includes/footer.php");
?>
