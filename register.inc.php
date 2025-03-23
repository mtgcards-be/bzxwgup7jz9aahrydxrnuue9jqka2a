<?php

include_once 'functions.inc.php';

// $username = $user->data['username'];

if (isset($_REQUEST['street'])) $street = $_REQUEST['street'];
else $street = "";
if ($street == '0') $street = "";
if (isset($_REQUEST['nr'])) $nr = $_REQUEST['nr'];
else $nr = "";
if ($nr == '0') $nr = "";
if (isset($_REQUEST['firstname'])) $firstname = $_REQUEST['firstname'];
else $firstname = "";
if ($firstname == '0') $firstname = "";
if (isset($_REQUEST['lastname'])) $lastname = $_REQUEST['lastname'];
else $lastname = "";
if ($lastname == '0') $lastname = "";
if (isset($_REQUEST['postcode'])) $postcode = $_REQUEST['postcode'];
else $postcode = "";
if ($postcode == '0') $postcode = "";
if (isset($_REQUEST['place'])) $place = $_REQUEST['place'];
else $place = "";
if ($place == '0') $place = "";
if (isset($_REQUEST['land'])) $land = $_REQUEST['land'];
else $land = 21; // Belgium
if (isset($_REQUEST['tel'])) $tel = $_REQUEST['tel'];
else $tel = "0032";
if(isset($_REQUEST['newsletter'])) $news = $_REQUEST['newsletter'];
else $newsletter = "1";


// Check if user does not exist in mtgcards_user DB

$uid = $_SESSION['id'];

$sql = "SELECT id FROM mtgcards_users WHERE id='$uid'";
$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error());

if (mysqli_num_rows($result) == 0) $user_in_db = false;
else $user_in_db = true;

$errormsg = "";

if ($lastname != "" || $firstname != "" || $street != "" || $nr != '' || $postcode != '' || $place != '') {
	if ($lastname == "") $errormsg .= "Lastname can't be left blank.<br/>";
	if ($firstname == "") $errormsg .= "Firstname can't be left blank.<br/>";
	if ($street == "") $errormsg .= "Street can't be left blank.<br/>";
	if ($nr == "") $errormsg .= "Nr can't be left blank.<br/>";
	if ($postcode == "") $errormsg .= "Postcode can't be left blank.<br/>";
	if ($place == "") $errormsg .= "Place can't be left blank.<br/>";
	if ($land == "") $errormsg .= "Land can't be left blank.<br/>";
}

if (isset($_SESSION['loggedin'])) { 
	
?>

<h1 class="title">Register</h1>
<div class="entry">
<p>Your email is now active and can be used to log in at our <b>mtgcards.be</b> shop. But we need a few things more to get your items shipped ;-).
</p>

<?php if ($errormsg != "") echo '<p class="error"><font color="red">' . $errormsg . '</font></p>'; ?>

<p>Please fill in all necessary details for your account ("<?php echo '<strong>' . $_SESSION['name'] . '</strong>'; ?>"):</p>

<form id="login" name="login" action="register-verify.php" method="post">
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
		<td><input type="submit" class="button" name="submit" value="register" /></td>
		<td><input type="reset" class="button" name="submit" value="reset" /></td>
	</tr>
	</table>
</p>
</form>
</div>
<?php
}
?>
