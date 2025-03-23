<?php

session_start();

include_once 'functions-write.inc.php';
include_once "conn.inc.php";

if (isset($_REQUEST['order_id'])) $order_id = $_REQUEST['order_id'];
else $order_id = "";

if ($order_id != "" && isset($_SESSION['id'])) {
	
	$user_id = $_SESSION['id'];
	
	$sql = sprintf("SELECT lastname, firstname FROM mtgcards_users WHERE id='%d'", mysqli_real_escape_string($db_connection, $user_id));
	$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error() . ' >> ' . $sql_x);
	$row = mysqli_fetch_array($result);
	
	// ORDER -- INTRODUCTION
	
	echo '<h1 class="title">Your order = ' . order_full($order_id) . '</h1>';
	echo '<div class="entry"><p>Dear ' . utf8_decode($row['firstname']) . ' ' . utf8_decode($row['lastname']) . ',';
	echo '<br/>An email from "info@mtgcards.be" with all further information should be in your mailbox very soon.';
	echo '<br/><i>If you don\'t see the email, perhaps your spam filter is blocking it, please check.</i>';
	echo '<p></div>';
	echo '<h1 class="title">Thank you for purchasing at <strong>mtgcards.be</strong>!</h1>';
} else {
	goto_path("index.php");
}
?>