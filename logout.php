<?php
include("configuration.php");
session_destroy();

header("Location: http://" . $_SERVER['SERVER_NAME'] . "/subscription_block");
?>