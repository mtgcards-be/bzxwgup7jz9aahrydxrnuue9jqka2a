<?php 
include_once 'conn.inc.php'; 
include_once 'functions-cards.inc.php';
// include_once 'phpbb.inc.php';


if (isset($_REQUEST['set'])) $set = $_REQUEST['set'];
else $set = "";
if (isset($_REQUEST['rarity'])) $rarity = $_REQUEST['rarity'];
else $rarity = "";
if (isset($_REQUEST['color'])) $color = $_REQUEST['color'];
else $color = "";
if (isset($_REQUEST['sort'])) $sort = $_REQUEST['sort'];
else $sort = "N";
if (isset($_REQUEST['stock'])) $stock = $_REQUEST['stock'];
else $stock = "A";

?>
<form id="login" name="login" action="index.php?adv_search" method="post">
<h1 class="title">Advanced Search</h1>
<div class="entry"><p>
<select class="theInput" name="set">
<?php	

    $sql = "SELECT setcode, setname FROM sets";
//	$result = mysql_query($sql) or die ("Query mislukt : " . mysql_error());
    $result = mysqli_query($db_connection, $sql);
    while ($row = mysqli_fetch_array($result)) {
		echo '<option value="' . $row['setcode'] . '"';
		if ($row['setcode'] == $set) echo " selected";
		echo '>' . $row['setname'] . '</option>';
   	}
?>
</select>
<select class="theInput" name="rarity">
<?php	
    $sql = "SELECT raritycode, rarity FROM rarities";
//	$result = mysql_query($sql) or die ("Query mislukt : " . mysql_error());

  	$result = mysqli_query($db_connection, $sql);
  	echo '<option value="">-All rarities-</option>';
    while ($row = mysqli_fetch_array($result)) {
		echo '<option value="' . $row['raritycode'] . '"';
		if ($row['raritycode'] == $rarity) echo " selected";
		echo '>' . $row['rarity'] . '</option>';
   	}
?>
</select>
<select class="theInput" name="color">
<?php	
    $sql = "SELECT colorcode, color FROM colors";
    // $result = mysql_query($sql) or die ("Query mislukt : " . mysql_error());
     $result = mysqli_query($db_connection, $sql);
     echo '<option value="">-All colors-</option>';
	while ($row = mysqli_fetch_array($result)) {
		echo '<option value="' . $row['colorcode'] . '"';
		if ($row['colorcode'] == $color) echo " selected";
		echo '>' . $row['color'] . '</option>';
   	}
?>
</select>
<?php
/*
<select class="theInput" name="sort">
	<option value="N">Name (A&gt;Z)</option>
	<option value="C">Color</option>
	<option value="R">Rarity</option>
<!--
	<option value="P">Price (high&gt;low)</option>
	<option value="Q">Price (low&gt;high)</option>
-->
</select>
-
*/
?>
<?php 
if ($stock == "Y") {
	echo '<input type="radio" name="stock" value="A">All';
	echo '<input type="radio" name="stock" value="Y" checked>In stock only';
} else {
	echo '<input type="radio" name="stock" value="A" checked> All';
	echo '<input type="radio" name="stock" value="Y"> Stock';
}
?>
&nbsp; <input type="submit" class="button" name="submit" value="Search" />
</form>
</p></div>

<?php

if (isset($_REQUEST['start'])) $start = $_REQUEST['start'];
else $start = 0;


if (isset($_REQUEST['submit'])) {
	
	if ($set != "") $whereSQL = sprintf(" cards.setcode = '%s'", mysqli_real_escape_string($db_connection, $set));
	if ($rarity != "") $whereSQL .= sprintf(" AND cards.rarity = '%s'", mysqli_real_escape_string($db_connection, $rarity));
	if ($color != "") $whereSQL .= sprintf(" AND cards.color = '%s'", mysqli_real_escape_string($db_connection, $color));
	if ($stock == "Y") $whereSQL .= " AND cards.quantity > 0";
	$sql = "SELECT count(*) total FROM cards WHERE " . $whereSQL;
	//$result = mysql_query($sql) or die ("Query mislukt : " . mysql_error());
	  $result = mysqli_query($db_connection, $sql);
	//$num_rows = mysql_num_rows($result);
	$row = mysqli_fetch_array($result);
	$num_rows = $row['total'];
	

	
	
	if ($num_rows == 0) {
		echo '<h1 class="title">No results for this query...</h1>';
	} else {
		
		// include_once "functions.inc.php";
		$offset = 20;

	$sql = sprintf("SELECT trade_param, price_change, diff, cards.id id, cards.rarity r, cards.setcode setcode, cards.cardname cardname, cards.price price, cards.quantity quantity, cards.collector_nr collector_nr, sets.setname setname, rarities.rarity rarity, colors.color color FROM cards, sets, rarities, colors WHERE " . $whereSQL . " AND cards.setcode=sets.setcode AND cards.rarity=rarities.raritycode AND cards.color=colors.colorcode ORDER BY cardname LIMIT %d , %d",
     					mysqli_real_escape_string($db_connection,$start),
     					mysqli_real_escape_string($db_connection,$offset));
     					

	    //http://localhost/mtgcards2/index.php?adv_search&submit&set=AL&rarity=C&start=100
	    
		// $result = mysql_query($sql) or die ("Query mislukt : " . mysql_error());
	  $result = mysqli_query($db_connection, $sql);
	    $tot_pages = ceil($num_rows / $offset);
		$extraInfo = "";
	$txt_overview = '<h1 class="title">';
	    if ($tot_pages > 1) {    
		    
		    $i = 0;
		    $page = $start/$offset;
		    while ($i < $tot_pages) {
		        $extra = "&submit";
		        if ($set != "") $extra .= "&set=" . $set;
		        if ($rarity != "") $extra .= "&rarity=" . $rarity;
		        if ($color != "") $extra .= "&color=" . $color;
		        if ($i == $page) {
			        $extraInfo = 'start=' . ($i * $offset) . $extra;
		        	$txt_overview .= '[' . ++$i . ']&nbsp;';	
		        }
		        else $txt_overview .= '<a href="index.php?adv_search' . $extra . '&start=' . $i++ * $offset . '&stock=' . $stock . '">' . $i . '</a>&nbsp;';
		    }

	    }
	    $txt_overview .= ' (' . $num_rows . ' results)</h1>';
	echo $txt_overview;
	echo '<div class="entry"><p><table>';
	echo '<tr><td class="bb2" colspan="4">&nbsp;</td></tr>';

	while ($row = mysqli_fetch_array($result)) {
		writeCardPic($row,$extraInfo); // ,$userIsLoggedIn,false);
	}
   	echo '</table></p></div>';
   	echo $txt_overview;
   }
} 
?>
