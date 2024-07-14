<?php
require_once("configuration.php");
require_once("functions/passwordLib.php");

$username = mysqli_real_escape_string($dataconnection, $_POST["username"]);
$password = mysqli_real_escape_string($dataconnection, $_POST["password"]);

$passwordHash = @$dataconnection->query("SELECT password FROM user_accounts WHERE username = '$username' LIMIT 1")->fetch_object()->password;
$adminStatus = false;

if (password_verify($password, $passwordHash))
{
	$_SESSION["LOGIN_USER"] = $username;
	
	if ($dataconnection->query("SELECT permission FROM user_accounts WHERE username = '$username' LIMIT 1")->fetch_object()->permission == "ADMINISTRATOR")
	{
		$adminStatus = true;
	}
	$_SESSION["IS_ADMIN"] = $adminStatus;
 	$dataconnection->query("UPDATE user_accounts SET accessCount = accessCount + 1 WHERE username = '$username'");
	echo '<script>window.location.href = "http://' . $_SERVER['SERVER_NAME'] . '/";';
}
else
{
	echo "Virheellinen salasana!";
}
?>