<?php

// CART FUNCTIONS

// include_once 'conn.inc.php';

function mysqli_writeOrder ($result) {
//writeCardsInCart($row,$extraInfo);
	$total = 0;
	$qTot = 0;
	$qC = 0;
		
    while ($row = mysqli_fetch_assoc($result)) {
    
		$c = $row['prod_id'];
		$d = $c + 250000;
		$prod = $row['productname'];
		$price = $row['price'];
		$q = $row['quantity'];
		$qC += $q;
				
		echo '<tr>';
		echo '<td class="r">' . $q . 'x</td>';
		echo '<td>' . $prod . '</td>';
		echo '<td>&nbsp;</td>'; // category?
		echo '<td class="c">' . $price . '</td>';
		
		$sub = number_format($q * $price,2,'.','');
		
		echo '<td class="c">' . $sub . '</td>';
		echo '<td>&nbsp;</td>';
		echo '</tr>';
		echo "\n";			
    	
		$qTot += $q;
		$total += $sub;
    	
	}	
	echo '<tr><td class="bb" colspan="5">&nbsp;</td><td></td></tr>';
	echo '<tr><td class="bb" colspan="5">&nbsp;</td><td></td></tr>';
	echo '<tr><td colspan="3">&nbsp;' . $qTot . ' product';
	if ($qTot > 1) echo 's';
	echo '</td><td class="c">SUB</td><td class="c">' . number_format($total,2,'.','') . '</td><td>&nbsp;</td></tr>';
		
	return $total;
}

function mysqli_checkCartQuantities ($user_id) {
	
	// CARDS
	$error = '';
	$sql_txt = "SELECT cards.rarity rarity, cards.id card_id, mtgcards_cart.quantity qCart, cards.quantity qCard FROM mtgcards_cart, cards ";
	$sql_txt .= "WHERE mtgcards_cart.card_id = cards.id AND mtgcards_cart.user_id = '%d' AND mtgcards_cart.card_id < 250000";
	$sql = sprintf($sql_txt, mysqli_real_escape_string($db_connection, $user_id));
   	// $result = mysql_query($sql);
   	$result = mysqli_query($db_connection, sprintf($sql_txt, mysqli_real_escape_string($db_connection, $user_id)));
   	while ($row = mysqli_fetch_array($result)) {
	   	$qCard = $row['qCard'];
	   	$qCart = $row['qCart'];
	   	$card_id = $row['card_id'];
		$rarity = $row['rarity'];
		
		/*
		Update : R/M = 4x ; U/C = 8x ; else limit = 20
		*/
		if ($rarity == 'M' || $rarity == 'R') $limit = 4;
		else if ($rarity == 'U') $limit = 8;
		else $limit = 20;
		
	   	if ($qCart > $qCard || $qCart > $limit) {
	   		if ($qCard == 0 OR $qCart <= 0) {
				$error = 'X';
	   			$sql1 = sprintf("DELETE FROM mtgcards_cart WHERE user_id='%d' AND card_id='%d'",
	   				mysqli_real_escape_string($db_connection, $user_id),
	   				mysqli_real_escape_string($db_connection, $card_id));
	   			// $result1 = mysql_query($sql1);
	   			$result1 = mysqli_query($db_connection, $sql1);
	   		} else {
				if ($qCart > $limit) {
					$error = $rarity;
					$qCard = $limit;
				}
		   		$sql1 = sprintf("UPDATE mtgcards_cart SET quantity='%d' WHERE user_id='%d' AND card_id='%d'",
		        		mysqli_real_escape_string($db_connection, $qCard),
		        		mysqli_real_escape_string($db_connection, $user_id),
		        		mysqli_real_escape_string($db_connection, $card_id));
   				// $result1 = mysql_query($sql1);
   				$result1 = mysqli_query($db_connection, $sql1);
   			}
	   	} 
   	}   
	
	// PRODUCTS
   	   	
   	   	
   	$sql = "SELECT tabel_a.new_id card_id, tabel_a.qCart qCart, mtgcards_products.quantity qProd FROM mtgcards_products, (SELECT mtgcards_cart.card_id-250000 new_id, mtgcards_cart.quantity qCart FROM mtgcards_cart WHERE mtgcards_cart.user_id='$user_id' AND mtgcards_cart.card_id > 250000) tabel_a WHERE mtgcards_products.id = tabel_a.new_id";
   	
  	   	
//   	$result = mysql_query($sql);
   	$result = mysqli_query($db_connection, $sql);
   	while ($row = mysqli_fetch_array($result)) {
	   	$qCard = $row['qProd'];
	   	$qCart = $row['qCart'];
	   	$card_id = $row['card_id'] + 250000;
	   	if ($qCart > $qCard) {
	   		if ($qCard == 0 OR $qCart <= 0) {
	   			$sql1 = sprintf("DELETE FROM mtgcards_cart WHERE user_id='%d' AND card_id='%d'",
	   				mysqli_real_escape_string($db_connection, $user_id),
	   				mysqli_real_escape_string($db_connection, $card_id));
	   			// $result1 = mysql_query($sql1);
				$result1 = mysqli_query($db_connection, $sql1);
	   		} else {
		   		$sql1 = sprintf("UPDATE mtgcards_cart SET quantity='%d' WHERE user_id='%d' AND card_id='%d'",
		        		mysqli_real_escape_string($db_connection, $qCard),
		        		mysqli_real_escape_string($db_connection, $user_id),
		        		mysqli_real_escape_string($db_connection, $card_id));
   				// $result1 = mysql_query($sql1);
 				$result1 = mysqli_query($db_connection, $sql1);  			
 			}
			$error = 'X';
	   	} 
		/* OGW Fat Packs 
	   	if ($card_id == 250902 && $qCart > 1) {
	   		$sql1 = sprintf("UPDATE mtgcards_cart SET quantity=1 WHERE user_id='%d' AND card_id='%d'",
		        		mysqli_real_escape_string($db_connection, $user_id),
		        		mysqli_real_escape_string($db_connection, $card_id));
   				// $result1 = mysql_query($sql1);
   				$result1 = mysqli_query($db_connection, $sql1);
   				$error = 'Y';
	   	}
		*/		
   	}   	
	return $error;
}   	

function mysqli_checkCartQuantities_2 ($db_connection, $user_id) {
	
	// CARDS
	$error = '';
	$sql_txt = "SELECT cards.rarity rarity, cards.id card_id, mtgcards_cart.quantity qCart, cards.quantity qCard FROM mtgcards_cart, cards ";
	$sql_txt .= "WHERE mtgcards_cart.card_id = cards.id AND mtgcards_cart.user_id = '%d' AND mtgcards_cart.card_id < 250000";
	$sql = sprintf($sql_txt, mysqli_real_escape_string($db_connection, $user_id));
   	// $result = mysql_query($sql);
   	$result = mysqli_query($db_connection, sprintf($sql_txt, mysqli_real_escape_string($db_connection, $user_id)));
   	while ($row = mysqli_fetch_array($result)) {
	   	$qCard = $row['qCard'];
	   	$qCart = $row['qCart'];
	   	$card_id = $row['card_id'];
		$rarity = $row['rarity'];
		
		/*
		Update : R/M = 4x ; U/C = 8x ; else limit = 20
		*/
		if ($rarity == 'M' || $rarity == 'R') $limit = 4;
		else if ($rarity == 'U') $limit = 8;
		else $limit = 20;
		
	   	if ($qCart > $qCard || $qCart > $limit) {
	   		if ($qCard == 0 OR $qCart <= 0) {
				$error = 'X';
	   			$sql1 = sprintf("DELETE FROM mtgcards_cart WHERE user_id='%d' AND card_id='%d'",
	   				mysqli_real_escape_string($db_connection, $user_id),
	   				mysqli_real_escape_string($db_connection, $card_id));
	   			// $result1 = mysql_query($sql1);
	   			$result1 = mysqli_query($db_connection, $sql1);
	   		} else {
				if ($qCart > $limit) {
					$error = $rarity;
					$qCard = $limit;
				}
		   		$sql1 = sprintf("UPDATE mtgcards_cart SET quantity='%d' WHERE user_id='%d' AND card_id='%d'",
		        		mysqli_real_escape_string($db_connection, $qCard),
		        		mysqli_real_escape_string($db_connection, $user_id),
		        		mysqli_real_escape_string($db_connection, $card_id));
   				// $result1 = mysql_query($sql1);
   				$result1 = mysqli_query($db_connection, $sql1);
   			}
	   	} 
   	}   
	
	// PRODUCTS
   	   	
   	   	
   	$sql = "SELECT tabel_a.new_id card_id, tabel_a.qCart qCart, mtgcards_products.quantity qProd FROM mtgcards_products, (SELECT mtgcards_cart.card_id-250000 new_id, mtgcards_cart.quantity qCart FROM mtgcards_cart WHERE mtgcards_cart.user_id='$user_id' AND mtgcards_cart.card_id > 250000) tabel_a WHERE mtgcards_products.id = tabel_a.new_id";
   	
  	   	
//   	$result = mysql_query($sql);
   	$result = mysqli_query($db_connection, $sql);
   	while ($row = mysqli_fetch_array($result)) {
	   	$qCard = $row['qProd'];
	   	$qCart = $row['qCart'];
	   	$card_id = $row['card_id'] + 250000;
	   	if ($qCart > $qCard) {
	   		if ($qCard == 0 OR $qCart <= 0) {
	   			$sql1 = sprintf("DELETE FROM mtgcards_cart WHERE user_id='%d' AND card_id='%d'",
	   				mysqli_real_escape_string($db_connection, $user_id),
	   				mysqli_real_escape_string($db_connection, $card_id));
	   			// $result1 = mysql_query($sql1);
				$result1 = mysqli_query($db_connection, $sql1);
	   		} else {
		   		$sql1 = sprintf("UPDATE mtgcards_cart SET quantity='%d' WHERE user_id='%d' AND card_id='%d'",
		        		mysqli_real_escape_string($db_connection, $qCard),
		        		mysqli_real_escape_string($db_connection, $user_id),
		        		mysqli_real_escape_string($db_connection, $card_id));
   				// $result1 = mysql_query($sql1);
 				$result1 = mysqli_query($db_connection, $sql1);  			
 			}
			$error = 'X';
	   	} 
	   	/* OGW Fat Packs 
	   	if ($card_id == 250902 && $qCart > 1) {
	   		$sql1 = sprintf("UPDATE mtgcards_cart SET quantity=1 WHERE user_id='%d' AND card_id='%d'",
		        		mysqli_real_escape_string($db_connection, $user_id),
		        		mysqli_real_escape_string($db_connection, $card_id));
   				// $result1 = mysql_query($sql1);
   				$result1 = mysqli_query($db_connection, $sql1);
   				$error = 'Y';
	   	}
		*/
   	}   	
	return $error;
}   

function mysqli_writeCardsInCartHTML ($result) {
	$total = 0;
	$qTot = 0;
	$qC = 0;

	$msg_x = "\n";	
	$P = '<p style="font-size:13"><u>Cards</u><br/><br/>';
	$msg_x .= $P . '<table width="60%">';
	$msg_x .= "\n";
	$msg_x .= '<tr>';
	$msg_x .= "\n";
	$msg_x .= '<td style="border-bottom: solid 2px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td>';
	$msg_x .= "\n";
	$msg_x .= '</tr>';
    	
    	while ($row = mysqli_fetch_assoc($result)) {
    
		$cardname = $row['cardname'];
		$rarity = $row['rarity'];
		$color = $row['color'];
		$setcode = $row['setcode'];
		
		$price = $row['price'];
		$edition = $row['setname'];
		$q = $row['quantity'];
		$qC += $q;

		$msg_x .= '<tr>';
		$msg_x .= "\n";
		$msg_x .= '<td style="font-size:13; face=Georgia;">' . $q . 'x</td>';
		$msg_x .= "\n";
		$msg_x .= '<td style="font-size:13; face=Georgia;">' . $edition . ' - ' . $cardname . '</td><td style="font-size:13; face=Georgia;">' . $rarity . ' , ' . $color . '</td>';
		$msg_x .= "\n";
		$msg_x .= '<td style="font-size:13; face=Georgia;" align="center">&euro; ' . $price . '</td>';
		$msg_x .= "\n";
		$sub = number_format($q * $price,2,'.','');
		$msg_x .= '<td style="font-size:13; face=Georgia;" align="center">&euro; ' . $sub . '</td>';
		$msg_x .= "\n";
		$msg_x .= '</tr>';
		$msg_x .= "\n";			
    	
		$qTot += $q;			
		$total += $sub;
    	
	}	
	$msg_x .= '<tr><td style="border-bottom: solid 2px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td></tr>';
	$msg_x .= "\n";
	$msg_x .= "</table>";
	
	$msg_x .= '<strong>&nbsp;&nbsp;' . $qTot . ' card';
	if ($qTot > 1) $msg_x .= 's';
	$msg_x .= ' / &euro; ' . number_format($total,2,'.','') . '</strong></p>';
	
	return $msg_x;
}

function mysqli_writeCardsInCartHTML_v2 ($result) {
	$total = 0;
	$qTot = 0;
	$qC = 0;

	$msg_x = "\n";	
	$P = '<p style="font-size:25"><u>Cards</u><br/><br/>';
	$msg_x .= $P . '<table width="100%">';
	$msg_x .= "\n";
	$msg_x .= '<tr>';
	$msg_x .= "\n";
	$msg_x .= '<td style="border-bottom: solid 2px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td>';
	$msg_x .= "\n";
	$msg_x .= '</tr>';
    	
    	while ($row = mysqli_fetch_assoc($result)) {
    
		$cardname = $row['cardname'];
		$rarity = $row['rarity'];
		$color = $row['color'];
		$setcode = $row['setcode'];
		$cnr = $row['cnr'];
		
		$price = $row['price'];
		$edition = $row['setname'];
		$q = $row['quantity'];
		$qC += $q;


/*

			echo "<tr onMouseOver=\"this.bgColor='";
			if ($q3 == 0) echo "yellow";
			else echo "orange";
			echo "';\" onMouseOut=\"this.bgColor='#F9F7F1';\"><td>";

			echo '<strong><font color="';
*/

		$msg_x .= '<tr onMouseOver="this.bgColor=\'yellow\'" onMouseOut="this.bgColor=\'white\'">';
		$msg_x .= "\n";
		$msg_x .= '<td style="font-size:30; face=Courier;">[<strong>' . $cnr . '</strong>] </td>';
		$msg_x .= "\n";
		$msg_x .= '<td style="font-size:40; face=Georgia;"><strong>' . $q . 'x ' . $cardname . '</strong></td><td style="font-size:30; face=Georgia;">' . $rarity . ' , ' . $color . '</td>';
		$msg_x .= "\n";
		$msg_x .= '<td style="font-size:30; face=Georgia;" align="center">&euro; ' . $price . '</td>';
		$msg_x .= "\n";
		$sub = number_format($q * $price,2,'.','');
		$msg_x .= '<td style="font-size:30; face=Georgia;" align="center">&euro; ' . $sub . '</td>';
		$msg_x .= "\n";
		$msg_x .= '</tr>';
		$msg_x .= "\n";
		$msg_x .= '<tr><td style="border-bottom: dotted 1px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td></tr>';		
    	
		$qTot += $q;			
		$total += $sub;
    	
	}	
	$msg_x .= '<tr><td style="border-bottom: solid 2px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td></tr>';
	$msg_x .= "\n";
	$msg_x .= "</table>";
	
	$msg_x .= '<strong>&nbsp;&nbsp;' . $qTot . ' card';
	if ($qTot > 1) $msg_x .= 's';
	$msg_x .= ' / &euro; ' . number_format($total,2,'.','') . '</strong></p>';
	
	return $msg_x;
}

function mysqli_writeProdsInCartHTML ($result) {
	
	// quantityDB !!!
	
	
	// if ($qDB == 999) // --> preorder
	
	$total = 0;
	$qTot = 0;
	$qC = 0;
	
	$msg_x = "\n";	
	$P = '<p style="font-size:13"><u>Products</u><br/><br/>';
	$msg_x .= $P . '<table width="60%">';
	$msg_x .= "\n";
	$msg_x .= '<tr>';
	$msg_x .= "\n";
	$msg_x .= '<td style="border-bottom: solid 2px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td>';
	$msg_x .= "\n";
	$msg_x .= '</tr>';
	$msg_x .= "\n";
	$tbc = false;
	$preorder_in_cart = false;
    	while ($row = mysqli_fetch_assoc($result)) {
		$product = $row['productname'];				
		$price = $row['price'];
		$q = $row['quantity'];
		
		//		$preorder = $row['preorder'];
		$qDB = $row['quantityDB'];
		if ($qDB == 999) $preorder = true;
		else $preorder = false;
		
		if ($preorder) {
			$category = '<strong>PREORDER</strong>';
			$preorder_in_cart = true;
		}
		else $category = '&nbsp;';
				
		$qC += $q;
		$msg_x .= '<tr>';
		$msg_x .= "\n";
		$msg_x .= '<td style="font-size:13; face=Georgia;">' . $q . 'x</td>';
		$msg_x .= "\n";		
		$msg_x .= '<td style="font-size:13; face=Georgia;">' . $product . '</td><td style="font-size:13; face=Georgia;">' . $category . '</td>';
		$msg_x .= "\n";
		$msg_x .= '<td style="font-size:13; face=Georgia;" align="center">&euro; ' . $price . '</td>';
		$msg_x .= "\n";
		$sub = number_format($q * $price,2,'.','');
		$msg_x .= '<td style="font-size:13; face=Georgia;" align="center">&euro; ' . $sub . '</td>';
		$msg_x .= "\n";
		$msg_x .= '</tr>';
		$msg_x .= "\n";			
		$qTot += $q;			
		$total += $sub;
		

		
    	
	}	
	
	$msg_x .= '<tr><td style="border-bottom: solid 2px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="5">&nbsp;</td></tr>';
	$msg_x .= "\n";
	$msg_x .= "</table>";
	
	$msg_x .= '<strong>&nbsp;&nbsp;' . $qTot . ' product';
	if ($qTot > 1) $msg_x .= 's';
	$msg_x .= ' / &euro; ' . number_format($total,2,'.','') . '</strong></p>';
	
	if ($tbc) {
		$msg_x .= $P . '<strong>(*) To Be Confirmed</strong> - Your order contains items for which our current stock is not sufficient. We\'ll try to order these items with our vendors and provide you feedback within 2 working days.</p>';
	}
	if ($preorder_in_cart) {
		$msg_x .= $P . '<strong>PREORDER</strong> - Your order contains items that are not yet for sale. They will be available on their official release date. This order can be shipped as soon as the preorder items are available.</p>';
	}
	
	return $msg_x;
}

function mysqli_writeCardsInCart ($result,$iscart,$isOrder = false) {
//writeCardsInCart($row,$extraInfo);
	$total = 0;
	$qTot = 0;
	$qC = 0;
	$prevSet = "";
    	while ($row = mysqli_fetch_assoc($result)) {
    
		$c = $row['card_id'];
		$cardname = $row['cardname'];
		$rarity = $row['rarity'];
		$color = $row['color'];
		$setcode = $row['setcode'];
		
		$price = $row['price'];
		
		$edition = $row['setname'];
		$q = $row['quantity'];
		$qC += $q;
		
		echo '<tr>';
		echo '<td class="r">' . $q . 'x</td>';
		echo '<td>' . $edition . ' - ' . $cardname . '</td><td class="r">' . $rarity . ' , ' . $color . '</td>';
		echo '<td class="c">' . $price . '</td>';
		$sub = number_format($q * $price,2,'.','');
		echo '<td class="c">' . $sub . '</td>';
		if ($iscart) {
			$extraInfo = "&showCart";
			echo '<td class="r">{';
			if ($q > 3) echo '<a href="addToCart.php?id=' . $c . '&q=-4' . $extraInfo . '">-4</a>&nbsp;';
			if ($q > 2) echo '<a href="addToCart.php?id=' . $c . '&q=-3' . $extraInfo . '">-3</a>&nbsp;';
			if ($q > 1) echo '<a href="addToCart.php?id=' . $c . '&q=-2' . $extraInfo . '">-2</a>&nbsp;';
			echo '<a href="addToCart.php?id=' . $c . '&q=-1' . $extraInfo . '">-1</a>';
			echo '}</td>';	
		} else {
			echo '<td>&nbsp;</td>';
		}
		echo '</tr>';
		echo "\n";			
    	
		$qTot += $q;			
		$total += $sub;
    	
	}	
	echo '<tr><td class="bb" colspan="5">&nbsp;</td><td></td></tr>';
	echo '<tr><td class="bb" colspan="5">&nbsp;</td><td></td></tr>';
	echo '<tr><td colspan="3">&nbsp;' . $qTot . ' card';
	if ($qTot > 1) echo 's';
	echo '</td><td class="c">SUB</td><td class="c">' . number_format($total,2,'.','') . '</td><td>&nbsp;</td></tr>';
	return $total;
}
?>