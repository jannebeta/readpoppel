<?php
require_once("configuration.php");
$code = mysqli_real_escape_string($dataconnection, $_POST["code"]);

if (mysqli_num_rows($dataconnection->query("SELECT invite_code FROM invites WHERE invite_code = '$code' AND is_used = '0' LIMIT 1")) == 1)
{
echo '<div class="icon_success"></div> Kutsukoodi kelpaa!<br><br>';
echo '<a href="#" onclick="RegisterCode(); return false;" class="invite_button">Seuraavaan vaiheeseen >></a>';
$_SESSION["INVITE_CODE"] = $code;
}
else
{
	echo '<div class="icon_failed"></div> Kutsukoodi on virheellinen!';
}
?>