<?php

include_once 'functions-stock.inc.php';

function is_valid_country_for_packages($country_id) {
	
/*
Country				>> 		ID
Belgium 1167 				21
Netherlands 58 				150 
Finland 19					(72)
Canada 7
Germany 2					81
France 2					73
Croatia 1
Peru 1
Israel 1
*/

	$valid_country_ids = [21, 150, 81, 73];
    return in_array($country_id, $valid_country_ids);
}


function pic_change_char_prod($card) {
	$card = str_replace("&#039;","'",$card);
	$card = str_replace("&#34;",'', $card);
	$card = str_replace("&amp;",'&',$card);
	$card = str_replace(" / ","_",$card);
	$card = str_replace(":","",$card);
	return $card;
}

function writeProd($row,$extraInfo) { // ,$userIsLoggedIn) {
	// $row is from DB
	$id = $row['id']; 
	$id += 250000; // products !!
	$prod = $row['productname'];

	$prod = pic_change_char_prod($prod);

	$desc = $row['description'];	
	$quantity = $row['quantity']; 
	$price = $row['price'];
	
	$url_pic = 'prodpics/thumbnails/' . $prod . '.jpg';
	$url_pic_full = 'prodpics/full/' . $prod . '.jpg';
		
	echo '<tr>';
	

	if (file_exists($url_pic)) {
		echo '<td rowspan="8" width="130">';
		// if (file_exists($url_pic_full)) echo '<a href="' . $url_pic_full . '" rel="lightbox" title="' . $prod . '">';
		echo '<img src="' . $url_pic . '" width="120" height="120"/>';
		// if (file_exists($url_pic_full)) echo '</a>';
		echo '</td>';
	}
	else echo '<td rowspan="8">&nbsp;</td>';
	echo '<td>&nbsp;</td>';
	echo '</tr><tr>';
	echo '<td><strong>' . $prod . '</strong></td>';
	echo '</tr><tr>';
	echo '<td>' . $desc . '</td>';
	echo '</tr><tr>';
	echo '<td><strong>' . $price . ' &euro;<strong></td>';
	echo '</tr><tr>';
	if ($quantity <= 0) {
		echo '<td><small><i>[Out of stock]</small></i>';
	} else if ($quantity == 999) {
		echo '<td><small><i>[Pre-Order]</i></small></td>';
	} else {
		echo '<td><small><i>[' . $quantity . 'x]</i></small></td>';
	}

	echo '</tr><tr>';
	echo '<td align="right">';
	if (isset($_SESSION['loggedin'])) {
		if (is_valid_country_for_packages($_SESSION['country'])) {
			if ($quantity > 3) echo '<a href="addToCart.php?id=' . $id . '&q=4&' . $extraInfo . '">+4</a>';
			if ($quantity > 2) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=3&' . $extraInfo . '">+3</a>';
			if ($quantity > 1) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=2&' . $extraInfo . '">+2</a>';
			if ($quantity > 0) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=1&' . $extraInfo . '">+1</a>';
		} else {
			echo '<i><small>S&H not available</small></i>';
		}
	}
	else if ($quantity > 0) {
		echo '<i><small>Log in to order</small></i>';
	}
	echo '&nbsp;&nbsp;&nbsp;';
	if ($quantity < 0) {
		// echo '<td><strong>Sold out - no longer available.</strong></td>';
		stock_outofprint(); 
	} else if ($quantity == 999) {
		stock_preorder();
	} else if ($quantity == 0) {
		stock_vendor();
	} else if ($quantity < 4) {
		stock_limitedstock();
	} else {
		stock_instock();
	}
	echo '</td>';
	echo '</tr><tr>';
	echo '<td>&nbsp;</td>';
	echo '</tr><tr>';
	echo '</tr>';
	echo "\n";
    	echo '<tr><td class="bb2" colspan="2">&nbsp;</td></tr>';
    	
}

?>