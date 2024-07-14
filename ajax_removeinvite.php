<?php
require_once("configuration.php");

if (!isset($_SESSION["LOGIN_USER"]))
{
	header("HTTP/1.1 401 Unauthorized");
	exit;
}

if (!isset($_POST["id"]) || intval($_POST["id"]) < 0 || !is_numeric($_POST["id"]))
{
header("HTTP/1.1 400 Bad Request");
return;	
}

header("Content-Type: text/plain");

$inviteId = intval($_POST["id"]);

$dataconnection->query("DELETE FROM invites WHERE id = '$inviteId' LIMIT 1");

echo "Toiminto onnistui.";

?>