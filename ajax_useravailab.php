<?php
require_once("configuration.php");
$username = mysqli_real_escape_string($dataconnection, @$_POST["username"]);
$availStatus = false;
$errorCode = -1;
$statusMessage = "";


if (!isset($_POST["username"]))
{
	$errorCode = 2;
	$statusMessage = "Pakollinen parametri puuttuu.";
}
else if (strlen($_POST["username"]) < 3)
{
	$errorCode = 4;
	$statusMessage = "Valitsemasi käyttäjänimi on liian lyhyt. Vähimmäispituus on 3 merkkiä.";
}
else if (strlen($_POST["username"]) > 20)
{
	$errorCode = 4;
	$statusMessage = "Valitsemasi käyttäjänimi on liian pitkä. Enimmäispituus on 20 merkkiä.";
}
else if (mysqli_num_rows($dataconnection->query("SELECT username FROM banned_usernames WHERE username = '$username' LIMIT 1")) == 1)
	{
		$errorCode = 3;
		$statusMessage = "Käyttäjätunnuksen <i>$username</i> käyttö ei ole sallittua. Valitse uusi.";
	}
else if (mysqli_num_rows($dataconnection->query("SELECT id FROM user_accounts WHERE username = '$username' LIMIT 1")) == 0)
{
	$availStatus = true;
		
$statusMessage = "Käyttäjätunnus on vapaa.";
}
else
{
	$errorCode = 1;
	$statusMessage = "Käyttäjätunnus on jo varattu. Valitse uusi.";
}
header('Content-Type: application/json');
class AvailabilityStatus {
      public $isSuccess = "";
      public $errorCode  = "";
      public $statusMessage = "";
   }
	
   $unstatus = new AvailabilityStatus();
   $unstatus->isSuccess = $availStatus;
   $unstatus->errorCode = $errorCode;
   $unstatus->statusMessage  = $statusMessage;
   echo json_encode($unstatus);

?>