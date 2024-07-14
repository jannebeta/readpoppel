<?php

require_once("configuration.php");

header('Content-Type: application/json');

if (!isset($_SESSION["LOGIN_USER"]))
{
	header('HTTP/1.1 401 Unauthorized');
	echo(json_encode(array('error' => 'authentication-needed')));
	exit();
}


if (!isset($_GET["paperBrand"]) || !isset($_GET["paperId"]) || !isset($_GET["pageId"]))
{
	header('HTTP/1.1 400 Bad request');
	echo(json_encode(array('error' => 'required-params')));
	exit();
}

$paperBrand = mysqli_real_escape_string($dataconnection, $_GET["paperBrand"]);
$paperId = intval($_GET["paperId"]);
$pageId = intval($_GET["pageId"]);

if ($pageId < 0)
{
	header('HTTP/1.1 400 Bad request');
	echo(json_encode(array('error' => 'invalid-param')));
	exit();
}
if ($paperId < 0)
{
	header('HTTP/1.1 400 Bad request');
	echo(json_encode(array('error' => 'invalid-param-2')));
	exit();
}

$dataquery = "SELECT * FROM papers WHERE paperBrand = '" . $paperBrand . "' AND paperId = '" . $paperId . "' LIMIT 1";

$dataresult = $dataconnection->query($dataquery);

if ($dataresult->num_rows <= 0)
{
	header('HTTP/1.1 404 Not found');
	echo(json_encode(array('error' => 'paperdata-missing')));
	exit();
}

$searchCheck = $dataconnection->query("SELECT * FROM user_readhistory WHERE user_id = '" . $_SESSION["LOGIN_UID"] . "' AND paperBrand = '" . $paperBrand . "' AND paperId = '" . $paperId . "' LIMIT 1");

if ($searchCheck->num_rows <= 0)
{

	$dataconnection->query("INSERT INTO `user_readhistory` (`user_id`, `paperBrand`, `paperId`, `pagesReaded`, `timestamp_start`, `timestamp_end`, `open_count`) VALUES ('" . $_SESSION["LOGIN_UID"] . "', '" . $paperBrand . "', '" . $paperId . "', '1', '" . time() . "', '0', '1')");
}
else
{
	$dataconnection->query("UPDATE `user_readhistory` SET `pagesReaded` = pagesReaded+1, `currentPage` = '" . $pageId . "', `timestamp_end` = '" . time() . "' WHERE `user_id` = '" . $_SESSION["LOGIN_UID"] . "' AND `paperId` = '" . $paperId . "'");
}
echo(json_encode(array('status' => 'ok')));
exit();
//$dataconnection->query("INSERT INTO `papers_metadatas` (`paperBrand`, `paperId`, `additionalImageURL`, `page_number`, `phrase_title`, `phrase_text`) VALUES ('" . $paperName . "', '" . $paper["id"] . "', '" . $meta->images->image->medium . "', '" . $meta->page . "' , '" . utf8_decode($meta->titles->title) . "', '" . strip_tags(utf8_decode($meta->content)) . "')");


?>
