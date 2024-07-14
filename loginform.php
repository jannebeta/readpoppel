<?php

/* LukuPoppeli Kutsukoodi & kirjautumisjärjestelmän pääsivu */

include("configuration.php");

$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";

$invitekey = substr(str_shuffle($characters), 0, 6) . "-" . substr(str_shuffle($characters), 0, 6) . "-" . substr(str_shuffle($characters), 0, 6) . "-" . substr(str_shuffle($characters), 0, 6);

if (isset($_GET["createInvite"]))
{
	$dataconnection->query("INSERT INTO `invites` (`timestamp_created`, `invite_code`, `is_used`) VALUES ('" . time() . "', '$invitekey', '0')");
	echo "<!-- Kutsukoodi generoitu: " . $invitekey . " -->";
}
if (isset($_SESSION["LOGIN_USER"]))
{

	header("Location: http://" . $_SERVER['SERVER_NAME'] . "/");

}

?>
<html>

<head>

<link rel="stylesheet" href="/styles/invite_tool.css" type="text/css"/>
<link href='https://fonts.googleapis.com/css?family=Ubuntu:300,300italic' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/js/jquery.maskedinput.js"></script>
<script type="text/javascript" src="/assets/js/invitetool.js"></script>

<meta name="description" content="LukuPoppeli on osa Ankka Verkkopalveluita">

<meta name="generator" content="ReadPoppel v0.4">

<title>LukuPoppeli</title>

</head>

<body>

<div id="invite_box">
<div class="readpoppel_logo"></div>
<p>Palvelun <i>LukuPoppeli</i> käyttäminen vaatii kutsukoodin. Ole hyvä ja syötä kutsukoodi,mikäli sinulta sellainen löytyy. Jos olet luonut jo tunnuksen kirjaudu sisään <a href="#" onclick="ShowLogin(); return false;" style="color: white;">klikkaamalla tästä</a>.</p>
<p><input type="text" name="inviteCode" id="inviteCode" placeholder="Kirjoita tai kopioi saamasi kutsukoodi tähän" class="invite_field" spellcheck="false"></p>
<p><div id="result"></div></p>
</div>
</body>

</html>