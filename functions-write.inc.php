<?php

function mysqli_writePO ($conn, $user_id) {
   	$sql = sprintf("SELECT land FROM mtgcards_users WHERE id='%d'", mysqli_real_escape_string($conn, $user_id));
	$result = mysqli_query($conn, $sql); // or die ("Query mislukt : " . mysql_error());
	$row = mysqli_fetch_array($result);
	if ($row['land'] == 21) { // Belgium
		// national
			echo '<input type="radio" name="po" value="BB" checked> Belgium Bank Transfer</input>';
			echo '<br/><input type="radio" name="po" value="PP"> Paypal Payment</input>';
			echo '<br/><input type="radio" name="po" value="X0"> Cash (only available when you collect yourself)</input>';
	} else {
		// EUROPE / WORLD
			echo '<input type="radio" name="po" value="EB" checked> Europe Bank Transfer (IBAN/BIC)</input>';
			echo '<br/><input type="radio" name="po" value="PP"> Paypal Payment</input>';
	}
}

/*
function sh_cost($sh) {


	$sh_c = 999.99;
	if ($sh == "N1") $sh_c = 1.00;
	else if ($sh == "N2") $sh_c = 1.75;
	else if ($sh == "N3") $sh_c = 2.50;
	else if ($sh == "N4") $sh_c = 3.25;
	else if ($sh == "NR1") $sh_c = 6.25;
	else if ($sh == "NR2") $sh_c = 7.00;
	else if ($sh == "NR3") $sh_c = 7.75;
	else if ($sh == "NR4") $sh_c = 8.50;
	// else if ($sh == "K") $sh_c = 5.00;
	else if ($sh == "I1") $sh_c = 1.50;
	else if ($sh == "I2") $sh_c = 2.50;
	else if ($sh == "I3") $sh_c = 3.50;
	else if ($sh == "I4") $sh_c = 4.50;
	else if ($sh == "IR1") $sh_c = 6.75;
	else if ($sh == "IR2") $sh_c = 7.75;
	else if ($sh == "IR3") $sh_c = 8.75;
	else if ($sh == "IR4") $sh_c = 9.75;
	else if ($sh == "I5") $sh_c = 15.00;
	else if ($sh == "I6") $sh_c = 0.00;
	else if ($sh == "X0") $sh_c = 0.00;
	else if ($sh == "T") $sh_c = 6.00;
	else if ($sh == "M") $sh_c = 4.50;
	else if ($sh == "P") $sh_c = 4.50;

	return $sh_c;
}

*/
function sh_full($sh) {
	$sh_f = "Unknown";
	
	// NEW Feb 2025
	if ($sh == "ENV") $sh_f = "Envelope";
	elseif ($sh == "COL") $sh_f = "Collect Yourself";
	elseif ($sh == "PAR") $sh_f = "Parcel Home Delivery";
	elseif ($sh == "MON") $sh_f = "Mondial Relay";
	elseif ($sh == "BPK") $sh_f = "Parcel Pick-Up/Locker";
	elseif ($sh == "MIN") $sh_f = "BPack Mini";
	// elseif ($sh == "000") $sh_f = "Unknown";
	
	// Previous
	else if ($sh == "N1" OR $sh == "N2" OR $sh == "N3" OR $sh == "N4") $sh_f = "National Belgium";
	else if ($sh == "NR1" or $sh == "NR2" OR $sh == "NR3" OR $sh == "NR4") $sh_f = "National Belgium Registered";
	// else if ($sh == "K") $sh_f = "National Kiala Shipping";
	else if ($sh == "T") $sh_f = "BPost Package @Home";
	else if ($sh == "M") $sh_f = "BPost Mini BPack @Home";
	else if ($sh == "P") $sh_f = "Bpost Package @BPack";
	else if ($sh == "I1" OR $sh == "I2" OR $sh == "I3" OR $sh == "I4") $sh_f = "International Europe/World";
	else if ($sh == "I5" OR $sh == "I6") $sh_f = " International Shipping (Additional charge applied!)";
	else if ($sh == "IR1" OR $sh == "IR2" OR $sh == "IR3" OR $sh == "IR4") $sh_f = "International Europe/World Registered";
	else if ($sh == "X0") $sh_f = "Collect Yourself";
	
	return $sh_f;
}

function po_full($po) {
	$po_f = "";
	if ($po == "BB") $po_f = "Belgium Bank Transfer";
	else if ($po == "PP") $po_f = "Paypal";
	else if ($po == "EB") $po_f = "Europe Bank Transfer (IBAN/BIC)";
	else if ($po = "X0") $po_f = "Cash";
	return $po_f;	
}

function order_full($order_id) {
	$x = "";
	if ($order_id < 10) $x = '000' . $order_id;
	else if ($order_id < 100) $x = '00' . $order_id;
	else if ($order_id < 1000) $x = '0' . $order_id;
	else $x = "" . $order_id;
	return $x;
}

function writePayment($po, $tot, $order) {
	$ref = order_full($order);
	$txt = "";
	
	$tot = number_format($tot,2,'.','');
	
	if ($po == "BB") {
		$txt .= 'Please pay <strong>&euro; ' . $tot . '</strong> on bank account<br/>';
		$txt .= '<strong>BE63 3770 0478 8908</strong><br/>';
		$txt .= 'Please mention the order reference = <strong>' . $ref . '</strong>.';
	}
	else if ($po == "PP") {
		$txt .= 'If not done yet, please initiate the PayPal payment using the following url:';
		$txt .= "\n";
		$txt .= '<br/><a href="https://www.mtgcards.be/index.php?paypal&order_id=' . $order . '">Pay your order via PayPal</a>.';
	}
	else if ($po == "EB") {
		$txt .= 'Please pay <strong>&euro; ' . $tot . '</strong> on the following bank account<br/>';
		$txt .= 'IBAN = <strong>BE63 3770 0478 8908</strong><br/>BIC = <strong>BBRUBEBB</strong><br/>';		
		$txt .= 'Please mention the order reference = <strong>' . $ref . '</strong>';
	}
	else if ($po == "X0") {
		$txt .= 'You chose to pay cash on delivery.<br/>';
	}
	return $txt;	
}




?>