<?php
/* ReadPoppel - Paper frontpage ripper */
require_once("configuration.php");

set_time_limit(0);

$dataquery = "SELECT paperID, paperBrand FROM papers";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows > 0) 
{

	while($papbrand = $dataresult->fetch_assoc()) 
	{
	
if (!file_exists('assets/images/frontpage_thumbs/' . $papbrand["paperBrand"])) {
	
    mkdir('assets/images/frontpage_thumbs/' . $papbrand["paperBrand"], 0777, true);
	
}
	file_put_contents("assets/images/frontpage_thumbs/" . $papbrand["paperBrand"] . "/" . $papbrand["paperID"] . ".jpg", fopen("http://www.e-pages.dk/" . $papbrand["paperBrand"] . "/" . $papbrand["paperID"] . "/pic/tm1.jpg", 'r'));

}
	}

	
	$dataconnection->close();

?>