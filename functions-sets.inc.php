<?php

// SET FUNCTIONS

include_once 'functions-prices.inc.php';

function showDetailsSet($setcode) {
	
	$sql = "SELECT setname FROM sets WHERE setcode = '$setcode'";
	$result = mysql_query($sql) or die ("Query mislukt : " . mysql_error());
	$row = mysql_fetch_array($result);
	$setname = $row['setname'];
	
	$sql = "SELECT price, quantity, rarity FROM cards WHERE setcode = '$setcode'";
	$cards_result = mysql_query($sql) or die ("Query mislukt : " . mysql_error());
	
	$qC = 0; $qCtot = 0; $pC = 0; $pCtot = 0;
	$qU = 0; $qUtot = 0; $pU = 0; $pUtot = 0;
	$qR = 0; $qRtot = 0; $pR = 0; $pRtot = 0;
	$qL = 0; $qLtot = 0; $pL = 0; $pLtot = 0;
	$qO = 0; $qOtot = 0; $pO = 0; $pOtot = 0;
	$qM = 0; $qMtot = 0; $pM = 0; $pMtot = 0;
	
	while ($row = mysql_fetch_array($cards_result)) {
		$r = $row['rarity'];
		$p = getPrice($r, $row['price']);
		$q = $row['quantity'];
		if ($r == "C") {
			$qC++;
			$qCtot += $q;
			$pC += $p;
			$pCtot += ($p*$q);
		} else if ($r == "U") {
			$qU++;
			$qUtot += $q;
			$pU += $p;
			$pUtot += ($p*$q);
		} else if ($r == "R") {
			$qR++;
			$qRtot += $q;
			$pR += $p;
			$pRtot += ($p*$q);
		} else if ($r == "L") {
			$qL++;
			$qLtot += $q;
			$pL += $p;
			$pLtot += ($p*$q);
		} else if ($r == "M") {
			$qM++;
			$qMtot += $q;
			$pM += $p;
			$pMtot += ($p*$q);
		} else {
			$qO++;
			$qOtot += $q;
			$pO += $p;
			$pOtot += ($p*$q);
		}
	}
	if ($qC != 0) $aC = $pC / $qC;
	else $aC = 0;
	if ($qU != 0) $aU = $pU / $qU;
	else $aU = 0;
	if ($qR != 0) $aR = $pR / $qR;
	else $aR = 0;
	if ($qL != 0) $aL = $pL / $qL;
	else $aL = 0;
	if ($qM != 0) $aM = $pM / $qM;
	else $aM = 0;
	if ($qO != 0) $aO = $pO / $qO;
	else $aO = 0;
	echo '<blockquote>';
	echo '<h1>' . $setname . '</h1>';
	echo '<h3>Overview</h3>';
	echo '<p>Commons = ' . $qC . ' cards -> &euro; ' . number_format($pC,2) . ' = &euro; ' . number_format($aC,2) . '/common';
	echo '<br/>Uncommons = ' . $qU . ' cards -> &euro; ' . number_format($pU,2) . ' = &euro; ' . number_format($aU,2) . '/uncommon';
	echo '<br/>Rares = ' . $qR . ' cards -> &euro; ' . number_format($pR,2) . ' = &euro; ' . number_format($aR,2) . '/rare';
	echo '<br/>Mythic Rares = ' . $qM . ' cards -> &euro; ' . number_format($pM,2) . ' = &euro; ' . number_format($aM,2) . '/mythic';
	echo '<br/>Lands = ' . $qL . ' cards -> &euro; ' . number_format($pL,2) . ' = &euro; ' . number_format($aL,2) . '/land';
	echo '<br/>Other = ' . $qO . ' cards -> &euro; ' . number_format($pO,2) . ' = &euro; ' . number_format($aO,2) . '/other</p>';
	$qTot = $qC + $qU + $qR + $qL + $qO + $qM;
	$pTot = $pC + $pU + $pR + $pL + $pO + $pM;
	if ($qTot != 0) $aTot = $pTot / $qTot;
	else $aTot = 0;
	echo '<p>' . $qTot . ' cards -> &euro; ' . number_format($pTot,2) . ' (&euro; ' . number_format($aTot,2) . ')</p>';
	echo '<h3>Database</h3>';
	echo '<p>Commons = ' . $qCtot . ' cards -> &euro; ' . number_format($pCtot,2);
	echo '<br/>Uncommons = ' . $qUtot . ' cards -> &euro; ' . number_format($pUtot,2);
	echo '<br/>Rares = ' . $qRtot . ' cards -> &euro; ' . number_format($pRtot,2);
	echo '<br/>Mythic Rares = ' . $qMtot . ' cards -> &euro; ' . number_format($pMtot,2);
	echo '<br/>Lands = ' . $qLtot . ' cards -> &euro; ' . number_format($pLtot,2);
	echo '<br/>Other = ' . $qOtot . ' cards -> &euro; ' . number_format($pOtot,2);
	$qTot = $qCtot + $qUtot + $qRtot + $qLtot + $qOtot + $qMtot;
	$pTot = $pCtot + $pUtot + $pRtot + $pLtot + $pOtot + $pMtot;
	echo '<p>' . $qTot . ' cards -> &euro; ' . number_format($pTot,2) . '</p>';
	
	
	$x = 0;
	if ($qM != 0) $x = (7*$aR + $aM)/8;
	else $x = $aR;
	
	$b = 11*$aC + 3*$aU + $x;
	echo '<h3>Booster</h3>';
	echo '<p>Price = ' . number_format($b,2) . '</p>';
	
	echo '</blockquote>';
}

?>