<?php

function mysqli_writeCardsInCartHTML_Email ($result) {
	$total = 0;	$qTot = 0; $qC = 0;
	$msg_x = "\n"; $msg_x .= '<table width="60%">'; $msg_x .= "\n";	$msg_x .= '<tr>'; $msg_x .= "\n";
	$msg_x .= '<td style="border-bottom: solid 1px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="3">&nbsp;</td>';
	$msg_x .= "\n";	$msg_x .= '</tr>'; 	
	while ($row = mysqli_fetch_assoc($result)) {
		$cardname = $row['cardname'];
		$rarity = $row['rarity'];
		$color = $row['color'];
		$collector_nr = $row['collector_nr'];
		$price = $row['price'];
		$edition = $row['setname'];
		$q = $row['quantity'];
		$qC += $q;
		$msg_x .= '<tr>';
		$msg_x .= "\n";
		$msg_x .= '<td style="font-family: Arial; line-height: 1.3; font-size: 15px;">' . $q . 'x ' . $edition . ' - ' . $cardname . '</td>';
		$msg_x .= '<td style="font-family: Arial; line-height: 1.3; font-size: 15px;">[' .  $collector_nr . '|' . $rarity . ',' . $color . '] &euro; ' . $price . '</td>';
		$msg_x .= "\n";
		$sub = number_format($q * $price,2,'.','');
		$msg_x .= '<td style="font-family: Arial; line-height: 1.3; font-size: 15px;" align="center">' . '&euro; ' . $sub . '</td>';
		$msg_x .= "\n";	
		$qTot += $q;			
		$total += $sub;	
	}	
	$msg_x .= '<tr><td style="border-bottom: solid 1px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="3">&nbsp;</td></tr>';
	$msg_x .= '<td style="font-family: Arial; line-height: 1.3; font-size: 15px;" colspan="3">' . $qTot . ' card';
	if ($qTot > 1) $msg_x .= 's';
	$msg_x .= '</td></tr></table>';		
	return $msg_x;
}


function mysqli_writeProdsInCartHTML_Email ($result) {
	$total = 0;	$qTot = 0; $qC = 0;
	$msg_x = "\n"; $msg_x .= '<table width="60%">'; $msg_x .= "\n";	$msg_x .= '<tr>'; $msg_x .= "\n";
	$msg_x .= '<td style="border-bottom: solid 1px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="3">&nbsp;</td>';
	$msg_x .= "\n";	$msg_x .= '</tr>'; 	
	$preorder_in_cart = false;
    while ($row = mysqli_fetch_assoc($result)) {
		$product = $row['productname'];				
		$price = $row['price'];
		$q = $row['quantity'];
		$qDB = $row['quantityDB'];
		if ($qDB == 999) $preoder = true;
		else $preorder = false;
		if ($preorder) {
			$category = '<strong>PREORDER</strong>';
			$preorder_in_cart = true;
		}
		else $category = '&nbsp;';		
		$qC += $q;
		$msg_x .= '<tr>';
		$msg_x .= "\n";
		$msg_x .= '<td style="font-family: Arial; line-height: 1.3; font-size: 15px;">' . $q . 'x ' . $product . '</td>';
		$msg_x .= '<td style="font-family: Arial; line-height: 1.3; font-size: 15px;">' .  $category . ' &euro; ' . $price . '</td>';
		$msg_x .= "\n";
		$sub = number_format($q * $price,2,'.','');
		$msg_x .= '<td style="font-family: Arial; line-height: 1.3; font-size: 15px;" align="center">' . '&euro; ' . $sub . '</td>';
		$msg_x .= "\n";
		$msg_x .= '</tr>';
		$msg_x .= "\n";			
		$qTot += $q;			
		$total += $sub;
	}	
	$msg_x .= '<tr><td style="border-bottom: solid 1px #333; height: 0px; font-size: 0px; line-height: 0px; padding: 0px;" colspan="3">&nbsp;</td></tr>';
	$msg_x .= '<td style="font-family: Arial; line-height: 1.3; font-size: 15px;" colspan="3">' . $qTot . ' product';
	if ($qTot > 1) $msg_x .= 's';
	if ($preorder_in_cart) {
		$msg_x .= ' -- <strong>PREORDER</strong>: Your order contains items that are not yet for sale. They will be available on their official release date. This order can be shipped as soon as the preorder items are available.';
	}
	$msg_x .= '</td></tr></table>';		
	return $msg_x;
}


?>