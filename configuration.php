<?php
/* ReadPopper - Configuration file */
header('Content-Type: text/html; charset=utf-8');
session_start();


// Site configuration

$SITE_URL = "http://localhost";

// Database information

$MYSQL_HOST = "localhost";

$MYSQL_PORT = "3306";

$MYSQL_USER = "root";

$MYSQL_PASSWORD = "poni123";

$MYSQL_DATABASE = "poppeli";

// DATA CONNECTING PART

$dataconnection = new mysqli($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DATABASE);

if ($dataconnection->connect_error) {
	header( 'Location: http://' . $_SERVER['SERVER_NAME'] . '/system/connection_error' ) ;
} 

?>