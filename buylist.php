<?php 
	include_once 'conn.inc.php'; 
	include_once 'phplogin/main.php';


function pic_change_char($card) {
	$card = str_replace("&#039;","'",$card);
	$card = str_replace("&#34;",'', $card);
	$card = str_replace("&amp;",'&',$card);
	$card = str_replace(" / ","_",$card);
	return $card;
}

function url_pic($card, $setcode) {
	if ($setcode != 'F_LEG' && $setcode != 'F_DRA' && $setcode != 'F_REL' && $setcode != 'F_EXI') $setcode = str_replace('F_','',$setcode);
	$card = pic_change_char($card);
	$remove = array(':',' / ','?'); // can be as big as needed '/',
	$card = str_replace($remove,"",$card);	
	$url_pic = 'cardpics/thumbnails/' . $setcode . '/' . $card . '.jpg';
	return $url_pic;
}

	$user_id = $_SESSION['id'] ?? null ;
	$user_loggedin = $_SESSION['loggedin'] ?? null;

	$admin = false;
	if ($user_loggedin && $user_id == 2) $admin = true;




?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Buylist</title>

<style>

#wrapper {
	width: 900px;
	margin: 0 auto;
	border: 10px solid #FFFFFF;
	background: #FFFFFF;
}

body {
	margin: 0px;
	padding: 0px;
	font-family: Verdana,sans-serif;
	color: #444444;
	background: #000000;

}

.box {
    position: relative;
    display: inline-block; /* Make the width of box same as image */
    margin: 4px;
    margin-top: 2px;
}

.box .price {
    position: absolute;
    z-index: 999;
    margin: 0 auto;
    left: 0;
    right: 0;        
    text-align: center;
    top: 45%; /* Adjust this value to move the positioned div up and down */
		
    font-family: Verdana,sans-serif;
    font-size: large;
    color: white;
}

.box .price_hot {
    position: absolute;
    z-index: 999;
    margin: 0 auto;
    left: 0;
    right: 0;        
    text-align: center;
    top: 45%; /* Adjust this value to move the positioned div up and down */
	background: rgba(128,128,0, 0.7); /* 255,255,153 0*/ 
    font-family: Verdana,sans-serif;
    font-size: large;
    color: black;
}

.tooltip {
  position: relative;
  display: inline-block;
}


.tooltip .tooltiptext {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
 
  /* Position the tooltip text - see examples below! */
  position: absolute;
  z-index: 1;

	top: -5%;


}

.tooltip .tooltiptext_bottom {
  visibility: hidden;
  width: 120px;
  background-color: black;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
 
  /* Position the tooltip text - see examples below! */
  position: absolute;
  z-index: 1000;

  top: 85%;
  left: 50%;
  margin-left: -60px; /* Use half of the width (120/2 = 60), to center the tooltip */

}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
  visibility: visible;
}

.tooltip:hover .tooltiptext_bottom {
  visibility: visible;
}

</style>


</head> 
<body>
<div id="wrapper">
<h1>mtgcards.be <u>BUYLIST</u></h1>
<?php

// form to change the buylist info
if ($admin && isset($_REQUEST['id']) && isset($_REQUEST['buy_q'])) {

	$buy_id = $_REQUEST['id'];
	$buy_q = $_REQUEST['buy_q'];
	
	$info = explode(";", $buy_q);

	if (count($info) == 3 && is_float($info[0]+0) && is_int($info[1]+0) && is_int($info[2]+0) )  {


		echo '<font color="red"><h3>CARD BUY PRICE/QUANTITY CHANGED!</h3></font>';
	
		$buy_new = $info[0] + 0;
		$buy_new = number_format($buy_new, 2, '.', '');

		$q_new = $info[1] + 0;

		$hot_new = $info[2] + 0;
		if ($hot_new != 1) $hot_new = 0;
		
		$buylist_sql_2 = "SELECT buylist.id id, cardname, setcode, buylist.quantity quantity, buyprice, hot, cards.quantity have_quantity, price FROM buylist, cards WHERE buylist.id = cards.id AND buylist.id = " . $buy_id; 

		$result_1 = mysqli_query($db_connection, $buylist_sql_2) or die("Could not connect: " . mysqli_error($db_connection));
		$row = mysqli_fetch_array($result_1);	

		$set = $row['setcode'];
		$card = $row['cardname'];
		$q = $row['quantity'];
		$buyprice = $row['buyprice'];
		$hot = $row['hot'];
		$have_quantity = $row['have_quantity'];
		$price = $row['price'];
		$id = $row['id'];

		$buy_before_comma = floor($buy_new);
		$buy_after_comma = 100 * ($buy_new - $buy_before_comma);
		if ($buy_after_comma < 10) $buy_after_comma = '0' . $buy_after_comma;


		echo '<table><tr><td>';
		echo '<div class="box"><div class="tooltip"><span class="tooltiptext" style="visibility: visible;"><small>' . $card;
		echo '</small></span>';
		$url_pic = url_pic($card, $set);

		if (file_exists($url_pic)) {
			echo '<img src="' . $url_pic . '" width="120" height="170" />'; 
		} else {
			echo '<img src="cardpics/thumbnails/unknown.jpg" width="120" height="170" />';
		}
		echo '</div>';
		echo '<div class="price';
		if ($hot_new) echo '_hot';
		echo '"><strong><h1>' . $buy_before_comma . '<small><small><small>.' . $buy_after_comma . '</small></small></small></h1></strong></div></div>';
		echo '</td><td>';
		echo '<u>Buy Price</u><br/>';
		echo '<font color="red">' . $buyprice . '</font> &#8594; <strong>' . $buy_new . '</strong>';
		echo '<br/><u>Desired Quantity</u><br/>';
		echo '<font color="red">' . $q . '</font> &#8594; <strong>' . $q_new . '</strong>';
		echo '<br/><u>Hot</u><br/>';
		echo '<font color="red">' . $hot . '</font> &#8594; <strong>' . $hot_new . '</strong>';
		// echo '&nbsp;';
		echo '</td></tr></table>';

		if ($q_new > 0 && $buy_new > 0) {
			$sql_update = "UPDATE buylist SET buyprice = " . $buy_new . ", quantity = " . $q_new . ", hot = " . $hot_new . " WHERE id = " . $buy_id; 
			$result_update = mysqli_query($db_connection, $sql_update) or die("Could not connect: " . mysqli_error($db_connection));
		}

		
	} else {
		echo '<font color="red"><h3>ERROR "PRICE;QUANTITY" = "9.99;99" = "' . $buy_q . '"!</h3></font>';
	}


}

/*
if (!$user_loggedin) {
	echo '<h3>Please make sure to <a href="/phplogin/index.php">LOGIN</a> in order to see our buylist</h3>';
} else {
	*/
?>
<!-- h3><a href="download_buylist.php">Download CSV</a></h3> -->

<p>We don't offer a detailed buylist at this stage, but this will change again in the future. 
<br/>However, you can still trade your cards at <strong>mtgcards.be</strong>!
<br/>Provide us a list of all cards and the quantity. Please mention the correct set, include collector number if possible.	
<br/>Prices will be offered for <strong>Near Mint & English</strong> and will be available as <strong><i>trade-credit</i></strong>.
<br/>This <strong><i>trade-credit</i></strong> is available for purchasing singles.
<br/>It can also be used for sealed product at 87.5% or even cash at 75% (Payment happens via bank transfer or PayPal).
<br/>If cards are not in the required condition, we'll adapt our trade-value: Excellent at 75% / Played at 50%.
<br/>
<br/><br/><u>Bulk Offers</u>
<ul>
	<li>Mythic 0.20 EUR each</li>
	<li>Rare 0.08 EUR each </li>
	<li>Uncommon 6.00 EUR / 1000</li>
	<li>Common 3.00 EUR / 1000</li>
</ul>
Get in touch with us at <a target="_new" href="mailto:buylist@mtgcards.be?subject=Buylist <?php echo date("d.m.Y");?>">buylist@mtgcards.be</a>.
</p>
<!--
<p>We have regular and *hot* buyprices. Check them out below!
	<div class="box"><div class="tooltip"><span class="tooltiptext"><small>NORMAL</small></span>
	<span class="tooltiptext_bottom"><small>Check it out!</small></span>
	<img src="cardpics/thumbnails/card_buylist.jpeg" width="120" height="170" />
	</div><div class="price"><strong><h1>XX<small><small><small>.XX</small></small></small></h1></strong></div></div>
	<div class="box"><div class="tooltip"><span class="tooltiptext"><small>HOT BUY</small></span>
	<span class="tooltiptext_bottom"><small>€€€!</small></span>
	<img src="cardpics/thumbnails/card_buylist_hot.jpeg" width="120" height="170" />
	</div><div class="price_hot"><strong><h1>XX<small><small><small>.XX</small></small></small></h1></strong></div></div>
</p>
-->
<p>
<i><small><small><u>Policy</u>
<ul>
<li>Please sort your cards as your list to ease the intake process. Preference is per set and alphabetical.</li>
<li>You are responsible for shipment, please protect the cards. Use toploaders or cardboard to properly protect. For bigger amount orders, we recommend small packages. Contact us if you wish to receive a cheap shipping label!</li>
<li>No need to sleeve every single card... Ten cards fit a 'soft clear sleeve' perfectly!</li>
<li>If a price offered seems to good to be true we've probably made a pricing error. We may return items that were mispriced.</li>
</ul>
</small></small></i>
</p>

<?php	

/*





$buylist_sql = "SELECT buylist.id id, cardname, setcode, buylist.quantity quantity, buyprice, hot, cards.quantity have_quantity, price FROM buylist, cards WHERE buylist.id = cards.id ORDER BY setcode, collector_nr + 0 ASC";

$result = mysqli_query($db_connection, $buylist_sql) or die("Could not connect: " . mysqli_error($db_connection));

$set_prev = '';
$set = '';

while (	$row = mysqli_fetch_array($result) ) {
	
	$set = $row['setcode'];

	if ($set_prev != $set) echo '<h1>' . $set . '</h1>';

	$card = $row['cardname'];
	
	$q = $row['quantity'];
	// if $q == 0 only show for admin, in order to adapt price / quantity

	$buyprice = $row['buyprice'];
	$hot = $row['hot'];

	$have_quantity = $row['have_quantity'];
	$price = $row['price'];
	$id = $row['id'];



	$buy_before_comma = floor($buyprice);
	$buy_after_comma = 100 * ($buyprice - $buy_before_comma);
	if ($buy_after_comma < 10) $buy_after_comma = '0' . $buy_after_comma;

	echo '<div class="box"><div class="tooltip"><span class="tooltiptext"><small>' . $card;
	
	if ($admin) {
		echo '<form action="' . htmlentities($_SERVER['PHP_SELF']) . '" method="post">';
		echo '<small>';
		echo '<input type="text" name="buy_q" value="' . $buyprice . ';' . $q . ';' . $hot . '" size="8">';
		echo '<input type="hidden" name="id" value="' . $id . '">';
		echo '<input type="submit" name="buy" value=">>" size="2">';
		echo '</small>';
		echo '</form>';
	}

	echo '</small></span>';


	
//	echo '<span class="tooltiptext_bottom">' . $price . '(' . $have_quantity . 'x)';

	echo '<span class="tooltiptext_bottom"><small>';
	if ($admin) echo '<font color=yellow>' . $price . ' (' . $have_quantity . 'x)</font>';
	else echo 'max ' . $q . 'x';
	echo '</small></span>';


	

	$url_pic = url_pic($card, $set);

	if (file_exists($url_pic)) {
		echo '<img src="' . $url_pic . '" width="120" height="170" />'; 
	} else {
		echo '<img src="cardpics/thumbnails/unknown.jpg" width="120" height="170" />';
	}
	
	echo '</div><div class="price';
	if ($hot) echo '_hot';
	echo '"><strong><h1>' . $buy_before_comma . '<small><small><small>.' . $buy_after_comma . '</small></small></small></h1></strong></div></div>';
	// echo '&nbsp;';
	$set_prev = $set;

 }

}

*/
?>
</div>
</body>
</html>