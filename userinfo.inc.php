<?php

/*
$id = $_SESSION['id'];
$username = $_SESSION['name'];
$user_loggedin = $_SESSION['loggedin'];
*/

if ($id > 0) {

	$sql = "SELECT id FROM mtgcards_users WHERE id='$id'";
	$result = mysqli_query($db_connection, $sql);
	$num_rows = mysqli_num_rows($result);
	if ($num_rows == 1) $inDB = true;
	else $inDB = false;

	if (isset($_REQUEST['cart'])) $cart_current = true;
	else $cart_current = false;
	/*
	if (isset($_REQUEST['wish'])) $wish_current = true;
	else $wish_current = false;
	*/
	/*
	if (isset($_REQUEST['account'])) $account_current = true;
	else $account_current = false;
	*/

	if (isset($_REQUEST['err'])) $err = $_REQUEST['err'];
	else $err = '';

	

	if ($inDB) {
		$sql = sprintf("SELECT lastname, firstname, street, nr, postcode, place, land FROM mtgcards_users WHERE id='%d'", mysqli_real_escape_string($db_connection, $id));
		$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error());
		$row = mysqli_fetch_array($result);
		$firstname = $row['firstname'];
	}
	else $firstname = '---';
	//echo "<h2>&#171; " . substr($username,0,18) . " &#187; (" . $id . ")</h2>";
	echo "<h2>&#171; " . $firstname . " &#187; </h2>";

	echo "<ul>";
	if ($inDB) {
		if ($id == 2) {
			echo '<li><a href="admin.php">Admin</a></li>';
		} 
		
		if ($err == 'X') {
			echo '<li><font color="red">Desired quantity not available.</font></li>';
		} else if ($err == 'M' || $err == 'R') {
			echo '<li><font color="red">Maximum 4 M/R cards.</font></li>';
		} else if ($err == 'U' || $err == 'C') {
			echo '<li><font color="red">Maximum 8 C/U cards.</font></li>';
		} else if ($err == 'Y') {
			echo '<li><font color="red">Limited to 1 per person.</font></li>';
		}
		echo '<li><a ';
		if ($cart_current) echo 'class="current" ';
		echo 'href="index.php?cart">My Cart</a>';
		
		$i = 0; $j = 0;
		$sql = "SELECT card_id, quantity FROM mtgcards_cart WHERE user_id='$id'";
		$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error());
		while ($row = mysqli_fetch_array($result)) {
			if ($row['card_id'] < 250000) $i += $row['quantity'];
			else $j += $row['quantity'];
		}
			
		if ($i > 0 OR $j > 0) {
			echo '&nbsp;<i>(' . $i . ' card';
			if ($i != 1) echo 's';
			echo ', ' . $j . ' product';
			if ($j != 1) echo 's';
			echo ')</i>';
		}
		echo '</li>';
		/*
		echo '<li><a ';
		if ($wish_current) echo 'class="current" ';
		echo 'href="index.php?wish">My Wishlist</a></li>';
		*/
		echo '<li><a href="index.php?account">My Account</a></li>';

	} else {
		echo '<li><b><a href="index.php?register">Complete your registration here!</a></b></li>';
	}
	echo "</ul>";
} else {
	echo '<ul><li>¯\_(ツ)_/¯</li></ul>';
}

?>