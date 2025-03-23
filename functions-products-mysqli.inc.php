<?php

// PRODUCT FUNCTIONS




function mysqli_writeProdsInCart ($result,$iscart,$isOrder = false) {
//writeCardsInCart($row,$extraInfo);
	$total = 0;
	$qTot = 0;
	$qC = 0;
	
	// q_stock
	$tbc = false;	
	$preorder = false;
	
    while ($row = mysqli_fetch_assoc($result)) {
    
		$c = $row['prod_id'];
		$d = $c + 250000;
		$prod = $row['productname'];
		$price = $row['price'];
		$q = $row['quantity'];
		$qC += $q;

		$q_stock = $row['q_stock'];
		$pre = false;
		if ($q_stock == 999) {
			$pre = true;
			$preorder = true;
		}
			
		$sub1 = 0;
		$sub2 = 0;	
				
		if (($pre || $q_stock >= $q)) {
			
			echo '<tr>';
			echo '<td class="r">' . $q . 'x</td>';
			echo '<td>' . $prod . '</td>';
			if ($pre) echo '<td><strong>PREORDER</strong></td>';
			else echo '<td>&nbsp;&nbsp;</td>';
			echo '<td class="c">' . $price . '</td>';
			$sub1 = number_format($q * $price,2,'.','');
			echo '<td class="c">' . $sub1 . '</td>';
			if ($iscart) {
				$extraInfo = "&showCart";
				echo '<td class="r">{';
				if ($q > 3) echo '<a href="addToCart.php?id=' . $d . '&q=-4' . $extraInfo . '">-4</a>&nbsp;';
				if ($q > 2) echo '<a href="addToCart.php?id=' . $d . '&q=-3' . $extraInfo . '">-3</a>&nbsp;';
				if ($q > 1) echo '<a href="addToCart.php?id=' . $d . '&q=-2' . $extraInfo . '">-2</a>&nbsp;';
				echo '<a href="addToCart.php?id=' . $d . '&q=-1' . $extraInfo . '">-1</a>';
				echo '}</td>';	
			} else {
				echo '<td>&nbsp;</td>';
			}
			echo '</tr>';

		} else {
		
			$q1 = $q_stock;
			$q2 = $q - $q_stock;

			// q_stock < $q -> we don't have a enough in stock
			// q1 = what we have still available in stock
			// q2 = what we don't have available in stock, but customer is asking

			// Q in stock
			if ($q1 > 0) {
				echo '<tr>';
				echo '<td class="r">' . $q1 . 'x</td>';
				echo '<td>' . $prod . '</td>';
				echo '<td>&nbsp;&nbsp;</td>';
				echo '<td class="c">' . $price . '</td>';
				$sub1 = number_format($q1 * $price,2,'.','');
				echo '<td class="c">' . $sub1 . '</td>';
				if ($iscart) {
					$extraInfo = "&showCart";
					echo '<td class="r">{';
					if ($q1 > 3) echo '<a href="addToCart.php?id=' . $d . '&q=-4' . $extraInfo . '">-4</a>&nbsp;';
					if ($q1 > 2) echo '<a href="addToCart.php?id=' . $d . '&q=-3' . $extraInfo . '">-3</a>&nbsp;';
					if ($q1 > 1) echo '<a href="addToCart.php?id=' . $d . '&q=-2' . $extraInfo . '">-2</a>&nbsp;';
					echo '<a href="addToCart.php?id=' . $d . '&q=-1' . $extraInfo . '">-1</a>';
					echo '}</td>';	
				} else {
					echo '<td>&nbsp;</td>';
				}
				echo '</tr>';
			}
		
			// Q not in stock
			echo '<tr>';
			echo '<td class="r">' . $q2 . 'x</td>';
			echo '<td>' . $prod . '</td>';
			echo '<td><strong>(*)</strong></td>';
			
			$tbc = true;
			
			echo '<td class="c">' . $price . '</td>';
			$sub2 = number_format($q2 * $price,2,'.','');
			echo '<td class="c">' . $sub2 . '</td>';
			if ($iscart) {
				$extraInfo = "&showCart";
				echo '<td class="r">{';
				if ($q2 > 3) echo '<a href="addToCart.php?id=' . $d . '&q=-4' . $extraInfo . '">-4</a>&nbsp;';
				if ($q2 > 2) echo '<a href="addToCart.php?id=' . $d . '&q=-3' . $extraInfo . '">-3</a>&nbsp;';
				if ($q2 > 1) echo '<a href="addToCart.php?id=' . $d . '&q=-2' . $extraInfo . '">-2</a>&nbsp;';
				echo '<a href="addToCart.php?id=' . $d . '&q=-1' . $extraInfo . '">-1</a>';
				echo '}</td>';	
			} else {
				echo '<td>&nbsp;</td>';
			}
			echo '</tr>';
		
		}
		
		

		echo "\n";			
    	
		$qTot += $q;
		$sub = $sub1 + $sub2;			
		$total += $sub;
    	
	}	
	echo '<tr><td class="bb" colspan="5">&nbsp;</td><td></td></tr>';
	echo '<tr><td class="bb" colspan="5">&nbsp;</td><td></td></tr>';
	echo '<tr><td colspan="3">&nbsp;' . $qTot . ' product';
	if ($qTot > 1) echo 's';
	echo '</td><td class="c">SUB</td><td class="c">' . number_format($total,2,'.','') . '</td><td>&nbsp;</td></tr>';
	
	if ($tbc) {
		echo '<tr><td colspan="6">&nbsp;</td></tr>';
		echo '<tr><td colspan="6"><strong>(*) To Be Confirmed</strong> - Your order contains items for which our current stock is not sufficient. We\'ll try to order these items with our vendors and provide you feedback within 2 working days.</td></tr>';
	}
	if ($preorder) {
		echo '<tr><td colspan="6">&nbsp;</td></tr>';
		echo '<tr><td colspan="6"><strong>PREORDER</strong> - Your order contains items that are not yet for sale. They will be available on their official release date. This order can be shipped as soon as the preorder items are available.</td></tr>';
	}

	
	return $total;
}

function mysqli_writeProd($row,$extraInfo,$userIsLoggedIn) {
	// $row is from DB
	$id = $row['id']; 
	$id += 250000; // products !!
	$prod = $row['productname'];

	$prod = pic_change_char($prod);

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
	echo '<td>Price = &euro; ' . $price . '</td>';
	echo '</tr><tr>';
	if ($quantity < 0) {
		echo '<td><i>No longer available - out of print</i></td>';
	} else if ($quantity == 0) {
		echo '<td><i>Not in our stock. Interested? Let us know!</i>';
	} else if ($quantity == 999) {
		echo '<td><i>Pre-Order item</i></td>';
	} else {
		echo '<td><i>In our stock, quantity = ' . $quantity . '</i></td>';
	}

	echo '</tr><tr>';
	echo '<td align="right">';
	if ($userIsLoggedIn && $quantity >= 0) {
		
		/*
		if (>= 3) - add begin (
		if (==x) - add end )
		CHECK !!
		
		*/
		
		if ($quantity > 3) echo '<a href="addToCart.php?id=' . $id . '&q=4&' . $extraInfo . '">+4</a>';
		if ($quantity > 2) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=3&' . $extraInfo . '">+3</a>';
		if ($quantity > 1) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=2&' . $extraInfo . '">+2</a>';
		if ($quantity > 0) echo '&nbsp;<a href="addToCart.php?id=' . $id . '&q=1&' . $extraInfo . '">+1</a>';
	}
	else echo '(Log in to order)';
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