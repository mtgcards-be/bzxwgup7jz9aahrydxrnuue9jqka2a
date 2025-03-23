<?php

if ($_SESSION['loggedin']) {

// include_once 'phpbb.inc.php';
// include_once 'functions.inc.php';

include_once 'functions-cart-mysqli.inc.php';
include_once 'functions-products-mysqli.inc.php';

$user_id = $_SESSION['id'];

mysqli_checkCartQuantities_2 ($db_connection, $user_id);

?>

<h1 class="title">Your Cart</h1>
<div class="entry">
<p>
<?php
include_once 'cart_info.inc.php';
echo '</p></div>';
$txt = '';

	if ($n_r != 0) {
		$txt = '<a target="_self" href="checkout.php?p1">Check out &#187;</a>';
		echo '<h1 class="title"><a href="index.php?delcart">Empty cart</a> | ' . $txt . '</h1>';
	}


}

?>
