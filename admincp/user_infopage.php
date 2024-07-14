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


if (!isset($_GET["uid"]))
{
  echo "Puuttuva parametri.";
	exit;
}

echo "<h2>Käyttäjän X tiedot</h2>";
 ?>
