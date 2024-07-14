<?php
require_once("configuration.php");
$code = mysqli_real_escape_string($dataconnection, @$_POST["code"]);
$inviteStatus = false;
$errorCode = -1;
$statusMessage = "";
$inviteCode = "";

if (!isset($_POST["code"]))
{
	$errorCode = 2;
	$statusMessage = "Pakollinen parametri puuttuu.";
}
else if (mysqli_num_rows($dataconnection->query("SELECT invite_code FROM invites WHERE invite_code = '$code' AND is_used = '0' LIMIT 1")) == 1)
{
	$inviteStatus = true;
$_SESSION["INVITE_CODE"] = $code;
$inviteCode = $code;
$statusMessage = "Koodi kelpaa! Jatka rekisteröintiä.";
}
else
{
	$errorCode = 1;
	$statusMessage = "Koodi on virheellinen tai se on jo käytetty.";
}
header('Content-Type: application/json');
class InviteStatus {
      public $isSuccess = "";
      public $errorCode  = "";
      public $statusMessage = "";
	  public $inviteCode = "";
   }
	
   $invite = new InviteStatus();
   $invite->isSuccess = $inviteStatus;
   $invite->errorCode = $errorCode;
   $invite->statusMessage  = $statusMessage;
   $invite->inviteCode = $inviteCode;
   echo json_encode($invite);

?>