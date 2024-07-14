<?php
require_once("configuration.php");
?>
<html>

<head>

<title>Näköislehdet ILMAISEKSI!</title>

<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>

<style>
body
{
	background-image: url('/img/textured_paper.png');
	font-family: 'Open Sans Condensed', sans-serif;
}
</style>
<body>
<div style="position: relative; left: 30%; top: 10%; padding-left: 10px; padding-top: -5px; width: 600px; height: 10px;">
<h3>Valitse näköislehti, jota haluat lukea:</h3>
</div>
<div style="position: relative; left: 30%; top: 12%; padding-left: 10px; padding-top: -5px; width: 600px; height: 800px; -webkit-column-count: 2;">
<?php

$dataquery = "SELECT brandCode, brandName, logoIMG FROM paper_brands";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows > 0) 
{
	while($papbrand = $dataresult->fetch_assoc()) 
	{
		echo '<p><a href="/paper/' . $papbrand["brandCode"] . '" title="' . $papbrand["brandName"] . '"><img src="/img/' . $papbrand["logoIMG"] . '"></a></p>';
	}
}
	?>
</div>

</body>

</html>