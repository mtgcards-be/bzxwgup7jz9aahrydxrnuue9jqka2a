<?php
// include_once "phpbb.inc.php";
include_once "functions.inc.php";

include_once "functions-cart-mysqli.inc.php";

$user_id = $_SESSION['id'];

if (isset($_REQUEST['id'])) {
	$order_id = $_REQUEST['id'];
	
	
	$sql = "SELECT date, sub, sh_sub, sh, po, mtgcards_orderstatus.status status FROM mtgcards_orderstatus, mtgcards_orders WHERE mtgcards_orders.user_id='$user_id' ";
	$sql .= "AND mtgcards_orders.order_id = '$order_id' AND mtgcards_orders.status = mtgcards_orderstatus.id";
	$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error() . ' >> ' . $sql);
	$num_rows = mysqli_num_rows($result);
	if ($num_rows != 0) {
		echo '<h1 class="title"><a href="index.php?account&action=orders&start=0">Back</a> | mtgcards.be - ORDER #' . order_full($order_id) . '</h1>';

		$row = mysqli_fetch_array($result);
		echo '<div class="entry">';
		echo '<p>Date = <strong>' . $row['date'] . '</strong>';
		$tot = $row['sub'];
		$sh_cost = $row['sh_sub'];
		echo '<br/>Total price = SUB &euro; ' . number_format($tot,2,'.','') . ' + ';
		echo 'S&H &euro; ' . number_format($sh_cost,2,'.','') . ' = <strong>&euro; ' . number_format(($tot+$sh_cost),2,'.','') . '</strong>';
		$sh_full = sh_full($row['sh']);
		$po_full = po_full($row['po']);
		echo '<br/>Shipping option = ' . $sh_full;
		echo '<br/>Payment option = ' . $po_full . '</p>';
		$s = $row['status'];
		echo '<p>Status = ' . $s . '</p>';
		echo '</div>';
		echo '<h1 class="title">Order details</h1>';
		echo '<div class="entry"><p>';
		$sql = <<<SQL
SELECT
    c.color,
    oi.card_id,
    oi.quantity,
    cards.cardname,
    cards.rarity,
    cards.setcode,
    s.setname,
    oi.price
FROM
    mtgcards_orderinfo AS oi
INNER JOIN
    cards ON oi.card_id = cards.id
INNER JOIN
    sets AS s ON cards.setcode = s.setcode
INNER JOIN
    colors AS c ON c.colorcode = cards.color
INNER JOIN
    rarities AS r ON r.raritycode = cards.rarity
WHERE
    oi.order_id = '$order_id' AND oi.card_id < 250000
ORDER BY
    s.setname, r.order, c.order, cards.cardname;
SQL;

//		"SELECT colors.color color, mtgcards_orderinfo.card_id card_id, mtgcards_orderinfo.quantity, cards.cardname cardname, cards.rarity rarity, cards.setcode setcode, sets.setname setname, mtgcards_orderinfo.price price FROM rarities, colors, mtgcards_orderinfo, sets, cards WHERE mtgcards_orderinfo.card_id = cards.id AND mtgcards_orderinfo.order_id='$order_id' AND cards.setcode = sets.setcode AND mtgcards_orderinfo.card_id < 250000 AND colors.colorcode = cards.color AND rarities.raritycode = cards.rarity ORDER BY setname, rarities.order, colors.order, cardname";

$is_cart = false;


$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error() . "<br/><strong>" . $sql . "</strong>");
$num_rows = mysqli_num_rows($result);
if ($num_rows != 0) {
	echo '<table class="tCart">';
	echo '<tr><th>&nbsp;</th><th colspan="2">Cards</th><th class="c">&euro;</th><th class="c">Total</th>';
	echo '<th class="r">';
	if ($is_cart) echo '{Delete X}'; 
	else echo '&nbsp;';
	echo '</th></tr>';
	echo '<tr><td class="bb2" colspan="6">&nbsp;</td></tr>';
	//$extraInfo = "";
	$isOrder = true;
	$sub_1 = mysqli_writeCardsInCart($result,$is_cart,$isOrder);
	echo '</table><br/>';
}
$n_r = $num_rows;

// PRODs

// -----	 
// $sql = "SELECT mtgcards_products.price price, mtgcards_products.productname productname, tabel_a.new_id prod_id, tabel_a.quantity quantity FROM mtgcards_products, (SELECT mtgcards_orderinfo.card_id-250000 new_id, mtgcards_orderinfo.quantity quantity FROM mtgcards_orderinfo WHERE mtgcards_orderinfo.order_id='$order_id' AND mtgcards_orderinfo.card_id > 250000) tabel_a WHERE mtgcards_products.id = tabel_a.new_id ORDER BY productname";

$sql = <<<SQL
SELECT
    oi.price AS price,  -- Price from the order
    p.productname AS productname,
    oi.card_id - 250000 AS prod_id, -- Keep the original ID derivation
    oi.quantity AS quantity
FROM
    mtgcards_orderinfo AS oi
INNER JOIN
    mtgcards_products AS p ON p.id = oi.card_id - 250000  -- Join on the derived ID
WHERE
    oi.order_id = '$order_id' AND oi.card_id > 250000
ORDER BY
    p.productname;
SQL;

$result = mysqli_query($db_connection, $sql); // or die ("Query mislukt : " . mysql_error() . " " . $sql);
$num_rows = mysqli_num_rows($result);
$n_r += $num_rows;
// echo '<h2>Products</h2>';

if ($num_rows != 0) {
	echo '<table class="tCart">';
	echo '<tr><th>&nbsp;</th><th colspan="2">Products</th><th class="c">&euro;</th><th class="c">Total</th>';
	echo '<th class="r">';
	if ($is_cart) echo '{Delete X}';
	else echo '&nbsp;';
	echo '</th></tr>';
	echo '<tr><td class="bb2" colspan="6">&nbsp;</td></tr>';
	//$extraInfo = "";
	
	$sub_2 = mysqli_writeOrder($result);
	echo '</table>';
}

//$sub = number_format($q2 * $price,2,'.','');
//echo '<br/><strong>Total = &euro; ' . number_format($sh_cost + $sub_1 + $sub_2,2,'.','') . ' (incl. S&H)</strong>';


		echo '</table>';
		echo '</p></div>';
		
	
	} else {
		goto_path("index.php");
	}
} else {	
	goto_path("index.php");
}