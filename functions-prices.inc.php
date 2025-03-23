<?php


function getDesiredQuantity($r, $p) {
	$qx = 99;
	if ($r == "C" || $r == "L") {		
		if ($p < 0.20) $qx = 20;
		else if ($p < 0.30) $qx = 24;
		else if ($p < 0.40) $qx = 30;
		else $qx = 36;
	} else if ($r == "U") {
		if ($p < 0.30) $qx = 16;
		else if ($p < 0.80) $qx = 20;
		else $qx = 24;
	} else if ($r == "R" OR $r == "B") {
		if ($p < 0.60) $qx = 12;
		else if ($p < 1.00) $qx = 16;
		else $qx = 20;
	} else if ($r == "M") {
		$qx = 10;
	}
	return $qx;
} 

function mysqli_totalPriceCards ($result) {
	$total = 0;
	while ($row = mysqli_fetch_array($result)) {
		$p = $row['price'];
		$q = $row['quantity'];
		$total += ($q * $p);
	}
	return number_format($total,2,'.','');
}


function mysqli_totalPriceProducts ($result) {
	$total = 0;
	while ($row = mysqli_fetch_array($result)) {
		$price = $row['price'];
		$q = $row['quantity'];
		$total += ($q * $price);
	}
	return number_format($total,2,'.','');
}


function mysqli_totalQuantity ($result) {
	$total = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$q = $row['quantity'];
		$total += $q;
	}	
	return $total;
}


?>