<?php
	// include_once 'phpbb.inc.php';
	include_once 'conn.inc.php';
	include_once 'functions.inc.php';

function pp_button($db_connection, $order) {
	
	$sql = "SELECT * from mtgcards_orders WHERE order_id = $order";
	$result = mysqli_query($db_connection, $sql);
	$row = mysqli_fetch_array($result);
	$sub = $row['sub'];
	$sh = $row['sh_sub'];
	
	echo '<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">';
	echo "\n";
	echo '<input type="hidden" name="cmd" value="_xclick">';
	echo "\n";
	echo '<input type="hidden" name="business" value="paypal@mtgcards.be">';
	echo "\n";
	$order_full = order_full($order);
	echo '<input type="hidden" name="item_name" value="mtgcards_be - order #' . $order_full . '">';
	echo "\n";
	echo '<input type="hidden" name="currency_code" value="EUR">';
	echo "\n";
	echo '<input type="hidden" name="amount" value="' . $sub . '">';
	echo "\n";
	echo '<input type="hidden" name="invoice" value="' . $order_full . '">';
	echo "\n";
	echo '<input type="hidden" name="charset" value="utf-8">';
	echo "\n";
	echo '<input type="hidden" name="shipping" value="' . $sh . '">';
	echo "\n";
	echo '<input type="hidden" name="return" value="http://www.mtgcards.be/index.php?thx_pp&order=' . $order . '">';
	echo "\n";
	echo '<input type="hidden" name="no_note" value="1">';
	echo "\n";
	echo '<input type="image" src="https://www.paypal.com/en_US/i/bnr/horizontal_solution_PPeCheck.gif" name="submit" alt="Make payments with PayPal - it is fast, free and secure!">';
	echo "\n";
	echo '</form>';
}

	if (isset($_REQUEST['order_id'])) $order = $_REQUEST['order_id'];
	else $order = "";
	
	if ($order != "") {

		echo '<h1 class="title">Pay via PayPal</h1><div class="entry"><p>';
		echo "Dear, <br/>please use the below button to proceed with your paypal payment:";
		echo "<br/><br/>";
		
		pp_button($db_connection, $order);
		
		echo "<br/>";
		echo "<i>You will be transferred back to this site after completing your payment.</i></p></div>";
		//goto("index.php?thx_order&order_id=" . $order_id);
	} else {
		goto_path("index.php");
	}
	
?>
