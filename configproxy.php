<?php

if (!isset($_GET["paperBrand"]) || !isset($_GET["paperId"]))
{
die("404");
}
header('Content-Type: application/json');
echo file_get_contents("http://e-pages.dk/" . $_GET["paperBrand"] . "/desktop/v1/configuration/" . $_GET["paperId"] . "/configuration.json")
?>