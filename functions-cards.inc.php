<?php

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

function url_pic_full($card, $setcode) {
	if ($setcode != 'F_LEG' && $setcode != 'F_DRA' && $setcode != 'F_REL' && $setcode != 'F_EXI') $setcode = str_replace('F_','',$setcode);
	$card = pic_change_char($card);
	$remove = array(':','/','?'); // can be as big as needed '/',
	$card = str_replace($remove,"",$card);
	$url_pic = 'cardpics/full/' . $setcode . '/' . $card . '.jpg';
	return $url_pic;
}

function url_pic_new($collector_nr, $setcode) {
	$collector_nr = str_replace('*','',$collector_nr);
	$url_pic = 'cardpics/thumbnails/' . $setcode . '/' . $collector_nr . '.jpg';
	return $url_pic;
}

function writeCardPic($row,$extraInfo) { // ,$userIsLoggedIn,$userIsAdmin) {

	if ($extraInfo != '') $extraInfo = '&' . $extraInfo;

	// if (isset($_SESSION['loggedin'])) $userIsloggedIn = true;
	// else $userIsloggedIn = false;

	$userIsAdmin = false;


	// $row is from DB
	$id = $row['id']; $card = $row['cardname'];	$rarity = $row['rarity'];
	$color = $row['color']; $setname = $row['setname'];
	
	$price = $row['price']; $quantity = $row['quantity']; 
//	$price_foil = $row['price_foil']; $quantity_foil = $row['quantity_foil'];
	
	
	
	// $priceB = $row['priceB']; $quantityB = $row['quantityB'];
	$tv = $row['trade_param'];
	$setcode = $row['setcode'];
	$r = $row['r']; // rarity as one char

	$collector_nr = $row['collector_nr'];

	// print_r($row);

//	$buy = buy_price($price, $q, $r, $tv);

	if ($quantity > 10) $quantity = 10;
	// else if ($quantity > 20) $quantity = 20;
	// if ($quantity_foil > 5) $quantity_foil = 5;

	if ($price < 0.05) 			{	$quantity = 0;			$price = '?.??';		}
	// if ($price_foil < 0.10)		{	$quantity_foil = 0;		$price_foil = '?.??';	}


// check if DFC
  $url_pic_trans = '';
	$url_pic = '';
	$dfc = false;
	$pos_dfc = strpos($card,'|');
	$c = $card;

	$c_trans = '';

	if ($pos_dfc) {
		$dfc = true;
		$c = substr($card,0,$pos_dfc-1);
		$c_trans = substr($card,$pos_dfc+2);

		$url_pic_trans = url_pic($c_trans, $setcode);

		 if (!file_exists($url_pic_trans)) $dfc = false;

			if (strstr($card, ' (Borderless)')) $c .= ' (Borderless)';
			else if (strstr($card, ' (Showcase)')) $c .= ' (Showcase)';
			else if (strstr($card, ' (Extended Art)')) $c .= ' (Extended Art)';
			else if (strstr($card, ' (Godzilla Series Monsters)')) $c .= ' (Godzilla Series Monsters)';
			else if (strstr($card, ' (Planeswalker Deck)')) $c .= ' (Planeswalker Deck)';
			else if (strstr($card, ' (Buy-a-Box)')) $c .= ' (Buy-a-Box)';
	}


	$url_pic = url_pic($c, $setcode);
	$url_pic_new = url_pic_new($collector_nr, $setcode);
	$url_pic_new_2nd = url_pic_new($collector_nr . '_', $setcode);

//	$url_pic_trans = url_pic($c_trans, $setcode);



	// <t> 1 :: picture(s)
	echo "\n";
	echo '<!-- start -->';
	echo "\n";
	echo '<tr>';

// splits in two pictures upfront!
	$colspan = 'colspan="2"';

	if (file_exists($url_pic_new)) $url_pic = $url_pic_new;
	if (file_exists($url_pic_new_2nd)) {
		$url_pic_trans = $url_pic_new_2nd;
		$dfc = true;
	}


	if (file_exists($url_pic)) {

		echo '<td rowspan="5" width="60">';
			echo "\n";
		echo '<img src="' . $url_pic . '" width="60" height="85" />';
			echo "\n";
		echo '</td>';

		if ($dfc) {
			echo "\n";
			echo '<td rowspan="5" width="60">';
			echo "\n";
			echo '<img src="' . $url_pic_trans . '" width="60" height="85" />';
			echo "\n";
			echo '</td>';
			$colspan = '';
		} 
	}	else {
		echo '<td rowspan="5" width="60">';
			echo "\n";
		echo '<img src="cardpics/thumbnails/unknown.jpg" width="60" height="85" />';
			echo "\n";
		echo '</td>';

	}
	echo '</tr>';

  // <tr> 2 :: cardname
	echo "\n";
	echo '<tr>';
	echo '<td height="17" style="padding-left: 10px;" '; echo $colspan; echo '><strong>' . $card . '</strong></td>';
	echo '</tr>';

	// <tr> 3 :: price
	echo "\n";
	echo '<tr>';
	
	// Regular
	echo '<td height="17" style="padding-left: 10px;" '; echo $colspan; echo '>Regular: <strong>' . $price . ' &euro;</strong>';
	if ($quantity > 0) echo ' (' . $quantity . 'x)';

	// Foil
	/*if ($price_foil != '?.??') {
		echo ' | Foil: <strong>' . $price_foil . ' &euro;</strong>';
		if ($quantity_foil > 0) echo ' (' . $quantity_foil . 'x)'; 
	}
	*/
	
	// if ($userIsAdmin && $buy != '0.00') echo ' <small>[' . $buy . ']</small>';
	echo '</td>';
	echo '</tr>';

	// <tr> 4 :: set, color, rarity
	echo "\n";
	echo '<tr>';
	echo '<td height="17" style="padding-left: 10px;" '; echo $colspan; echo '><i>' . $setname;
	if ($collector_nr != '') echo ' #' . $collector_nr;
	echo ' (' . $color . ', ' . $rarity . ')</i>';
	echo '</td>';
	echo '</tr>';


// echo '<tr>&nbsp;xxxxxxxxxxxxxxxx</tr>';

  // <tr> 5 :: addCart
	echo "\n";
	echo '<tr>';
	echo '<td height="17" align="right" '; echo $colspan; echo '>';
	if (isset($_SESSION['loggedin'])) {
		// if (is_ok2sell($setcode, $r)) {
			if ($quantity > 0) echo '<strong>Add to cart &#187; ';
			if ($quantity > 3) echo '<a href="addToCart.php?id=' . $id . '&q=4' . $extraInfo . '">+4</a>';
			if ($quantity > 2) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=3' . $extraInfo . '">+3</a>';
			if ($quantity > 1) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=2' . $extraInfo . '">+2</a>';
			if ($quantity > 0) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=1' . $extraInfo . '">+1</a></strong>';
			else echo '&nbsp;'; // '<a href="addToWish.php?id=' . $id . '&q=1' . $extraInfo . '">Wish</a>';
		// } else if ($quantity > 0) {
		// 	echo '<small><i>Soon you can order this card!<i></small>';
		// }
	}
	else echo '(Log in to order)';
	echo '</td>';
	echo '</tr>';


	// <tr> :: bb
	echo "\n";
  echo '<tr><td class="bb2" colspan="3"></td></tr>';



}

function writeCardSmall($row,$extraInfo,$userIsLoggedIn) {
	// $row is from DB
	$id = $row['id']; $card = $row['cardname'];	$rarity = $row['rarity'];
	$color = $row['color']; $price = $row['price']; $quantity = $row['quantity']; $setname = $row['setname'];
	$setcode = $row['setcode'];
	$r = $row['r']; // rarity as one char

	if ($extraInfo != '') $extraInfo = '&'.$extraInfo;

	// $url_pic = url_pic($card, $setcode);
	$url_pic_full = url_pic_full($card, $setcode);

	echo '<tr>';


	echo '<td width="350"><strong>';
	// if (file_exists($url_pic_full)) echo '<a href="' . $url_pic_full . '" rel="lightbox[mtgcards]">';
	echo $card . ' - ' . $setname;
	// if (file_exists($url_pic_full)) echo '</a>';
	echo '</strong></td>';
	echo '<td>' . $quantity . 'x</td>';
	echo '<td>&euro; ' . $price . '</td>';

	echo '<td align="right">';
	if ($userIsLoggedIn) {
		echo '<strong>';
		if ($quantity > 3) echo '<a href="addToCart.php?id=' . $id . '&q=4' . $extraInfo . '">+4</a>';
		if ($quantity > 2) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=3' . $extraInfo . '">+3</a>';
		if ($quantity > 1) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=2' . $extraInfo . '">+2</a>';
		if ($quantity > 0) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=1' . $extraInfo . '">+1</a></strong>';
		else echo '&nbsp;'; // '<a href="addToWish.php?id=' . $id . '&q=1' . $extraInfo . '">Wish</a>';
	}
	else '&nbsp;';
	echo '<td></tr>';

	echo "\n";
    	echo '<tr><td class="bb" colspan="4">&nbsp;</td></tr>';

}

?>