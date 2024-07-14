<?php
require_once("configuration.php");
require_once("functions/passwordLib.php");

$username = mysqli_real_escape_string($dataconnection, $_POST["username"]);
$password = mysqli_real_escape_string($dataconnection, $_POST["password"]);
$passwordAgain = mysqli_real_escape_string($dataconnection, $_POST["password_again"]);
$email = mysqli_real_escape_string($dataconnection, $_POST["email"]);

if ($username == "" || $password == "" || $passwordAgain == "" || $email == "")
{
	echo "Täytä kaikki kentät!";
	exit;
	
}
if ($_SESSION["INVITE_CODE"] == null)
{
	die("Kutsukoodi puuttuu!");
}
if (strpos($email, "@") != true)
{
	echo "Sähköpostiosoite virheellinen!";
	exit;
}
else if (strpos(explode('@', $email)[1], ".") != true)
{
	echo "Sähköpostiosoite on virheellinen!";
	exit;
}
if ($password != $passwordAgain)
{
	echo "Salasanat eivät täsmää!";
	exit;
}
if (mysqli_num_rows($dataconnection->query("SELECT username FROM user_accounts WHERE username = '$username' LIMIT 1")) == 1)
{
	echo "Käyttäjänimi on jo käytössä.";
	exit;
}

$dataconnection->query("INSERT INTO `user_accounts` (`username`, `password`, `registered_ip`, `email`, `timestamp_registered`) VALUES ('$username', '" . password_hash($password, PASSWORD_BCRYPT) . "', '" . $_SERVER['REMOTE_ADDR'] . "', '$email', '" . time() . "')");

$dataconnection->query("DELETE FROM `invites` WHERE (`invite_code`='" . $_SESSION["INVITE_CODE"] . "')");

echo '<script>window.location.href = "http://' . $_SERVER['SERVER_NAME'] . '/";';
?>