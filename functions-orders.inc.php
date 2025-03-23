<?php

// ORDER FUNCTIONS

// adapt to msqli (only admin)
function writeCardsOrderDetail($order_id) {
	$sql = "SELECT cards.id card_id, cards.cardname cardname, cards.rarity rarity, cards.color color, ";
	$sql .= "cards.setcode setcode, orderinfo.price price, orderinfo.quantity quantity ";
	$sql .= "from colors, cards, orderinfo WHERE orderinfo.order_id = '$order_id' AND cards.id = orderinfo.card_id AND cards.color = colors.colorcode ";
	$sql .= "ORDER BY setcode, rarity, color, cardname";
	$result = mysqli_query($db_connection, $sql);
	$ordertxt = '<tr><th>Cardname - Edition</th><th>Color</th><th>Rarity</th><th>Price</th><th>Subtotal</th></tr>';
	$ordertxt .= '<tr><td class="bb2" colspan="5">&nbsp;</td></tr>';
	$tot = 0;
	$set = "";
	while ($row = mysqli_fetch_array($result)) {
		$cardname = $row['cardname'];
		$rarity = $row['rarity'];
		$color = $row['color'];
		$setcode = $row['setcode'];
		if ($set != $setcode) $ordertxt .= '<tr><td class="bb2" colspan="5">&nbsp;</td></tr>';
		else $ordertxt .= '<tr><td class="bb" colspan="5">&nbsp;</td></tr>';
		$set = $setcode;
		$sql_e = "SELECT * FROM sets WHERE setcode='$setcode'";
		$result_e = mysqli_query($db_connection, $sql_e); // or die ("Query mislukt : " . mysql_error());;
		$row_e = mysqli_fetch_array($result_e);
		$edition = $row_e['setname'];
		$q = $row['quantity'];
		$p = $row['price'];
		$ordertxt .= '<tr>';
		$ordertxt .= '<td>' . $q . 'x ' . $edition . ' - ' . $cardname . '</td>';
		$ordertxt .= '<td>' . $color . '</td>';
		$ordertxt .= '<td>' . $rarity . '</td>';
		$ordertxt .= '<td>&euro; ' . number_format($p,2) . '</td>';
		$sub = number_format($q * $p,2);
		$tot += $sub;
		$ordertxt .= '<td>&euro; ' . $sub . '</td>';
		$ordertxt .= '</tr>';
		$ordertxt .= "\n";				
	}
	$tot = number_format($tot,2);
	$ordertxt .= '<tr><td class="bb2" colspan="5">&nbsp;</td></tr>';
	$ordertxt .= '<tr><td colspan="3">&nbsp;</td><td class="r">Total =</td><td>&euro; ' . $tot . '</td></tr>';
	$ordertxt .= '<tr><td class="bb2" colspan="5">&nbsp;</td></tr>';
	return $ordertxt;
}

// adapt to mysqli (only admin)
function writeCardsOrderDetailAdmin($order_id) {
	$sql = "SELECT cards.id card_id, cards.cardname cardname, cards.rarity rarity, cards.color color, ";
	$sql .= "cards.setcode setcode, orderinfo.price price, orderinfo.quantity quantity ";
	$sql .= "from colors, cards, orderinfo WHERE orderinfo.order_id = '$order_id' AND cards.id = orderinfo.card_id AND cards.color = colors.colorcode ";
	$sql .= "ORDER BY setcode, rarity, color, cardname";
	$result = mysqli_query($db_connection, $sql);
	$ordertxt = '<tr><th>Cardname - Edition</th><th>Color</th><th>Rarity</th><th>Price</th><th>Subtotal</th><th>&nbsp;</th></tr>';
	$ordertxt .= '<tr><td class="bb2" colspan="6">&nbsp;</td></tr>';
	$tot = 0;
	$set = "";
	while ($row = mysqli_fetch_array($result)) {
		$cardname = $row['cardname'];
		$rarity = $row['rarity'];
		$color = $row['color'];
		$setcode = $row['setcode'];
		if ($set != $setcode) $ordertxt .= '<tr><td class="bb2" colspan="6">&nbsp;</td></tr>';
		else $ordertxt .= '<tr><td class="bb" colspan="6">&nbsp;</td></tr>';
		$set = $setcode;
		$sql_e = "SELECT * FROM sets WHERE setcode='$setcode'";
		$result_e = mysqli_query($db_connection, $sql_e); // or die ("Query mislukt : " . mysql_error());;
		$row_e = mysqli_fetch_array($result_e);
		$edition = $row_e['setname'];
		$q = $row['quantity'];
		$p = $row['price'];
		$ordertxt .= '<tr>';
		$ordertxt .= '<td>' . $q . 'x ' . $edition . ' - ' . $cardname . '</td>';
		$ordertxt .= '<td>' . $color . '</td>';
		$ordertxt .= '<td>' . $rarity . '</td>';
		$ordertxt .= '<td>&euro; ' . number_format($p,2) . '</td>';
		$sub = number_format($q * $p,2);
		$tot += $sub;
		$ordertxt .= '<td>&euro; ' . $sub . '</td>';
		$ordertxt .= '<td><a href="">-1</a></td>';
		$ordertxt .= '</tr>';
		$ordertxt .= "\n";				
	}
	$tot = number_format($tot,2);
	$ordertxt .= '<tr><td class="bb2" colspan="6">&nbsp;</td></tr>';
	$ordertxt .= '<tr><td colspan="3">&nbsp;</td><td class="r">Total =</td><td>&euro; ' . $tot . '</td><td>&nbsp;</td></tr>';
	$ordertxt .= '<tr><td class="bb2" colspan="6">&nbsp;</td></tr>';
	return "AAA -- " . $ordertxt;
}



function writeCardsOrderMail($order_id) {
	$sql = "SELECT cards.id card_id, cards.cardname cardname, cards.rarity rarity, cards.color color, ";
	$sql .= "cards.setcode setcode, mtgcards_orderinfo.price price, mtgcards_orderinfo.quantity quantity ";
	$sql .= "from colors, cards, mtgcards_orderinfo WHERE mtgcards_orderinfo.order_id = '$order_id' AND cards.id = mtgcards_orderinfo.card_id AND cards.color = colors.colorcode ";
	$sql .= "ORDER BY setcode, rarity, color, cardname";
	$result = mysql_query($sql);
	$ordertxt = '<table width="85%" border="0">';
	$ordertxt .= "\n";
	$ordertxt .= '<tr><td><font face="Verdana" size="2"><strong>Edition - Cardname</strong></font></td><td><font face="Verdana" size="2"><strong>Color</strong></font></td><td><font face="Verdana" size="2"><strong>Rarity</strong></font></td><td><font face="Verdana" size="2"><strong>Price</strong></font></td><td><font face="Verdana" size="2"><strong>Subtotal</strong></font></td></tr>';
	$ordertxt .= "\n";
	$ordertxt .= '<tr><td style="border-bottom: solid 2px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td></tr>';
	
	/*
	td.bb {
  border-bottom: solid 1px #333;
}

td.bb2 {
  border-bottom: solid 2px #333;
  height: 0px;
  font-size: 0px;
  line-height: 0px;
  padding: 0px;
}

	*/
	$tot = 0;
	$set = "";
	while ($row = mysql_fetch_array($result)) {
		$cardname = $row['cardname'];
		$rarity = $row['rarity'];
		$color = $row['color'];
		$setcode = $row['setcode'];
		if ($set != $setcode) $ordertxt .= '<tr><td style="border-bottom: solid 2px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td></tr>';
		else $ordertxt .= '<tr><td style="border-bottom: solid 1px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td></tr>';
		$set = $setcode;
		$sql_e = "SELECT * FROM sets WHERE setcode='$setcode'";
		$result_e = mysql_query($sql_e) or die ("Query mislukt : " . mysql_error());;
		$row_e = mysql_fetch_array($result_e);
		$edition = $row_e['setname'];
		$q = $row['quantity'];
		$p = $row['price'];
		$ordertxt .= "\n";
		$ordertxt .= '<tr>';
		$ordertxt .= "\n";
		$ordertxt .= '<td><font face="Verdana" size="2">' . $q . 'x ' . $edition . ' - ' . $cardname . '</font></td>';
		$ordertxt .= "\n";
		$ordertxt .= '<td><font face="Verdana" size="2">' . $color . '</font></td>';
		$ordertxt .= "\n";
		$ordertxt .= '<td><font face="Verdana" size="2">' . $rarity . '</font></td>';
		$ordertxt .= "\n";
		$ordertxt .= '<td><font face="Verdana" size="2">&euro; ' . number_format($p,2) . '</font></td>';
		$sub = number_format($q * $p,2);
		$tot += $sub;
		$ordertxt .= "\n";
		$ordertxt .= '<td><font face="Verdana" size="2">&euro; ' . $sub . '</font></td>';
		$ordertxt .= "\n";
		$ordertxt .= '</tr>';
		$ordertxt .= "\n";				
	}
	$tot = number_format($tot,2);
	$ordertxt .= '<tr><td style="border-bottom: solid 2px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td></tr>';
	$ordertxt .= "\n";
	$ordertxt .= '<tr><td colspan="3"><font face="Verdana" size="2">&nbsp;</font></td><td><font face="Verdana" size="2">Total =</font></td><td><font face="Verdana" size="2">&euro; ' . $tot . '</font></td></tr>';
	$ordertxt .= "\n";
	$ordertxt .= '<tr><td style="border-bottom: solid 2px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td></tr>';
	$ordertxt .= "\n";
	$ordertxt .= '</table>';
	$ordertxt .= "\n";
	return $ordertxt;
}

function writeOrderMail_1 ($row) {
	
	$user_id = $row['user_id'];
	$order_id = $row['order_id'];
	
	$msg = '<font face="Verdana" size="2">';
	$msg .= "\n";
	$msg .= '<p><u><strong>Shipping Address</strong></u></p>';
	$msg .= "\n";
	$sql_x = sprintf("SELECT lastname, firstname, street, nr, postcode, place, land FROM mtgcards_users WHERE id='%d'", mysql_real_escape_string($user_id));
	$result_x = mysql_query($sql_x) or die ("Query mislukt : " . mysql_error() . ' >> ' . $sql_x);
	$row_x = mysql_fetch_array($result_x);
	$sql_y = sprintf("SELECT user_email FROM phpbb_users WHERE user_id='%d'", mysql_real_escape_string($user_id));
	$result_y = mysql_query($sql_y) or die ("Query mislukt : " . mysql_error() . ' >> ' . $sql_y);
	$row_y = mysql_fetch_array($result_y);
	$email = $row_y['user_email'];
	$sql_y = sprintf("SELECT countries_name FROM countries WHERE countries_id='%d'", mysql_real_escape_string($row_x['land']));
	$result_y = mysql_query($sql_y) or die ("Query mislukt : " . mysql_error() . ' >> ' . $sql_y);
	$row_y = mysql_fetch_array($result_y);
	$land = $row_y['countries_name'];
	$msg .= '<p>' . $row_x['lastname'] . " " . $row_x['firstname'];
	$msg .= "\n";	
	$msg .= '<br/>' . $row_x['street'] . " " . $row_x['nr'];
	$msg .= "\n";
	$msg .= '<br/>' . $row_x['postcode'] . " " . $row_x['place'];
	$msg .= "\n";
	$msg .= '<br/>' . $land;
	$msg .= "\n";
	$msg .= '</p>';
	$msg .= "\n";

	$sql = "SELECT * FROM mtgcards_orders WHERE user_id='$user_id' AND order_id = '$order_id'";
	$result = mysql_query($sql) or die ("Query mislukt : " . mysql_error() . ' >> ' . $sql);
	$row = mysql_fetch_array($result);
	
	$msg .= '<p><u><strong>Order details</strong></u></p>';
	$msg .= '<p><i>Date - ' . $row['date'];
	$sh_full = sh_full($row['sh']);
	$po_full = po_full($row['po']);
	$tot = $row['sub'];
	$sh_cost = $row['sh_sub'];
	$msg .= "\n";
	$msg .= '<br/>Shipping - ' . $sh_full . ' (&euro; ' . number_format($sh_cost ,2) . ')';
	$msg .= "\n";
	$msg .= '<br/>Payment - ' . $po_full . '</i></p>';
	$msg .= "\n";

	$msg .= "\n";
	$msg .= '</font>';

	$msg .= '<br/><font face="Verdana" size="2"><strong><u>mtgcards_be - Order ' . order_full($order_id) . '</u></strong></p></font><br/>';
	

	return $msg;
}

function writeOrderMail_2($row) {
	
	$order_id = $row['order_id'];
	$tot = $row['sub'];
	$sh_cost = $row['sh_sub'];
	$tot_t = number_format(($tot+$sh_cost),2);
	
	$msg = '<font face="Verdana" size="2">';
	$msg .= '<p><u><strong>Payment details</strong></u></p>';

	$msg .= "\n";
	$msg .= '<p>Order price = &euro; ' . number_format($tot,2) . '<br/>';
	$msg .= "\n";
	$msg .= 'S&H = &euro; ' . number_format($sh_cost,2) . '<br/>';
	$msg .= '<strong>Total price = &euro; <u>' . number_format(($tot_t),2) . '</u></strong>';
	$msg .= "\n";
	$msg .= '</p><p>';
	$msg .= writePayment($row['po'], $tot_t, $order_id) . '</p>';
	$msg .= "\n";
	$msg .= '<p>Thanks and best regards,<br/><strong>~ mtgcards_be</strong></p>';
	
	// footer
	$msg .= '<hr>';
	$msg .= '<center><strong>mtgcards.be</strong> - Van den Hautelei 53, 2100 Deurne - BTW BE 0806 364 364';
	$msg .= '<br/>ING 377-0047889-08 - IBAN BE63 3770 0478 8908 - BIC BBRUBEBB<center>';
	
	$msg .= "\n";
	$msg .= '</font>';
	return $msg;
}

?>