<?php

// SERVER_NAME
include_once 'serverinfo.inc.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_connection = mysqli_connect("localhost","root","","mtgcards_be");
$db_login = mysqli_connect("localhost","root","","mtgcards_bephplogin");

if (!$db_connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!$db_login) {
    die("Connection failed: " . mysqli_connect_error());
}


?>