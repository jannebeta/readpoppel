<?php


if (!ipOverrideStatus("127.0.4.1"))
{
	echo "ip ei löydy listasta";
}

function ipOverrideStatus($requestIpAddress)
{

// checking override ips

$addressFile = file_get_contents("ipoverride.txt");

$addresses = preg_split('/\r\n|\n|\r/', trim($addressFile));

foreach ($addresses as $ip)
{
if ($ip == $requestIpAddress)
{
	
	// match - return true!
	
	return true;
}
}

  // no matches - return false

	return false;

}

?>