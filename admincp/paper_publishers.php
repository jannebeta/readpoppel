<?php
require_once("../configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/subscription_block');
	exit;
}

if (!$_SESSION["IS_ADMIN"])
{
	header('Location: http://' . $_SERVER['SERVER_NAME'] . '/');
	exit;
}

include("../includes/header.php");

?>
		
<div class="container">
  <h2>Lehtijulkaisijat</h2>
  <p>Tältä sivulta näet kaikki LukuPoppeliin tuodut lehdet, voit muokata ja lisätä niitä myös täältä.</p>
  <table class="table table-hover">
    <thead>
      <tr>
        <th># ID-numero</th>
        <th>Lehden nimi</th>
        <th>Korostusvärit</th>
		 <th>Lehtiä tuotu</th>
		  <th>Viimeisin lehti</th>
		  <th>Toiminnot</th>
      </tr>
    </thead>
    <tbody>
	<?php

$dataquery = "SELECT * FROM paper_brands ORDER BY id";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows > 0) 
{
	while($papbrand = $dataresult->fetch_assoc()) 
	{
			$paperResult = $dataconnection->query("SELECT * FROM papers WHERE paperBrand = '" . $papbrand["brandCode"] . "' ORDER BY paperPublished DESC LIMIT 1");
			$paperData = $paperResult->fetch_assoc();
			
			$paperCount = $dataconnection->query("SELECT COUNT(paperID) FROM papers WHERE paperBrand = '" . $papbrand["brandCode"] . "'"); 
			$del = $dataconnection->query("SELECT COUNT(*) FROM papers WHERE paperBrand = '" . $papbrand["brandCode"] . "'");
			$count = $del->fetch_row()[0];
			echo '<tr>
        <td>' . $papbrand["id"] . '</td>
        <td>' . utf8_encode($papbrand["brandName"]) . '</td>
        <td><span style="color: white; background-color: ' . $papbrand["secondColor"] . '; -webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px; padding: 5px;">' . date('j.n.Y') . '</span></td>
<td>' . $count . '</td>
<td>' . ($paperData["paperPublished"] > 0 ? date('j.n.Y', $paperData["paperPublished"]) : '-') . '</td>
<td><button type="button" class="btn btn-primary btn-xs">Siirry</button><button type="button" class="btn btn-success btn-xs">Muokkaa</button><button type="button" class="btn btn-danger btn-xs">Poista käytöstä</button></td>
      </tr>';
	
		
	
	}
}
	?>
    </tbody>
  </table>
</div>

<?php
include("../includes/footer.php");
?>