<?php

// include_once "functions.inc.php";
include_once "functions-write.inc.php";

$txt = "";
$errormsg = "";


if (isset($_SESSION['id'])) $uid = $_SESSION['id'];
else $uid = -1;

// action=confirm_ok&order_id=

if (isset($_REQUEST['action']) AND $_REQUEST['action'] == "confirm_ok" AND isset($_REQUEST['order_id']) AND $uid > 0) {
	$order = $_REQUEST['order_id'];
	$sql = "UPDATE mtgcards_orders SET status='4' WHERE order_id = '$order' AND user_id = '$uid'";
	$result = mysqli_query($db_connection, $sql);
}
if (isset($_REQUEST['action']) AND $_REQUEST['action'] == "change" AND $uid > 0) {
		
	if (isset($_REQUEST['street'])) $street = $_REQUEST['street'];
	else $street = "";
	if (isset($_REQUEST['nr'])) $nr = $_REQUEST['nr'];
	else $nr = "";
	if (isset($_REQUEST['firstname'])) $firstname = $_REQUEST['firstname'];
	else $firstname = "";
	if (isset($_REQUEST['lastname'])) $lastname = $_REQUEST['lastname'];
	else $lastname = "";
	if (isset($_REQUEST['postcode'])) $postcode = $_REQUEST['postcode'];
	else $postcode = "";
	if (isset($_REQUEST['place'])) $place = $_REQUEST['place'];
	else $place = "";
	if (isset($_REQUEST['land'])) $land = $_REQUEST['land'];
	else $land = "";
	if (isset($_REQUEST['tel'])) $tel = $_REQUEST['tel'];
	else $tel = "";
	if ($tel == 0) $tel = "0032";

//	if (isset($_REQUEST['newsletter'])) $newsletter = $_REQUEST['newsletter'];
	$newsletter = 1;
	
	$errormsg = "";
		
	if ($lastname == "") $errormsg .= "Lastname can't be left blank.<br/>";
	if ($firstname == "") $errormsg .= "Firstname can't be left blank.<br/>";
	if ($street == "") $errormsg .= "Street can't be left blank.<br/>";
	if ($nr == "") $errormsg .= "Number can't be left blank.<br/>";
	if ($postcode == "") $errormsg .= "Postcode can't be left blank.<br/>";
	if ($place == "") $errormsg .= "Place can't be left blank.<br/>";
	
	if ($errormsg == "") {
		// wegschrijven in DB	
		
		$sql = sprintf("UPDATE mtgcards_users SET lastname='%s', firstname='%s', street='%s', nr='%s', postcode='%s', place='%s', land='%s', tel='%s', news='%s' WHERE id = '$uid'",
     					mysqli_real_escape_string($db_connection, $lastname),
     					mysqli_real_escape_string($db_connection, $firstname),
     					mysqli_real_escape_string($db_connection, $street),
     					mysqli_real_escape_string($db_connection, $nr),
     					mysqli_real_escape_string($db_connection, $postcode),
     					mysqli_real_escape_string($db_connection, $place),
     					mysqli_real_escape_string($db_connection, $land),
     					mysqli_real_escape_string($db_connection, $tel),
     					mysqli_real_escape_string($db_connection, $newsletter));
		$result = mysqli_query($db_connection, $sql); // or die ("Query [" . $sql . "] mislukt : " . mysql_error()); 
		
		$txt = "Account succesfully adapted.";
	}   

}


if (isset($_REQUEST['action'])) $action = $_REQUEST['action'];
else $action = "";

$title_overview = '<h1 class="title">My orders ';

if ( ($action == "orders" || $action == "confirm_ok") AND $uid > 0 ) {

	$sql = "SELECT count(*) total FROM mtgcards_orders WHERE user_id='$uid'";
	$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error() . ' >> ' . $sql);
	//$num_rows = mysql_num_rows($result);
	$row = mysqli_fetch_array($result);
	$num_rows = $row['total'];
	$offset = 20;
	$tot_pages = ceil($num_rows / $offset);

	if (isset($_REQUEST['start'])) {
		$start = $_REQUEST['start'];
		$page = $start/$offset;
	} else {
		$page = ($tot_pages - 1);
		$start = $page * $offset;
	}
	
	if ($num_rows != 0) {
		$sql = "SELECT order_id, date, mtgcards_orderstatus.status status, sub, sh_sub FROM mtgcards_orders, mtgcards_orderstatus WHERE mtgcards_orders.user_id='$uid'";
		$sql .= " AND mtgcards_orderstatus.id = mtgcards_orders.status ORDER BY date DESC, order_id DESC LIMIT $start , $offset";
		$result = mysqli_query($db_connection, $sql); // or die("Query mislukt : " . mysql_error() . ' >> ' . $sql);
	}
	$i = 0;
	if ($num_rows > $offset) {
		while ($i < $tot_pages) {
			if ($i == $page) { // $page = 1
				$title_overview .= '[' . ++$i . ']&nbsp;';
			}
			else $title_overview .= '<a href="index.php?account&action=orders&start=' . $i++ * $offset . '">' . $i . '</a>&nbsp;';
		}
		
	}
	$title_overview .= '(Total = ' . $num_rows . ')';
	$title_overview .= '</h1>';
	echo $title_overview;
	

	echo '<div class="entry"><p><ul>';
	if ($num_rows != 0) {
		while ($row = mysqli_fetch_array($result)) {
			echo '<li><strong><a href="index.php?orderinfo&id=' . $row['order_id'];
			// $$$$$$$$$$$$$$$$$$
			// $ add HASH ?!?!? $
			// $$$$$$$$$$$$$$$$$$
			echo '">#' . order_full($row['order_id']) . '</a></strong> (' . $row['date'] . ') <strong>&euro; ' . number_format(($row['sub'] + $row['sh_sub']),2,'.','') . '</strong>';
			// echo '<ul>';
			// echo '<li type="circle">Status = "' . $row['status'] . '"';
			echo ' (Status = "' . $row['status'] . '")';
			// if ($row['status'] == 'Shipped') echo ' - <a href="index.php?account&action=confirm&order_id=' . $row['order_id'] . '">confirm</a> your order.';
			// echo '</li>';
			
			// $sub = number_format($q2 * $price,2,'.','');
			/*
			echo '<li type="circle">&euro; ' . number_format($row['sub'],2,'.','') . ' + &euro; ' . number_format($row['sh_sub'],2,'.','');
			echo ' (S&H) = <strong>&euro; ' . number_format(($row['sub'] + $row['sh_sub']),2,'.','') . '</strong></li>';
			*/
			
			// echo '<li type="circle">Total = <strong>&euro; ' . number_format(($row['sub'] + $row['sh_sub']),2,'.','') . '</strong></li>';
			// echo '</ul>';
			/*
				<li type=îdisc"> eerste item
				<li type=îsquareî > tweede item
				<li type=îcircleî > derde item
			*/
			
//			echo '</li>';
			
		}
	} else {
		echo '<li>Still waiting for your first order? ;-)</li>';
	}
	echo '</ul>';
	echo '</div>';
	//echo $title_overview;
	echo '<h1 class="title"><a href="index.php?account">Back</a></h1>';
} else if ( ($action == "profile" OR $action == "change") AND $uid > 0 ) {

	$sql = "SELECT tel, lastname, firstname, street, nr, postcode, place, news, land FROM mtgcards_users WHERE id='$uid'";
	$result = mysqli_query($db_connection, $sql);
	$row = mysqli_fetch_array($result);
	$lastname = htmlentities($row['lastname'], ENT_QUOTES);
	
	$firstname = htmlentities($row['firstname'], ENT_QUOTES);
	$street = htmlentities($row['street'], ENT_QUOTES);
	$nr = $row['nr'];
	$postcode = $row['postcode'];
	$place = $row['place'];
	$newsletter = $row['news'];
	$land = $row['land'];

	
	$tel = $row['tel'];
	echo '<h1 class="title">Change your profile</h1>';
	echo '<div class="entry">';
	echo '<p>Change your profile. <i><strong>Attention!</strong> This information is used to ship your items.</i></p>';
	if ($txt != "") echo "<p><strong>" . $txt . "</strong></p>";
	else echo $errormsg;
?>	
	<form id="login" name="login" action="index.php?account&action=change" method="post">
	<p>
	<table>

	<tr>
		<td><input type="text" class="theInput" name="lastname" value="<?php echo $lastname; ?>"/></td>
		<td>Lastname *</td>
	</tr>
	<tr>
		<td><input type="text" class="theInput" name="firstname" value="<?php echo $firstname; ?>"/></td>
		<td>Firstname *</td>
	</tr>
	<tr>
		<td>
			<input type="text" class="theInput" name="street" value="<?php echo $street; ?>"/>
			<input type="text" class="theInputSmall" name="nr" value="<?php echo $nr; ?>"/>
		</td>
		<td>Street + Number *</td>
	</tr>
	<tr>
		<td>
			<input type="text" class="theInputSmall" name="postcode" value="<?php echo $postcode; ?>"/>
			<input type="text" class="theInput" name="place" value="<?php echo $place; ?>"/>
		</td>
		<td>Postcode + Place *</td>
	</tr>
	<tr>
		<td>
			<select class="theInput" name="land">
			<?php	
				$sql = "SELECT countries_id, countries_name FROM countries ORDER BY countries_name";
				$result = mysqli_query($db_connection, $sql);
				while ($row = mysqli_fetch_array($result)) {
					$c_id = $row['countries_id'];
					$c_nm = $row['countries_name'];
  					echo '<option value="' . $c_id . '"';
  					if ($land == $c_id) echo " selected ";
  					echo '>' . $c_nm . '</option>';
				}
			?>
			</select>
		</td>	
		<td>Land *</td> 
	</tr>
	<tr>
		<td><input type="text" class="theInput" name="tel" value="<?php echo $tel; ?>"/></td>
		<td>TEL/GSM</td>
	</tr>

		<?php
		/*
			<tr>
		<td>
	
		if ($newsletter == 1) {
			echo '<input type="radio" name="newsletter" value="0" />No&nbsp;';
			echo '<input type="radio" name="newsletter" value="1" checked />Yes';
		} else {
			echo '<input type="radio" name="newsletter" value="0" checked />No&nbsp;';
			echo '<input type="radio" name="newsletter" value="1" />Yes';
		}
		</td>
		<td>Want to receive newsletters?</td>
		
			</tr>
		*/
		?>

	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td colspan="2" class="bb">&nbsp;</td></tr>	
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td><input type="submit" class="button" name="submit" value="change" /></td>
		<td>&nbsp;</td>
	</tr>
	</table>
	</p>
	</div>
	<h1 class="title"><a href="index.php?account">Back</a></h1>
	</form>
	
<?php
} else if ($action == "confirm" AND $uid > 0) {
	$order_id = $_REQUEST['order_id'];
?>
	<h1 class="title">Change your profile</h1>
	<div class="entry">
	<p>Please confirm that you received order '<?php echo order_full($order_id); ?>'
	<br/>
	Thank you!</p>
	</div>
	<h1 class="title"><a href="index.php?account&action=confirm_ok&start=0&order_id=<?php echo $order_id; ?>">Confirm order</a></h1>
	</form>
<?php
} else if ($uid > 0 ) {
	
	$sql = "SELECT * FROM mtgcards_users WHERE id='$uid'";
	$result = mysqli_query($db_connection, $sql);
	$row = mysqli_fetch_array($result);
	
	$lastname = htmlentities($row['lastname'], ENT_QUOTES);
	
	$firstname = htmlentities($row['firstname'], ENT_QUOTES);
	$street = htmlentities($row['street'], ENT_QUOTES);
	
	$nr = $row['nr'];
	$postcode = $row['postcode'];
	$place = $row['place'];
	$newsletter = $row['news'];
	$land = $row['land'];
	$tel = $row['tel'];
	
	echo '<h1 class="title">Your profile</h1>';
	echo '<div class="entry"><p><strong><u>Address</u></strong><br/>';
	
	echo $lastname . ' ' . $firstname;
	echo '<br/>';
	echo $street . ' ' . $nr;
	echo '<br/>';
	echo $postcode . ' ' . $place;
	echo '<br/>';
	
	$sql_x = "SELECT countries_name FROM countries WHERE countries_id='$land'";
	$result_x = mysqli_query($db_connection, $sql_x);
	$row_x = mysqli_fetch_array($result_x);
	$land = $row_x['countries_name'];

	echo $land;
	
	echo '<br/><br/><strong><u>Contact Details</u></strong>';
	if ($tel != '0032' && $tel != '') {
		echo '<br/>';
		echo $tel;
	}
/*	echo '<br/><br/>Newsletter? ';
	if ($newsletter == 1) echo 'Yes';
	else echo 'No';
*/	
	echo '<br/>';
	echo $username;
	
	echo '<br/><small><i>We use this email-address for all communication. If you wish to change it, please contact us.</i></small></p></div>';

?>

	<h1 class="title"><a href="index.php?account&action=profile">Change details</a> | <a href="index.php?account&action=orders&start=0">Show my orders</a></h1>
<?php	
}
?>
